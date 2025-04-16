<?php
@session_start(); 
require_once("conexao.php");

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

$query = $pdo->prepare("SELECT * FROM usuarios WHERE (email = :usuario OR cpf = :usuario) AND senha = :senha");
$query->bindValue("usuario", $usuario);
$query->bindValue("senha", $senha);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($res) > 0) {
    $nivel = $res[0]['nivel'];
    $_SESSION['id_usuario'] = $res[0]['id']; 
    $_SESSION['nome_usuario'] = $res[0]['nome']; 
    $_SESSION['cpf_usuario'] = $res[0]['cpf']; 
    $_SESSION['cpf_usuario'] = $res[0]['cpf']; 
    $_SESSION['nivel_usuario'] = $res[0]['nivel']; 
    
    if ($nivel == 'Administrador') {
        header("Location: painel-adm");
        exit();
    } 
    
    if($nivel == 'Tesoureiro'){
        header("Location: painel-finan");
        exit();
    }

    else {
        echo "<script>alert('Dados incorretos!'); window.location='../pdv';</script>";
        exit();
    }
} else {
    echo "<script>alert('Usuário ou senha inválidos!'); window.location='../pdv';</script>";
    exit();
}
?>