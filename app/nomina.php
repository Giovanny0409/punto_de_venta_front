<?php
// Redirige al POS oficial del proyecto para evitar duplicar código
// Si se accede a /app/nomina.php, enviamos a /public/pos.php
header('Location: ../public/pos.php');
http_response_code(302);
?>
