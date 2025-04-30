<?php
require_once("../../conexao.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$codigo = $_POST['codigo'];
$nome_double = $_POST['nome_double'];

//DUPLICIDADE NOME 
if ($nome_double != $nome) {
    $res_double = $pdo->prepare("SELECT * FROM forma_pgtos WHERE nome = :nome");
    $res_double->bindValue(":nome", "$nome");
    $res_double->execute();
    $nome_usu = $res_double->fetch(PDO::FETCH_ASSOC);
    if ($nome_usu) {
        echo 'Forma de Pagamento já cadastrado!';
        exit();
    }
}

if ($id == "") {
    $res = $pdo->prepare("INSERT INTO forma_pgtos SET codigo = :codigo, nome = :nome");
    $res->bindValue(":codigo", "$codigo");
    $res->bindValue(":nome", "$nome");
    $res->execute();
} else {
    $res = $pdo->prepare("UPDATE forma_pgtos SET codigo = :codigo, nome = :nome WHERE id = :id");
    $res->bindValue(":id", "$id");
    $res->bindValue(":codigo", "$codigo");
    $res->bindValue(":nome", "$nome");
    $res->execute();
}
echo 'Salvo com Sucesso!';
