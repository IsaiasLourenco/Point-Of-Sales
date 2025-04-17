<?php 
    require_once('../../conexao.php');

    $id = $_POST['id'];

    $query_del = $pdo->query(("SELECT * FROM contas_pagar WHERE id = '$id'"));
    $res = $query_del->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if ($total_reg > 0) {
        $pago = $res[0]['pago'];
        $descricao = $res[0]['descricao'];
        if ($pago == 'Sim') {
            echo 'Essa conta já está paga, você <strong>NÃO</strong> pode excluí-la!';
            exit();
        }
        if ($descricao == 'Compra de Produtos para o Estoque') {
            echo 'Conta vinda das compras para o Estoque. Você <strong>NÃO</strong> tem privilégios de exclusão!!';
            exit();
        }
    }

    $query = $pdo->query("DELETE FROM contas_pagar WHERE id = '$id'");
    echo 'Excluído com Sucesso!';
?>