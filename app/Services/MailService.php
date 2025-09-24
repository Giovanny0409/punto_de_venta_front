<?php
// app/Services/MailService.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private static ?string $lastError = null;

    public static function send(string $to, string $subject, string $htmlBody, ?string $attachTmpPath = null, ?string $attachName = null): bool
    {
        self::$lastError = null;

        // Cargar autoload de Composer usando la raíz del proyecto
        $composer = defined('BASE_PATH')
            ? BASE_PATH . '/vendor/autoload.php'
            : __DIR__ . '/../../vendor/autoload.php';
        if (is_file($composer)) {
            require_once $composer;
        }

        if (!class_exists(PHPMailer::class)) {
            self::$lastError = 'PHPMailer no está instalado. Ejecuta: composer install';
            return false;
        }

        $settings = function_exists('config') ? (config('mail') ?? []) : [];

        $mail = new PHPMailer(true);
        try {
            // SMTP solo si está configurado
            if (!empty($settings['smtp'])) {
                $smtp = $settings['smtp'];
                $mail->isSMTP();
                $mail->Host       = $smtp['host'] ?? 'localhost';
                $mail->SMTPAuth   = (bool)($smtp['auth'] ?? true);
                $mail->Username   = (string)($smtp['user'] ?? '');
                $mail->Password   = (string)($smtp['pass'] ?? '');
                $secure           = strtolower((string)($smtp['secure'] ?? 'tls'));
                if (in_array($secure, ['tls','ssl','starttls'], true)) {
                    $mail->SMTPSecure = $secure === 'starttls' ? PHPMailer::ENCRYPTION_STARTTLS : $secure;
                }
                $mail->Port       = (int)($smtp['port'] ?? 587);

                if (isset($smtp['debug'])) $mail->SMTPDebug = (int)$smtp['debug'];
                if (!empty($smtp['allow_self_signed'])) {
                    $mail->SMTPOptions = [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true,
                        ],
                    ];
                }
            }

            $from = $settings['from'] ?? ['email' => 'no-reply@example.com', 'name' => 'Sistema POS'];
            $mail->setFrom((string)$from['email'], (string)$from['name']);
            $mail->addAddress($to);

            $mail->CharSet  = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = strip_tags($htmlBody);

            if ($attachTmpPath && is_file($attachTmpPath)) {
                $mail->addAttachment($attachTmpPath, $attachName ?: basename($attachTmpPath));
            }

            $ok = $mail->send();
            if (!$ok) self::$lastError = $mail->ErrorInfo ?: 'Error desconocido al enviar';
            return $ok;
        } catch (Exception $e) {
            self::$lastError = $e->getMessage();
            return false;
        }
    }

    public static function lastError(): ?string
    {
        return self::$lastError;
    }
}
