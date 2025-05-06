<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../config.php');
require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Verifica se o ID foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID do comprovante não foi fornecido na URL.');
}

$id = $_GET['id'];
$_GET['id'] = $id; // Garante que fique disponível no include()

// Captura o HTML do comprovante
ob_start();
include(__DIR__ . '/../rel/comprovante.php');
$html = ob_get_clean();

// Configura e gera o PDF
$options = new Options();
$options->set('isRemoteEnabled', true);

$pdf = new Dompdf($options);
$pdf->setPaper([0, 0, 497.64, 700], 'portrait');
$pdf->loadHtml($html);
$pdf->render();
$pdf->stream('comprovante.pdf', ['Attachment' => false]);
