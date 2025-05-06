<?php 

require_once('../config.php');

$id = $_GET['id'];

//ALIMENTAR OS DADOS NO RELATÓRIO
// $html = file_get_contents($url_sistema."rel/comprovante.php?id=".$id);

$_GET['id'] = $id; // Passa o ID para o script incluído
ob_start();
include(__DIR__ . '/../rel/comprovante.php'); // Caminho físico no servidor
$html = ob_get_clean();



//CARREGAR DOMPDF
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);

$pdf = new DOMPDF($options);

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