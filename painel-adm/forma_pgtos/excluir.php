<?php 
    require_once('../../conexao.php');

    $id = $_POST['id'];

    $query = $pdo->query("DELETE FROM forma_pgtos WHERE id = '$id'");
    echo 'Excluído com Sucesso!';
?>