<?php 

require_once('config.php');

$id = $_GET['id'];

//ALIMENTAR OS DADOS NO RELATÓRIO
$html = file_get_contents($url_sistema."comprovante.php?id=".$id);

if($relatorio_pdf != 'Sim'){
	echo $html;
	exit();
}


//CARREGAR DOMPDF
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;


$pdf = new DOMPDF();

//Definir o tamanho do papel e orientação da página
$pdf->setPaper(array(0, 0, 497.64, 700), 'portrait');

//CARREGAR O CONTEÚDO HTML
$pdf->loadHtml($html);

//RENDERIZAR O PDF
$pdf->render();

//NOMEAR O PDF GERADO
$pdf->stream(
'comprovante.pdf',
array("Attachment" => false)
);


?>