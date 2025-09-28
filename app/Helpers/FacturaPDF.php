<?php
use Dompdf\Dompdf;
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

    // Emisor
    $html .= '<div class="seccion"><div class="seccion-titulo">EMISOR</div>';
    $html .= '<div class="dato"><strong>Razón Social:</strong> '.htmlspecialchars($emisor['razon_social']).'</div>';
    $html .= '<div class="dato"><strong>RFC:</strong> '.htmlspecialchars($emisor['rfc']).'</div>';
    $html .= '<div class="dato"><strong>Régimen Fiscal:</strong> '.$emisor['regimen'].' - General de Ley Personas Morales</div>';
    $html .= '</div>';

    // Receptor
    $html .= '<div class="seccion"><div class="seccion-titulo">RECEPTOR</div>';
    $html .= '<div class="dato"><strong>Cliente:</strong> '.htmlspecialchars($receptor['nombre']).'</div>';
    $html .= '<div class="dato"><strong>RFC:</strong> '.htmlspecialchars($receptor['rfc']).'</div>';
    $html .= '<div class="dato"><strong>Uso CFDI:</strong> '.$receptor['uso_cfdi'].' – Gastos en general</div>';
    $html .= '</div>';

    // Datos fiscales
    $html .= '<div class="seccion"><div class="seccion-titulo">DATOS FISCALES</div>';
    $html .= '<div class="dato"><strong>Serie:</strong> A</div>';
    $html .= '<div class="dato"><strong>Folio:</strong> '.htmlspecialchars($folio).'</div>';
    $html .= '<div class="dato"><strong>UUID:</strong> '.htmlspecialchars($uuid).'</div>';
    $html .= '<div class="dato"><strong>Fecha:</strong> '.date('j/n/Y', strtotime($fecha)).'</div>';
    $html .= '<div class="dato"><strong>Método de Pago:</strong> '.$metodo_pago.'</div>';
    $html .= '<div class="dato"><strong>Forma de Pago:</strong> '.$forma_pago.' – Efectivo</div>';
    $html .= '</div>';

    // Productos
    $html .= '<table class="tabla-productos"><thead><tr><th>Producto</th><th>Cantidad</th><th>Precio unitario</th><th>Importe</th></tr></thead><tbody>';
    foreach ($productos as $p) {
        $html .= '<tr><td>'.htmlspecialchars($p['name']).'</td><td>'.$p['qty'].'</td><td>$'.number_format($p['price'],2).'</td><td>$'.number_format($p['qty']*$p['price'],2).'</td></tr>';
    }
    $html .= '</tbody></table>';
    $html .= '<div class="total">Total: $'.number_format($total,2).'</div>';

    $html .= '<div class="nota">Esta factura es una representación impresa del CFDI. El XML timbrado está disponible para descarga en tu perfil.</div>';
    $html .= '</div></body></html>';
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    return $dompdf->output();
}
