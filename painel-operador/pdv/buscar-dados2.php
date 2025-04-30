<?php
@session_start();
require_once("../../conexao.php");


$id_usuario = $_SESSION['id_usuario'];
$query_abertura = $pdo->query("SELECT * FROM caixa WHERE operador = '$id_usuario' AND status_caixa = 'Aberto'");
$res_abertura = $query_abertura->fetchAll(PDO::FETCH_ASSOC);
$id_abertura = $res_abertura[0]['id'];
$nome = "PRODUTO NÃO ENCONTRADO!!!";
$descricao = "PRODUTO NÃO ENCONTRADO!!!";
$valor = "NÃO ENCONTRADO!!!";
$quantidade = $_POST['quantidade'];
$estoque = "NÃO ENCONTRADO!!!";
$imagem = "sem-foto.jpg";
$valor_total = "NÃO ENCONTRADO!!!";
$codigo = $_POST['codigo'];
$total_compra = 0;
$query = $pdo->query("SELECT * FROM produtos WHERE codigo = '$codigo'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($res) > 0) {
    $id_produto = $res[0]['id'];
    $nome = $res[0]['nome'];
    $descricao = $res[0]['descricao'];
    $valor = $res[0]['valor_venda'];
    $estoque = $res[0]['estoque'];
    $imagem = $res[0]['imagem'];
    if ($estoque < $quantidade) {
        echo "Estoque insuficiente! $nome tem apenas $estoque unidades disponíveis.";
        exit;
    }
    $valor_total = $quantidade * $valor;
    $query_iten = $pdo->prepare("INSERT INTO itens_venda SET    produto = :produto, 
                                                                valor_unitario = :valor_unitario, 
                                                                quantidade = :quantidade, 
                                                                valor_total = :valor_total, 
                                                                usuario = :usuario, venda = '0', 
                                                                data_venda = curDate()");
    $query_iten->bindValue(":produto", $id_produto);
    $query_iten->bindValue(":valor_unitario", $valor);
    $query_iten->bindValue(":quantidade", $quantidade);
    $query_iten->bindValue(":valor_total", $valor_total);
    $query_iten->bindValue(":usuario", $id_usuario);
    $query_iten->execute();
    $query_est = $pdo->query("SELECT * FROM produtos WHERE id = '$id_produto'");
    $res_est = $query_est->fetchAll(PDO::FETCH_ASSOC);
    if (@count($res_est) > 0) {
        $estoque = $res_est[0]['estoque'];
        $estoque = $estoque - $quantidade;
        $res_est = $pdo->prepare("UPDATE produtos SET   estoque = :estoque 
                                                        WHERE 
                                                        id = '$id_produto'");
        $res_est->bindValue(":estoque", $estoque);
        $res_est->execute();
    }
    $query_total = $pdo->query("SELECT SUM(valor_total) as total FROM   itens_venda 
                                                                        WHERE 
                                                                        usuario = '$id_usuario' 
                                                                        AND 
                                                                        venda = 0");
    $res_total = $query_total->fetchAll(PDO::FETCH_ASSOC);
    $total_compra = $res_total[0]['total'];
    
}
$dados =    $nome . '&-/z' .
    $descricao . '&-/z' .
    $valor . '&-/z' .
    $estoque . '&-/z' .
    $imagem . '&-/z' .
    $valor_total . '&-/z' .
    number_format($total_compra, 2, ',', '.');
echo $dados;
