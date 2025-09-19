<?php
// config/mail.php
// Configuración para PHPMailer. Rellena con tus credenciales reales.
return [
    'from' => ['email' => 'facturacion@tuservidor.com', 'name' => 'Punto de Venta'],
    'smtp' => [
        'host' => 'smtp.tuservidor.com',
        'auth' => true,
        'user' => 'facturacion@tuservidor.com',
        'pass' => 'tu_contraseña',
        // Usa 'tls' o 'ssl'
        'secure' => 'tls',
        'port' => 587,
    ],
];
