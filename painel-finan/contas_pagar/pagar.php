<?php
@session_start();
require_once('../../conexao.php');

$id = $_POST['id-pagar'];
$id_usuario = $_SESSION['id_usuario'];

$query = $pdo->query("UPDATE contas_pagar SET   pago = 'Sim', 
                                                    data_pgto = curDate() 
                                                    WHERE 
                                                    id = '$id'");

//VERIFICAR SE É COMPRA DE PRODUTOS PARA ESTOQUE
$query_select = $pdo->query("SELECT * FROM contas_pagar WHERE id = '$id'");
$res = $query_select->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
    $descricao = $res[0]['descricao'];
    $id_compra = $res[0]['id_compra'];
    $valor = $res[0]['valor'];
    if ($descricao == 'Compra de Produtos para o Estoque') {
        $query_compra = $pdo->query("UPDATE compras SET pago = 'Sim' WHERE id = '$id_compra'");

        $query_mov = $pdo->query("INSERT INTO movimentacoes SET tipo = 'Saída', 
                                                                descricao = '$descricao',
                                                                valor = '$valor',
                                                                usuario = '$id_usuario',
                                                                data_mov = curDate(), 
                                                                id_mov = '$id'");
    }
    $query_mov = $pdo->query("INSERT INTO movimentacoes SET tipo = 'Saída', 
    descricao = '$descricao',
    valor = '$valor',
    usuario = '$id_usuario',
    data_mov = curDate(), 
    id_mov = '$id'");
}

echo 'Conta efetivamente Paga!';
