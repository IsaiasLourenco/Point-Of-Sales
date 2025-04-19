<?php
@session_start();
require_once('../../conexao.php');

$id = $_POST['id'];
$id_usuario = $_SESSION['id_usuario'];

$query = $pdo->query("UPDATE contas_receber SET pago = 'Sim', 
                                                data_pgto = curDate(),
                                                usuario = '$id_usuario'
                                                WHERE 
                                                id = '$id'");

                                             

$query_valor = $pdo->query("SELECT * FROM   contas_receber 
                                            WHERE 
                                            id = '$id'");
$res = $query_valor->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
    $descricao = $res[0]['descricao'];
    $valor = $res[0]['valor'];

    $query_mov = $pdo->query("INSERT INTO movimentacoes SET tipo = 'Entrada', 
                                                            descricao = '$descricao', 
                                                            valor = '$valor',
                                                            usuario = '$id_usuario',
                                                            data_mov = curDate(),
                                                            id_mov = '$id'");

$query_venda = $pdo->query("UPDATE vendas SET   valor_recebido = '$valor', 
                                                status_venda = 'Fechada'");                                                               
}

echo 'Acerto efetivamente Recebido!!';
