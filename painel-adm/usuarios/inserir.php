<?php 
    require_once("../../conexao.php");

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];
    $nivel = $_POST['nivel'];

    //DUPLICIDADE EMAIL
    $res_double = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $res_double->bindValue(":email", "$email"); 
    $res_double->execute();
    $email_usu = $res_double->fetch(PDO::FETCH_ASSOC);
    if ($email_usu) {
        echo 'Email já cadastrado!';
        exit();
    }
    
    //DUPLICIDADE CPF
    $res_double = $pdo->prepare("SELECT * FROM usuarios WHERE cpf = :cpf");
    $res_double->bindValue(":cpf", "$cpf"); 
    $res_double->execute();
    $cpf_usu = $res_double->fetch(PDO::FETCH_ASSOC);
    if ($cpf_usu) {
        echo 'CPF já cadastrado!';
        exit();
    }

    $res = $pdo->prepare("INSERT INTO usuarios SET nome = :nome, email = :email, cpf = :cpf, senha = :senha, nivel= :nivel");
    $res->bindValue(":nome", "$nome");
    $res->bindValue(":email", "$email");
    $res->bindValue(":cpf", "$cpf");
    $res->bindValue(":senha", "$senha");
    $res->bindValue(":nivel", "$nivel");
    $res->execute();

    echo 'Salvo com Sucesso!';
?>