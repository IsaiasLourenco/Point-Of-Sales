<?php 
    require_once("conexao.php");

    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $query = $pdo->prepare("SELECT * FROM usuarios WHERE (email = :usuario OR cpf = :usuario) AND senha = :senha");
    $query->bindValue("usuario", "$usuario");
    $query->bindValue("senha", "$senha");
    $query->execute();
    
?>