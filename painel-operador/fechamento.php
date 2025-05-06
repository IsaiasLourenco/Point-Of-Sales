<?php 
@session_start();
require_once("../conexao.php");

$id_usuario = $_SESSION['id_usuario'];
$caixa = $_POST['caixa_fechamento'];
$gerente = $_POST['gerente_fechamento'];
$valor_fechamento = $_POST['valor_fechamento'];
$valor_fechamento = str_replace(',', '.', $valor_fechamento);
$senha_gerente = $_POST['senha_gerente_fechamento'];

$query = $pdo->query("SELECT * FROM caixa WHERE operador = '$id_usuario' AND status_caixa = 'Aberto'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_abertura = $res[0]['valor_ab'] ;
$id_abertura = $res[0]['id'] ;

$query_venda = $pdo->query("SELECT SUM(valor) AS total_vendas FROM vendas WHERE operador = '$id_usuario' AND abertura = '$id_abertura'");
$res_venda = $query_venda->fetch(PDO::FETCH_ASSOC);
$valor_vendido = $res_venda['total_vendas'] ?? 0;   
$valor_quebra = $valor_fechamento - ($valor_abertura + $valor_vendido);
//VERIFICAR A SENHA DO GERENTE
$query_con = $pdo->prepare("SELECT * from usuarios WHERE id = :id_gerente and senha = :senha_gerente ");
$query_con->bindValue(":id_gerente", $gerente);
$query_con->bindValue(":senha_gerente", $senha_gerente);
$query_con->execute();
$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
if(@count($res_con) == 0){
		echo 'A senha do Gerente está Incorreta. <strong>Não</strong> foi possivel fechar o caixa!';
	exit();
	}
    

$res = $pdo->prepare("UPDATE caixa SET  data_fec = curDate(), 
                                        hora_fec = curTime(), 
                                        valor_fec = :valor_fec, 
                                        valor_vendido = :valor_vendido,
                                        valor_quebra = :valor_quebra,
                                        gerente_fec = :gerente_fec, 
                                        status_caixa = 'Fechado'
                                        WHERE 
                                        id = :id_abertura
                                        AND
                                        status_caixa = 'Aberto'");
$res->bindValue(":id_abertura", $id_abertura);
$res->bindValue(":valor_fec", $valor_fechamento);
$res->bindValue(":valor_vendido", $valor_vendido);
$res->bindValue(":valor_quebra", $valor_quebra);
$res->bindValue(":gerente_fec", $gerente);
$res->execute();

echo "Fechado com Sucesso!";

?>