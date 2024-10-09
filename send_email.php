<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];

    $pdf = $_FILES['pdf'];

    // Verificar que el archivo se haya subido correctamente
    if ($pdf['error'] === UPLOAD_ERR_OK) {
        $pdfName = $pdf['name'];
        $pdfTmpPath = $pdf['tmp_name'];
        $pdfSize = $pdf['size'];
        $pdfType = $pdf['type'];

        //  Verificar el tamaño del archivo o tipo antes de continuar
        if ($pdfSize > 10000000) { 
            echo "El archivo es demasiado grande.";
            exit;
        }

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.';
            $mail->SMTPAuth   = true;
            $mail->Username   = '';
            $mail->Password   = '';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Destinatarios (establecer una dirección de correo electrónico fija)
            $mail->setFrom('', 'Mailer');
            $mail->addAddress('congresocostos2024@utalca.cl', 'Congreso 2024'); 

            // Adjuntar archivo PDF
            $mail->addAttachment($pdfTmpPath, $pdfName);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Registro Congreso 2024';
            $mail->Body    = "Nombre: $nombre<br>Apellido: $apellido<br>Email: $email<br>Se ha adjuntado archivo PDF.";

            // Enviar correo
            $mail->send();
            echo 'El mensaje ha sido enviado con éxito.';
        } catch (Exception $e) {
            echo "El mensaje no pudo ser enviado. Error de PHPMailer: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error al subir el archivo.";
    }
}
?>