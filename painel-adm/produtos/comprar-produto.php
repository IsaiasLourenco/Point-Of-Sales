<?php
    @session_start();
    require_once("../../conexao.php");
    
    $id_usuario = $_SESSION['id_usuario'];
    $id = $_POST['id-comprar'];
    $quantidade = $_POST['quantidade'];
    $valor_compra = $_POST['valor_compra'];
    $valor_compra = str_replace(',', '.', $valor_compra);
    $fornecedor = $_POST['fornecedor'];
    
    if ($quantidade == 0) {
        echo 'A compra precisa ter pelo menos <strong>001</strong> produto em quantidade!';
        exit();
    }

    $total = $quantidade * $valor_compra;

    //ATUALIZAR ESTOQUE
    $query_qtde = $pdo->query("SELECT * FROM produtos WHERE id = '$id'");
    $res_qtde = $query_qtde->fetchAll(PDO::FETCH_ASSOC);
    $estoque = $res_qtde[0]['estoque'];
    $quantidade_atual =  $quantidade + $estoque;

    $res = $pdo->prepare("UPDATE produtos SET estoque = :estoque, valor_compra = :valor_compra, fornecedor = :fornecedor  WHERE id = :id    ");
    $res->bindValue(":id", "$id");
    $res->bindValue(":estoque", "$quantidade_atual");
    $res->bindValue(":valor_compra", "$valor_compra");
    $res->bindValue(":fornecedor", "$fornecedor");
    $res->execute();

    $res_compra = $pdo->prepare("INSERT INTO compras SET total = :total, data_compra = curDate(), usuario = :usuario, fornecedor = :fornecedor, pago = 'Não', quantidade = :quantidade, produto = :produto");
    $res_compra->bindValue(":total", "$total");
    $res_compra->bindValue(":usuario", "$id_usuario");
    $res_compra->bindValue(":fornecedor", "$fornecedor");
    $res_compra->bindValue(":quantidade", "$quantidade");
    $res_compra->bindValue(":produto", "$id");
    $res_compra->execute();

    echo 'Salvo com Sucesso!';
?>