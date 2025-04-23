<?php
require_once("../../conexao.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$nome_double = $_POST['nome_double'];

//DUPLICIDADE NOME 
if ($nome_double != $nome) {
    $res_double = $pdo->prepare("SELECT * FROM caixas WHERE nome = :nome");
    $res_double->bindValue(":nome", "$nome");
    $res_double->execute();
    $nome_usu = $res_double->fetch(PDO::FETCH_ASSOC);
    if ($nome_usu) {
        echo 'Caixa já cadastrado!';
        exit();
    }
}

if ($id == "") {
    $res = $pdo->prepare("INSERT INTO caixas SET nome = :nome");
    $res->bindValue(":nome", "$nome");
    $res->execute();
} else {
    $res = $pdo->prepare("UPDATE caixas SET nome = :nome WHERE id = :id");
    $res->bindValue(":id", "$id");
    $res->bindValue(":nome", "$nome");
    $res->execute();
}
echo 'Salvo com Sucesso!';
