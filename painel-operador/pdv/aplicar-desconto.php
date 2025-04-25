<?php
@session_start();
require_once("../../conexao.php");

$id_usuario = $_SESSION['id_usuario'];
$desconto = $_POST['desconto'] ?? 0;

// Calcula o total da compra com desconto
$query_total = $pdo->query("SELECT SUM(valor_total) as total FROM itens_venda WHERE usuario = '$id_usuario' AND venda = 0");
$res_total = $query_total->fetchAll(PDO::FETCH_ASSOC);

if (@count($res_total) > 0) {
    $total_compra = $res_total[0]['total'] - $desconto;
} else {
    $total_compra = 0;
}

echo number_format($total_compra, 2, ',', '.'); // Apenas retorna o total formatado