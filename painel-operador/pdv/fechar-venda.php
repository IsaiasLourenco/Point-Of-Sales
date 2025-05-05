<?php
@session_start();
require_once("../../conexao.php");

// Garante que os valores nunca sejam NULL
$forma_pgto = $_POST['forma_pgto'] ?? '0';
$desconto = str_replace("R$", "", $_POST['desconto'] ?? '0.00');
$valor_recebido = str_replace("R$", "", $_POST['valor_recebido'] ?? '0.00');
$troco = isset($_POST['troco_input']) ? str_replace(["R$", ","], ["", "."], trim($_POST['troco_input'])) : '0.00';

error_log("Dados recebidos no POST: " . print_r($_POST, true));


$id_usuario = $_SESSION['id_usuario'];
$query_abertura = $pdo->query("SELECT * FROM caixa WHERE operador = '$id_usuario' AND status_caixa = 'Aberto'");
$res_abertura = $query_abertura->fetchAll(PDO::FETCH_ASSOC);
$id_abertura = $res_abertura[0]['id'];

@$quantidade = $_POST['quantidade'];

$imagem = "sem-foto.jpg";
error_log("FORMA PGTO: $forma_pgto | DESCONTO: $desconto | VALOR RECEBIDO: $valor_recebido | TROCO: $troco");
$query_total = $pdo->query("SELECT SUM(valor_total) as total FROM   itens_venda 
                                                                        WHERE 
                                                                        usuario = '$id_usuario' 
                                                                        AND 
                                                                        venda = 0");
$res_total = $query_total->fetchAll(PDO::FETCH_ASSOC);
$total_compra = $res_total[0]['total'] ?? 0;
// Validar se há itens antes de fechar a venda
if ($total_compra <= 0) {
    echo "Erro: Nenhum item foi adicionado à venda.";
    exit();
}

error_log("POST recebido: " . print_r($_POST, true)); // Confirma o que está chegando do frontend



error_log("Troco final após processamento: " . $troco);

error_log("Capturado no backend -> FORMA PGTO: $forma_pgto | DESCONTO: $desconto | VALOR RECEBIDO: $valor_recebido | TROCO: $troco");


// **4. Calcular total_compra no backend**
$query_total = $pdo->query("SELECT SUM(valor_total) as total FROM itens_venda WHERE usuario = '$id_usuario' AND venda = 0");
$res_total = $query_total->fetchAll(PDO::FETCH_ASSOC);
$total_compra = $res_total[0]['total'] ?? 0;

// **5. Validar total_compra antes de continuar**
if ($total_compra <= 0) {
    echo "Erro: Total da compra é inválido.";
    exit();
}
error_log("Debug: Forma de pagamento = $forma_pgto"); 


try {
    // **6. Registrar Venda na Tabela `vendas`**
    $query = $pdo->prepare("INSERT INTO vendas SET valor = :valor, 
                                                    data_venda = curDate(), 
                                                    hora = curTime(), 
                                                    operador = :operador, 
                                                    valor_recebido = :valor_recebido, 
                                                    desconto = :desconto, 
                                                    troco = :troco, 
                                                    forma_pgto = :forma_pgto, 
                                                    status_venda = 'Fechada'");
    $query->bindValue(":valor", $total_compra);
    $query->bindValue(":operador", $id_usuario);
    $query->bindValue(":valor_recebido", $valor_recebido);
    $query->bindValue(":desconto", $desconto);
    $query->bindValue(":troco", $troco);
    $query->bindValue(":forma_pgto", $forma_pgto);
    $query->execute();
    $id_venda = $pdo->lastInsertId();

    // **7. Atualizar Itens na Tabela `itens_venda`**
    $query = $pdo->prepare("UPDATE itens_venda SET venda = :venda WHERE usuario = :usuario AND venda = 0");
    $query->bindValue(":venda", $id_venda);
    $query->bindValue(":usuario", $id_usuario);
    $query->execute();

    // **8. Inserir Registro na Tabela `movimentacoes`**
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

    // **9. Retornar Sucesso**
    echo "Venda finalizada com sucesso!";
    echo "Ver banco de dados";
} catch (Exception $e) {
    // Registrar Erro para Depuração
    error_log("Erro ao processar a venda: " . $e->getMessage());
    echo "Erro ao processar a venda: " . $e->getMessage();
    exit();
}
?>
