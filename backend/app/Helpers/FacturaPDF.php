<?php
// Autoload composer only if available
if (file_exists(__DIR__ . '/../../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../../vendor/autoload.php';
}

use Dompdf\Dompdf;

if (!function_exists('generarFacturaPDF')) {
function generarFacturaPDF($emisor, $receptor, $productos, $total, $uuid, $folio, $fecha, $metodo_pago, $forma_pago) {
    $html = '<html><head><style>
    body { font-family: Arial, sans-serif; background: #f8f6fa; color: #222; }
    .factura-box { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px #0001; max-width: 700px; margin: 30px auto; padding: 32px 28px; }
    .titulo { color: #4a47a3; font-size: 2em; font-weight: bold; text-align: center; margin-bottom: 0; }
    .subtitulo { color: #888; text-align: center; margin-bottom: 24px; font-size: 1.1em; }
    .seccion { margin-bottom: 22px; }
    .seccion-titulo { color: #4a47a3; font-weight: bold; font-size: 1.1em; border-bottom: 2px solid #4a47a3; margin-bottom: 8px; padding-bottom: 2px; letter-spacing: 1px; }
    .dato { margin-bottom: 4px; }
    .dato strong { color: #222; }
    .datos-fiscales { margin-bottom: 18px; }
    .tabla-productos { width: 100%; border-collapse: collapse; margin-top: 18px; }
    .tabla-productos th { background: #e3e6fd; color: #4a47a3; font-weight: bold; border: 1px solid #c5c5c5; padding: 7px; }
    .tabla-productos td { border: 1px solid #e0e0e0; padding: 7px; font-size: 1em; }
    .total { text-align: right; font-size: 1.2em; color: #4a47a3; font-weight: bold; margin-top: 10px; }
    .nota { font-size: .95em; color: #888; margin-top: 24px; text-align: center; }
    </style></head><body><div class="factura-box">';
    $html .= '<div class="titulo">FACTURA ELECTRÓNICA</div>';
    $html .= '<div class="subtitulo">CFDI Fiscal Digital</div>';

    $html .= '<div class="seccion"><div class="seccion-titulo">EMISOR</div>';
    $html .= '<div class="dato"><strong>Razón Social:</strong> '.htmlspecialchars($emisor['razon_social']).'</div>';
    $html .= '<div class="dato"><strong>RFC:</strong> '.htmlspecialchars($emisor['rfc']).'</div>';
    $html .= '<div class="dato"><strong>Régimen Fiscal:</strong> '.$emisor['regimen'].' - General de Ley Personas Morales</div>';
    $html .= '</div>';

    $html .= '<div class="seccion"><div class="seccion-titulo">RECEPTOR</div>';
    $html .= '<div class="dato"><strong>Cliente:</strong> '.htmlspecialchars($receptor['nombre']).'</div>';
    $html .= '<div class="dato"><strong>RFC:</strong> '.htmlspecialchars($receptor['rfc']).'</div>';
    $html .= '<div class="dato"><strong>Uso CFDI:</strong> '.$receptor['uso_cfdi'].' – Gastos en general</div>';
    $html .= '</div>';

    $html .= '<div class="seccion"><div class="seccion-titulo">DATOS FISCALES</div>';
    $html .= '<div class="dato"><strong>Serie:</strong> A</div>';
    $html .= '<div class="dato"><strong>Folio:</strong> '.htmlspecialchars($folio).'</div>';
    $html .= '<div class="dato"><strong>UUID:</strong> '.htmlspecialchars($uuid).'</div>';
    $html .= '<div class="dato"><strong>Fecha:</strong> '.date('j/n/Y', strtotime($fecha)).'</div>';
    $html .= '<div class="dato"><strong>Método de Pago:</strong> '.htmlspecialchars($metodo_pago).'</div>';
    $html .= '<div class="dato"><strong>Forma de Pago:</strong> '.htmlspecialchars($forma_pago).'</div>';
    $html .= '</div>';

    $html .= '<table class="tabla-productos"><thead><tr><th>Producto</th><th>Cantidad</th><th>Precio unitario</th><th>Importe</th></tr></thead><tbody>';
    foreach ($productos as $p) {
        $html .= '<tr><td>'.htmlspecialchars($p['name']).'</td><td>'.$p['qty'].'</td><td>$'.number_format($p['price'],2).'</td><td>$'.number_format($p['qty']*$p['price'],2).'</td></tr>';
    }
    $html .= '</tbody></table>';
    $html .= '<div class="total">Total: $'.number_format($total,2).'</div>';

    // Add simulated QR and sello (fake) at the bottom
    $qrSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120">'
        . '<rect width="120" height="120" fill="#fff" stroke="#222"/>'
        . '<rect x="6" y="6" width="32" height="32" fill="#222"/>'
        . '<rect x="82" y="6" width="32" height="32" fill="#222"/>'
        . '<rect x="6" y="82" width="32" height="32" fill="#222"/>'
        . '<rect x="40" y="40" width="10" height="10" fill="#222"/>'
        . '<rect x="56" y="56" width="8" height="8" fill="#222"/>'
        . '<rect x="72" y="72" width="6" height="6" fill="#222"/>'
        . '</svg>';
    $selloText = 'SELLO DIGITAL: ' . substr($uuid,0,16) . '...SELLOFAKE';

    $html .= '<div style="display:flex;align-items:center;justify-content:space-between;margin-top:18px;">';
    $html .= '<div style="flex:1">';
    $html .= '<p style="font-size:0.9em;color:#666">Esta factura es una representación impresa del CFDI. El XML timbrado está disponible para descarga en tu perfil.</p>';
    $html .= '<p style="font-size:0.8em;color:#444"><strong>Sello:</strong><br>' . htmlspecialchars($selloText) . '</p>';
    $html .= '</div>';
    $html .= '<div style="width:140px;text-align:center;">';
    $html .= '<div style="display:inline-block;border:1px solid #ddd;padding:6px;background:#fff;">' . $qrSvg . '</div>';
    $html .= '<div style="font-size:0.75em;margin-top:6px;color:#666">QR (simulado)</div>';
    $html .= '</div>';
    $html .= '</div>';

    $html .= '</div></body></html>';
    if (!class_exists('\Dompdf\\Dompdf')) {
        // Dompdf no disponible: devolver una representación simple en texto para no bloquear el flujo
        return "%PDF-1.4\n% Dompdf no instalado - factura de texto:\n" . strip_tags($html);
    }
    $dompdfClass = '\\Dompdf\\Dompdf';
    $dompdf = new $dompdfClass();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    return $dompdf->output();
}
}
