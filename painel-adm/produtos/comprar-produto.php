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
$prazo = $res_qtde[0]['prazo']; // PARA CALCULAR DATA DO VENCIMENTO
$quantidade_atual =  $quantidade + $estoque;

$res = $pdo->prepare("UPDATE produtos SET   estoque = :estoque, 
                                            valor_compra = :valor_compra, 
                                            fornecedor = :fornecedor  
                                            WHERE 
                                            id = :id ");
$res->bindValue(":id", "$id");
$res->bindValue(":estoque", "$quantidade_atual");
$res->bindValue(":valor_compra", "$valor_compra");
$res->bindValue(":fornecedor", "$fornecedor");
$res->execute();

$res_compra = $pdo->prepare("INSERT INTO compras SET    total = :total, 
                                                        data_compra = curDate(), 
                                                        usuario = :usuario, 
                                                        fornecedor = :fornecedor, 
                                                        pago = 'Não', 
                                                        quantidade = :quantidade, 
                                                        produto = :produto");
$res_compra->bindValue(":total", "$total");
$res_compra->bindValue(":usuario", "$id_usuario");
$res_compra->bindValue(":fornecedor", "$fornecedor");
$res_compra->bindValue(":quantidade", "$quantidade");
$res_compra->bindValue(":produto", "$id");
$res_compra->execute();

//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = preg_replace('/[ -]+/', '-', @$_FILES['imagem']['name']);
$caminho = '../../assets/img/contas_pagar/' . $nome_img;
if (@$_FILES['imagem']['name'] == "") {
    $imagem = "compraProdutos.jpeg";
} else {
    $imagem = $nome_img;
}

$imagem_temp = @$_FILES['imagem']['tmp_name'];
$ext = pathinfo($imagem, PATHINFO_EXTENSION);
if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'pdf') {
    move_uploaded_file($imagem_temp, $caminho);
} else {
    echo 'Extensão de Imagem não permitida!';
    exit();
}

    $res_compra = $pdo->prepare("INSERT INTO contas_pagar SET   descricao = 'Compra de Produtos para o Estoque', 
                                                                valor = :valor,     
                                                                data_conta = curDate(), 
                                                                vencimento = DATE_ADD(CURDATE(), INTERVAL $prazo DAY), 
                                                                usuario = :usuario, 
                                                                pago = 'Não', 
                                                                arquivo = :imagem ");
    $res_compra->bindValue(":valor", "$total");
    $res_compra->bindValue(":usuario", "$id_usuario");
    $res_compra->bindValue(":imagem", $imagem);
    $res_compra->execute();

echo 'Salvo com Sucesso!';
