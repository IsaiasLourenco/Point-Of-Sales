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
    $_SESSION['nome_usuario'] = $res[0]['nome']; 
    $_SESSION['nivel_usuario'] = $res[0]['nivel']; 
    $_SESSION['cpf_usuario'] = $res[0]['cpf']; 
    
    if ($nivel == 'Administrador') {
        header("Location: painel-adm");
        exit();
    } else {
        echo "<script>alert('Dados incorretos!'); window.location='../pdv';</script>";
        exit();
    }
} else {
    echo "<script>alert('Usuário ou senha inválidos!'); window.location='../pdv';</script>";
    exit();
}
?>