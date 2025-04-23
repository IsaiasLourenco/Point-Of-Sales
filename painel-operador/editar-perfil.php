<?php
require_once("../conexao.php");

$id = $_POST['id-perfil'];
$nome = $_POST['nome-perfil'];
$email = $_POST['email-perfil'];
$cpf = $_POST['cpf-perfil'];
$senha = $_POST['senha-perfil'];
$cpf_double = $_POST['cpf_double-perfil'];
$email_double = $_POST['email_double-perfil'];

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

$res = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf, senha = :senha WHERE id = :id");
$res->bindValue(":id", "$id");
$res->bindValue(":nome", "$nome");
$res->bindValue(":email", "$email");
$res->bindValue(":cpf", "$cpf");
$res->bindValue(":senha", "$senha");
$res->execute();

echo 'Salvo com Sucesso!';
