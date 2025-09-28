<?php
namespace App\Controllers;
use App\Helpers\DB;
use App\Helpers\PACClient;
class OrderController {
    public function checkoutForm() {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: index.php');
            exit;
        }
        include __DIR__ . '/../Views/orders/checkout.php';
    }
    public function placeOrder() {
    require_once __DIR__ . '/../Helpers/stripe_keys.php';
    require_once __DIR__ . '/../../vendor/autoload.php';
    require_once __DIR__ . '/../Helpers/FacturaPDF.php';
        $pdo = DB::get();
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: index.php');
            exit;
        }
        $name = $_POST['name'] ?? 'Cliente';
        $email = $_POST['email'] ?? 'cliente@local';
        $stripeToken = $_POST['stripeToken'] ?? null;
        if (!$stripeToken) {
            die('Error: No se recibió el token de pago.');
        }
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$user) {
            $insu = $pdo->prepare("INSERT INTO users (name, email, password, rfc) VALUES (?,?,?,?)");
            $insu->execute([$name, $email, 'guest', 'XAXX010101000']);
            $userId = $pdo->lastInsertId();
        } else {
            $userId = $user['id'];
        }
        $pdo->beginTransaction();
        try {
            $total = 0;
            $ids = array_keys($cart);
            $ids = array_map('intval', $ids);
            $in = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
            $stmt->execute($ids);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rows as $r) {
                $qty = $cart[$r['id']];
                $total += $qty * $r['price'];
            }
            // Simulación de pago exitoso (sin Stripe)
            $pagoExitoso = true;
            if (!$pagoExitoso) {
                throw new \Exception('El pago no fue exitoso.');
            }
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status) VALUES (?,?,?)");
            $stmt->execute([$userId, $total, 'paid']);
            $orderId = $pdo->lastInsertId();
            $ins = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?,?,?,?)");
            foreach ($rows as $r) {
                $qty = $cart[$r['id']];
                $ins->execute([$orderId, $r['id'], $qty, $r['price']]);
            }
            $xmlPath = __DIR__ . '/../../storage/invoices/order_' . $orderId . '.xml';
            $pdfPath = __DIR__ . '/../../storage/invoices/order_' . $orderId . '.pdf';
            // Crear carpeta si no existe
            $dir = dirname($xmlPath);
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            $emisor = [
                'rfc' => 'AAA010101AAA',
                'nombre' => 'MiTienda S.A. de C.V.',
                'regimen' => '601',
                'razon_social' => 'MiTienda S.A. de C.V.'
            ];
            $receptor = [
                'rfc' => ($_POST['rfc'] ?? 'XAXX010101000'),
                'nombre' => $name,
                'email' => $email,
                'uso_cfdi' => 'G03'
            ];
            $serie = 'A';
            $folio = $orderId;
            $uuid = strtoupper(bin2hex(random_bytes(16)));
            $fecha = date('Y-m-d\TH:i:s');
            $metodo_pago = 'PUE';
            $forma_pago = '04'; // Tarjeta
            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd" Serie="' . $serie . '" Folio="' . $folio . '" Fecha="' . $fecha . '" Total="' . number_format($total,2,'.','') . '" Moneda="MXN" MetodoPago="' . $metodo_pago . '" FormaPago="' . $forma_pago . '" TipoDeComprobante="I" LugarExpedicion="64000">' . "\n";
            $xml .= '  <cfdi:Emisor Rfc="' . $emisor['rfc'] . '" Nombre="' . $emisor['razon_social'] . '" RegimenFiscal="' . $emisor['regimen'] . '" />' . "\n";
            $xml .= '  <cfdi:Receptor Rfc="' . $receptor['rfc'] . '" Nombre="' . htmlspecialchars($receptor['nombre']) . '" UsoCFDI="' . $receptor['uso_cfdi'] . '" />' . "\n";
            $xml .= '  <cfdi:Conceptos>' . "\n";
            foreach ($rows as $r) {
                $qty = $cart[$r['id']];
                $xml .= '    <cfdi:Concepto ClaveProdServ="01010101" Cantidad="' . $qty . '" ClaveUnidad="H87" Unidad="Pieza" Descripcion="' . htmlspecialchars($r['name']) . '" ValorUnitario="' . number_format($r['price'],2,'.','') . '" Importe="' . number_format($qty * $r['price'],2,'.','') . '" />' . "\n";
            }
            $xml .= '  </cfdi:Conceptos>' . "\n";
            $xml .= '  <cfdi:Complemento>' . "\n";
            $xml .= '    <tfd:TimbreFiscalDigital xmlns:tfd="http://www.sat.gob.mx/TimbreFiscalDigital" Version="1.1" UUID="' . $uuid . '" FechaTimbrado="' . $fecha . '" RfcProvCertif="AAA010101AAA" SelloCFD="SELLOFAKE" NoCertificadoSAT="00001000000403258748" />' . "\n";
            $xml .= '  </cfdi:Complemento>' . "\n";
            $xml .= '</cfdi:Comprobante>' . "\n";
            file_put_contents($xmlPath, $xml);
            // Generar PDF de factura
            $productos = [];
            foreach ($rows as $r) {
                $productos[] = [
                    'name' => $r['name'],
                    'qty' => $cart[$r['id']],
                    'price' => $r['price']
                ];
            }
            $pdfData = generarFacturaPDF($emisor, $receptor, $productos, $total, $uuid, $folio, $fecha, $metodo_pago, $forma_pago);
            file_put_contents($pdfPath, $pdfData);
            $pdo->prepare("INSERT INTO invoices (order_id, uuid, xml_path, pdf_path, timbre_status) VALUES (?,?,?,?,?)")
                ->execute([$orderId, $uuid, $xmlPath, $pdfPath, 'timbrado']);
            $pdo->commit();
            unset($_SESSION['cart']);

            // Enviar factura PDF por correo (no XML)
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true;
                $mail->Username = 'puntodeventaumb@gmail.com'; 
                $mail->Password = 'gficrgeogbibtaae'; 
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('puntodeventaumb@gmail.com', 'MiTienda');
                $mail->addAddress($email, $name);
                $mail->Subject = 'Factura electrónica de tu compra en MiTienda';
                $mail->isHTML(true);
                $mail->Body = '<h2 style="color:#388e3c;">¡Gracias por tu compra!</h2>' .
                    '<p>Adjuntamos tu factura electrónica en PDF. <br>El XML timbrado está disponible para descarga en tu perfil.</p>' .
                    '<ul>' .
                    '<li><b>Emisor:</b> ' . $emisor['razon_social'] . ' (' . $emisor['rfc'] . ')</li>' .
                    '<li><b>Receptor:</b> ' . htmlspecialchars($receptor['nombre']) . ' (' . $receptor['rfc'] . ')</li>' .
                    '<li><b>Uso CFDI:</b> ' . $receptor['uso_cfdi'] . '</li>' .
                    '<li><b>Serie/Folio:</b> ' . $serie . '-' . $folio . '</li>' .
                    '<li><b>UUID:</b> ' . $uuid . '</li>' .
                    '<li><b>Fecha:</b> ' . $fecha . '</li>' .
                    '<li><b>Método de pago:</b> Tarjeta (PUE)</li>' .
                    '</ul>' .
                    '<p style="color:#888;">Cualquier duda, contáctanos.</p>';
                $mail->addAttachment($pdfPath, 'factura_' . $folio . '.pdf');
                $mail->send();
            } catch (\Exception $e) {
                // Si falla el correo, solo mostrar mensaje en pantalla
            }

            include __DIR__ . '/../Views/orders/success.php';
        } catch (\Exception $e) {
            $pdo->rollBack();
            echo 'Error: ' . $e->getMessage();
        }
    }
}
