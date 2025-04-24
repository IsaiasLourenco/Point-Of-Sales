<?php
@session_start();
require_once("../../conexao.php");

$id_usuario = $_SESSION['id_usuario'];
$nome = "PRODUTO NÃO ENCONTRADO!!!";
$descricao = "PRODUTO NÃO ENCONTRADO!!!";
$valor = "NÃO ENCONTRADO!!!";
$quantidade = $_POST['quantidade'];
$estoque = "NÃO ENCONTRADO!!!";
$imagem = "sem-foto.jpg";
$estoque = "NÃO ENCONTRADO!!!";
$codigo = $_POST['codigo'];

$query = $pdo->query("SELECT * FROM produtos WHERE codigo = '$codigo'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if (@count($res) > 0) {
    $id_produto = $res[0]['id'];
    $nome = $res[0]['nome'];
    $descricao = $res[0]['descricao'];
    $valor = $res[0]['valor_venda'];
    $estoque = $res[0]['estoque'];
    $imagem = $res[0]['imagem'];

    $valor_total = $quantidade * $valor;
    $query_iten = $pdo->prepare("INSERT INTO itens_venda SET produto = :produto, valor_unitario = :valor_unitario, quantidade = :quantidade, valor_total = :valor_total, usuario = :usuario, venda = '0', data_venda = curDate()");
    $query_iten->bindValue(":produto", $id_produto);
    $query_iten->bindValue(":valor_unitario", $valor);
    $query_iten->bindValue(":quantidade", $quantidade);
    $query_iten->bindValue(":valor_total", $valor_total);
    $query_iten->bindValue(":usuario", $id_usuario);
    $query_iten->execute();
    }

$dados = $nome . '&-/z' . $descricao . '&-/z' . $valor . '&-/z' . $estoque . '&-/z' . $imagem;
echo $dados;
