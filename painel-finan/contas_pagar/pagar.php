<?php 
    require_once('../../conexao.php');

    $id = $_POST['id-pagar'];

    $query = $pdo->query("UPDATE contas_pagar SET pago = 'Sim', data_pgto = curDate() WHERE id = '$id'");

    //VERIFICAR SE É COMPRA DE PRODUTOS PARA ESTOQUE
    $query_select = $pdo->query("SELECT * FROM contas_pagar WHERE id = '$id'");
    $res = $query_select->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if ($total_reg > 0) {
        $descricao = $res[0]['descricao'];
        $id_compra = $res[0]['id_compra'];
        if ($descricao = 'Compra de Produtos para o Estoque') {
            $query_compra = $pdo->query("UPDATE compras SET pago = 'Sim' WHERE id = '$id_compra'");
        }

    }

    echo 'Conta efetivamente Paga!';
?>