<?php 
    require_once('../../conexao.php');

    $id = $_POST['id'];

    $query_prod = $pdo->query(("SELECT * FROM produtos WHERE categoria = '$id'"));
    $res = $query_prod->fetchAll(PDO::FETCH_ASSOC);
    if ($res) {
        echo "Existem produtos relacionados a essa categoria. Primeiramente exclua esses produtos, depois exclua essa categoria!";
        exit();
    }

    $query = $pdo->query("DELETE FROM categorias WHERE id = '$id'");
    echo 'Excluído com Sucesso!';
?>