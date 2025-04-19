<?php 
    require_once('../../conexao.php');

    $id = $_POST['id'];

    $query_del = $pdo->query(("SELECT * FROM contas_receber WHERE id = '$id'"));
    $res = $query_del->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if ($total_reg > 0) {
        $pago = $res[0]['pago'];
        if ($pago == 'Sim') {
            echo 'Essa conta já está paga, você <strong>NÃO</strong> pode excluí-la!';
            exit();
        }
    }

    $query = $pdo->query("DELETE FROM contas_receber WHERE id = '$id'");
    echo 'Excluído com Sucesso!';
?>