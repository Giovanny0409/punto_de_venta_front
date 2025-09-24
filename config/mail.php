<?php
// config/mail.php
// Configuración para PHPMailer con Gmail usando App Password.
// Requisitos Gmail:
// - Activar Verificación en dos pasos (2FA) en la cuenta
// - Crear un "App Password" para "Mail"
// - Usar ese App Password como 'pass'
// - El remitente ('from.email') debe ser la misma cuenta o un alias autorizado
return [
    'from' => ['email' => 'giovannyrosasgonzalez4@gmail.com', 'name' => 'Punto de Venta'],
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'auth' => true,
        'user' => 'puntodeventaumb@gmail.com',
        'pass' => 'gficrgeogbibtaae',
        // 'tls' (STARTTLS) en 587 es lo recomendado por Gmail
        'secure' => 'tls',
        'port' => 587,
        // Habilitar depuración temporal (0=off, 2=verbose). Quitar en producción.
        'debug' => 0,
    ],
];
