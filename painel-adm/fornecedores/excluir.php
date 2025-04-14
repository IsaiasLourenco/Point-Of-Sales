<?php 
    require_once('../../conexao.php');

    $id = $_POST['id'];

    $query_prod = $pdo->query(("SELECT * FROM produtos WHERE fornecedor = '$id'"));
    $res = $query_prod->fetchAll(PDO::FETCH_ASSOC);
    if ($res) {
        echo "Existem produtos relacionados a esse fornecedor. Primeiramente exclua esses produtos, depois exclua esse fornecedor!";
        exit();
    }

    $query = $pdo->query("DELETE FROM fornecedores WHERE id = '$id'");
    echo 'Excluído com Sucesso!';
?>