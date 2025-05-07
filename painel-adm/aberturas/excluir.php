<?php
require_once('../../conexao.php');

$id = $_POST['id'];

$query = $pdo->query("UPDATE vendas SET status_venda = 'Cancelada' WHERE id = '$id'");

$res = $pdo->query("SELECT * FROM itens_venda WHERE venda = '$id'");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$novo_estoque = 0;
for ($i = 0; $i < count($dados); $i++) {
    foreach ($dados[$i] as $key => $value) {
    }
    $id_produto = $dados[$i]['produto'];
    $quantidade = $dados[$i]['quantidade'];
    $id_item_venda = $dados[$i]['id'];


    $res_prod = $pdo->query("SELECT * FROM produtos WHERE id = '$id_produto'");
    $dados_prod = $res_prod->fetchAll(PDO::FETCH_ASSOC);
    $estoque = $dados_prod[0]['estoque'];
    $novo_estoque = $estoque + $quantidade;

    $pdo->query("UPDATE produtos SET estoque = '$novo_estoque' WHERE id = '$id_produto'");

    $pdo->query("DELETE FROM itens_venda WHERE id = '$id_item_venda'");

}

echo 'Cancelado com Sucesso!';