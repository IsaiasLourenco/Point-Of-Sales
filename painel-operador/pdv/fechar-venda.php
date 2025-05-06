<?php
@session_start();
require_once("../../conexao.php");

$forma_pgto = $_POST['forma_pgto_input'] ?? '0';
$desconto = str_replace("R$", "", $_POST['desconto_input'] ?? '0.00');
$valor_recebido = str_replace("R$", "", $_POST['recebido_input'] ?? '0.00');
$troco = isset($_POST['troco_input']) ? str_replace(["R$", ","], ["", "."], trim($_POST['troco_input'])) : '0.00';

$id_usuario = $_SESSION['id_usuario'];
$query_abertura = $pdo->query("SELECT * FROM caixa WHERE operador = '$id_usuario' AND status_caixa = 'Aberto'");
$res_abertura = $query_abertura->fetchAll(PDO::FETCH_ASSOC);
$id_abertura = $res_abertura[0]['id'];

@$quantidade = $_POST['quantidade'];

if ($forma_pgto == '2') {
}

$imagem = "sem-foto.jpg";
$query_total = $pdo->query("SELECT SUM(valor_total) as total FROM   itens_venda 
                                                                        WHERE 
                                                                        usuario = '$id_usuario' 
                                                                        AND 
                                                                        venda = 0");
$res_total = $query_total->fetchAll(PDO::FETCH_ASSOC);
$total_compra = $res_total[0]['total'] ?? 0;
if ($total_compra <= 0) {
    echo "Erro: Nenhum item foi adicionado à venda.";
    exit();
}

$query_total = $pdo->query("SELECT SUM(valor_total) as total FROM itens_venda WHERE usuario = '$id_usuario' AND venda = 0");
$res_total = $query_total->fetchAll(PDO::FETCH_ASSOC);
$total_compra = $res_total[0]['total'] ?? 0;

if ($total_compra <= 0) {
    echo "Erro: Total da compra é inválido.";
    exit();
}

try {
    $query = $pdo->prepare("INSERT INTO vendas SET valor = :valor, 
                                                    data_venda = curDate(), 
                                                    hora = curTime(), 
                                                    operador = :operador, 
                                                    valor_recebido = :valor_recebido, 
                                                    desconto = :desconto, 
                                                    troco = :troco, 
                                                    forma_pgto = :forma_pgto, 
                                                    abertura = :abertura,
                                                    status_venda = 'Fechada'");
    $query->bindValue(":valor", $total_compra);
    $query->bindValue(":operador", $id_usuario);
    $query->bindValue(":valor_recebido", $valor_recebido);
    $query->bindValue(":desconto", $desconto);
    $query->bindValue(":troco", $troco);
    $query->bindValue(":forma_pgto", $forma_pgto);
    $query->bindValue(":abertura", $id_abertura);
    $query->execute();
    $id_venda = $pdo->lastInsertId();

    $query = $pdo->prepare("UPDATE itens_venda SET venda = :venda WHERE usuario = :usuario AND venda = 0");
    $query->bindValue(":venda", $id_venda);
    $query->bindValue(":usuario", $id_usuario);
    $query->execute();

    $query_mov = $pdo->prepare("INSERT INTO movimentacoes SET tipo = 'Entrada', 
                                                                descricao = 'Venda PDV', 
                                                                valor = :valor, 
                                                                usuario = :usuario, 
                                                                data_mov = curDate(), 
                                                                id_mov = :id_mov");
    $query_mov->bindValue(":valor", $total_compra);
    $query_mov->bindValue(":usuario", $id_usuario);
    $query_mov->bindValue(":id_mov", $id_venda);
    $query_mov->execute();

    echo "Venda finalizada com sucesso!";
    // header("Location: ../rel/comprovante_class.php?id=$id_venda");
    exit();
} catch (Exception $e) {
    echo "Erro ao processar a venda: " . $e->getMessage();
    exit();
}
