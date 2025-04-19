<?php 
    require_once('../../conexao.php');

    $id = $_POST['id'];

    $query = $pdo->query("SELECT * FROM compras WHERE id = '$id'");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if ($total_reg > 0) {
        $pago = $res[0]['pago'];
        if ($pago == 'Sim') {
            echo 'Esta conta já está faturada, você <strong>NÃO</strong> pode excluí-la!!';
            exit();
        }
    }

    $query = $pdo->query("DELETE FROM contas_pagar WHERE id_compra = '$id'");
    
    $query = $pdo->query("DELETE FROM compras WHERE id = '$id'");
    echo 'Excluído com Sucesso!';

?>