<?php 
    require_once('../../conexao.php');

    $id = $_POST['id'];

    $query = $pdo->query("DELETE FROM caixas WHERE id = '$id'");
    echo 'Excluído com Sucesso!';
?>