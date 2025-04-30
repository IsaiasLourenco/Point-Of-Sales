<?php 
require_once("../../conexao.php");

$id = $_POST['id'];
$gerente = $_POST['gerente'];
$senha_gerente = $_POST['senha_gerente'];

// VERIFICAR CREDENCIAIS DO GERENTE
$query_con = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id_gerente AND senha = :senha_gerente");
$query_con->bindValue(":id_gerente", $gerente);
$query_con->bindValue(":senha_gerente", $senha_gerente);
$query_con->execute();
$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);

if (@count($res_con) == 0) {
    echo 'A senha do Gerente está Incorreta, <strong>NÃO</strong> foi possível excluir o item!';
    exit();
}

// RECUPERAR O PRODUTO DO ITEM DESTA VENDA
$query_con = $pdo->query("SELECT * FROM itens_venda WHERE id = '$id'");
$res = $query_con->fetchAll(PDO::FETCH_ASSOC);

if (@count($res) > 0) {
    $id_prod = $res[0]['produto'];
    $quantidade = $res[0]['quantidade'];

    // DEVOLVER OS PRODUTOS AO ESTOQUE
    $query_con = $pdo->query("SELECT * FROM produtos WHERE id = '$id_prod'");
    $res = $query_con->fetchAll(PDO::FETCH_ASSOC);

    if (@count($res) > 0) {
        $estoque = $res[0]['estoque'];
        $novo_estoque = $estoque + $quantidade;

        $res = $pdo->prepare("UPDATE produtos SET estoque = :estoque WHERE id = '$id_prod'");
        $res->bindValue(":estoque", $novo_estoque);
        $res->execute();
    }
}

// EXCLUIR O ITEM DA VENDA
$query_con = $pdo->query("DELETE FROM itens_venda WHERE id = '$id'");

echo 'Excluído com Sucesso!';
 ?>