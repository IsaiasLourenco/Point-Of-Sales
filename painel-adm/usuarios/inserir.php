<?php
require_once("../../conexao.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];
$nivel = $_POST['nivel'];
$cpf_double = $_POST['cpf_double'];
$email_double = $_POST['email_double'];

//DUPLICIDADE EMAIL
if ($email_double != $email) {
    $res_double = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $res_double->bindValue(":email", "$email");
    $res_double->execute();
    $email_usu = $res_double->fetch(PDO::FETCH_ASSOC);
    if ($email_usu) {
        echo 'Email já cadastrado!';
        exit();
    }
}

//DUPLICIDADE CPF
if ($cpf_double != $cpf) {
    $res_double = $pdo->prepare("SELECT * FROM usuarios WHERE cpf = :cpf");
    $res_double->bindValue(":cpf", "$cpf");
    $res_double->execute();
    $cpf_usu = $res_double->fetch(PDO::FETCH_ASSOC);
    if ($cpf_usu) {
        echo 'CPF já cadastrado!';
        exit();
    }
}

if ($id == "") {
    $res = $pdo->prepare("INSERT INTO usuarios SET nome = :nome, email = :email, cpf = :cpf, senha = :senha, nivel= :nivel");
    $res->bindValue(":nome", "$nome");
    $res->bindValue(":email", "$email");
    $res->bindValue(":cpf", "$cpf");
    $res->bindValue(":senha", "$senha");
    $res->bindValue(":nivel", "$nivel");
    $res->execute();
} else {
    $res = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf, senha = :senha, nivel= :nivel WHERE id = :id");
    $res->bindValue(":id", "$id");
    $res->bindValue(":nome", "$nome");
    $res->bindValue(":email", "$email");
    $res->bindValue(":cpf", "$cpf");
    $res->bindValue(":senha", "$senha");
    $res->bindValue(":nivel", "$nivel");
    $res->execute();
}
echo 'Salvo com Sucesso!';
