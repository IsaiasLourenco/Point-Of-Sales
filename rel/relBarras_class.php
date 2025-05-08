<?php

require_once('../config.php');

$codigo = $_GET['codigo'];

//ALIMENTAR OS DADOS NO RELATÓRIO
$html = file_get_contents($url_sistema . "rel/relBarras.php?codigo=$codigo");

echo $html;
exit();

//CARREGAR DOMPDF
require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);

$pdf = new DOMPDF($options);

//Definir o tamanho do papel e orientação da página
$pdf->setPaper('A4', 'portrait'); //caso queira a folha em paisagem use landscape em vez de portrait

//CARREGAR O CONTEÚDO HTML
$pdf->loadHtml($html);

//RENDERIZAR O PDF
$pdf->render();

//NOMEAR O PDF GERADO
$pdf->stream(
	'barras.pdf',
	array("Attachment" => false)
);
