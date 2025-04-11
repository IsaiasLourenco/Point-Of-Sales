<?php
require_once("../../conexao.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$cnpj = $_POST['cnpj'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$cep = $_POST['cep'];
$rua = $_POST['rua'];
$numero = $_POST['numero'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cnpj_double = $_POST['cnpj_double'];
$email_double = $_POST['email_double'];

//DUPLICIDADE EMAIL
if ($email_double != $email) {
    $res_double = $pdo->prepare("SELECT * FROM fornecedores WHERE email = :email");
    $res_double->bindValue(":email", "$email");
    $res_double->execute();
    $email_usu = $res_double->fetch(PDO::FETCH_ASSOC);
    if ($email_usu) {
        echo 'Email já cadastrado!';
        exit();
    }
}

//DUPLICIDADE CPF
if ($cnpj_double != $cnpj) {
    $res_double = $pdo->prepare("SELECT * FROM fornecedores WHERE cnpj = :cnpj");
    $res_double->bindValue(":cnpj", "$cnpj");
    $res_double->execute();
    $cpf_usu = $res_double->fetch(PDO::FETCH_ASSOC);
    if ($cpf_usu) {
        echo 'CNPJ já cadastrado!';
        exit();
    }
}

if ($id == "") {
    $res = $pdo->prepare("INSERT INTO fornecedores SET nome = :nome, cnpj = :cnpj, email = :email, telefone = :telefone, cep = :cep, rua = :rua, numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado");
    $res->bindValue(":nome", "$nome");
    $res->bindValue(":cnpj", "$cnpj");
    $res->bindValue(":email", "$email");
    $res->bindValue(":telefone", "$telefone");
    $res->bindValue(":cep", "$cep");
    $res->bindValue(":rua", "$rua");
    $res->bindValue(":numero", "$numero");
    $res->bindValue(":bairro", "$bairro");
    $res->bindValue(":cidade", "$cidade");
    $res->bindValue(":estado", "$estado");
    $res->execute();
} else {
    $res = $pdo->prepare("UPDATE fornecedores SET nome = :nome, cnpj = :cnpj, email = :email, telefone = :telefone, cep = :cep, rua = :rua, numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado WHERE id = :id");
    $res->bindValue(":id", "$id");
    $res->bindValue(":nome", "$nome");
    $res->bindValue(":cnpj", "$cnpj");
    $res->bindValue(":email", "$email");
    $res->bindValue(":telefone", "$telefone");
    $res->bindValue(":cep", "$cep");
    $res->bindValue(":rua", "$rua");
    $res->bindValue(":numero", "$numero");
    $res->bindValue(":bairro", "$bairro");
    $res->bindValue(":cidade", "$cidade");
    $res->bindValue(":estado", "$estado");
    $res->execute();
}
echo 'Salvo com Sucesso!';
