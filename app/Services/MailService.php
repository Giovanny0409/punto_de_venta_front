<?php
// app/Services/MailService.php
// Servicio para envío de correos (facturas) usando PHPMailer si está instalado.

class MailService
{
    // Envía un correo con posible adjunto. Recibe configuración desde config('mail').
    public static function send(string $to, string $subject, string $htmlBody, ?string $attachTmpPath = null, ?string $attachName = null): bool
    {
        // Cargar Composer autoload si existe
        $composer = BASE_PATH . '/vendor/autoload.php';
        if (is_file($composer)) {
            require_once $composer;
        }

        // Verifica que PHPMailer esté disponible
        if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            // No instalado: devolver false para que la UI lo indique
            return false;
        }

        $settings = config('mail') ?? [];

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            if (!empty($settings['smtp'])) {
                $smtp = $settings['smtp'];
                $mail->isSMTP();
                $mail->Host = $smtp['host'] ?? 'localhost';
                $mail->SMTPAuth = (bool)($smtp['auth'] ?? true);
                $mail->Username = $smtp['user'] ?? '';
                $mail->Password = $smtp['pass'] ?? '';
                // Seguridad: aceptar string ('tls'|'ssl') o constante si se quiere
                $secure = $smtp['secure'] ?? 'tls';
                $mail->SMTPSecure = $secure;
                $mail->Port = (int)($smtp['port'] ?? 587);
            }

            $from = $settings['from'] ?? ['email' => 'no-reply@example.com', 'name' => 'Sistema'];
            $mail->setFrom($from['email'], $from['name']);
            $mail->addAddress($to);

            if ($attachTmpPath) {
                $mail->addAttachment($attachTmpPath, $attachName ?: basename($attachTmpPath));
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;
            $mail->AltBody = strip_tags($htmlBody);

            return $mail->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            return false;
        }
    }
}
