<?php
// Stripe keys: leer desde variables de entorno para evitar exponer secretos en el repositorio
// Configura en tu entorno (Apache/Nginx o variables del sistema):
//   STRIPE_SECRET_KEY, STRIPE_PUBLISHABLE_KEY
// En desarrollo también puedes cargar desde un archivo no versionado.

$__stripeSecret = getenv('STRIPE_SECRET_KEY') ?: '';
$__stripePublishable = getenv('STRIPE_PUBLISHABLE_KEY') ?: '';

define('STRIPE_SECRET_KEY', $__stripeSecret);
define('STRIPE_PUBLISHABLE_KEY', $__stripePublishable);
