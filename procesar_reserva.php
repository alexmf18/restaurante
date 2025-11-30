<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurante";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$nombre = $_POST['nombre'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$comensales = $_POST['comensales'] ?? '';
$comentarios = $_POST['comentarios'] ?? '';

if (empty($nombre) || empty($telefono) || empty($comensales)) {
    die("Error: Campos incompletos.");
}

// Guardar en base de datos
$stmt = $conn->prepare("INSERT INTO reservas (nombre, telefono, comensales, comentarios, fecha_reserva) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("ssss", $nombre, $telefono, $comensales, $comentarios);
$stmt->execute();
$stmt->close();
$conn->close();

// Enviar correo de confirmación
$mail = new PHPMailer(true);
try {
    // Configuración SMTP (ejemplo con Gmail)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'alexmf188@gmail.com';        // Tu correo
    $mail->Password   = 'dxjc iwzm njxg mzdc';         // Contraseña de app (no la del correo)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('alexmf188@gmail.com', 'Sabores del Mar');
    $mail->addAddress('alexmf188@gmail.com'); // Copia para el restaurante
    // Si quieres enviar al cliente, necesitas su email (aquí no lo tenemos)

    $mail->isHTML(true);
    $mail->Subject = 'Nueva Reserva - Sabores del Mar';
    $mail->Body    = "
        <h3>Nueva Reserva Recibida</h3>
        <p><strong>Nombre:</strong> $nombre</p>
        <p><strong>Teléfono:</strong> $telefono</p>
        <p><strong>Comensales:</strong> $comensales</p>
        <p><strong>Comentarios:</strong> " . ($comentarios ?: 'Ninguno') . "</p>
    ";

    $mail->send();
} catch (Exception $e) {
    // Error de correo (pero la reserva ya se guardó)
}

// Redirigir a éxito
echo "<body style='background: #1e1e1e'>
        <div class='alert alert-success text-center mt-5' style='margin: 50px auto; max-width: 600px; background: #1e1e1e; color: white; border-color: #c6894d;'>
        <h4>¡Reserva confirmada!</h4>
        <p>Gracias por reservar en <strong>Sabores del Mar</strong>.</p>
        <a href='index.html' class='btn' style='background: #c6894d; color: white;'>Volver al inicio</a>
        </div>
      </body>";
?>