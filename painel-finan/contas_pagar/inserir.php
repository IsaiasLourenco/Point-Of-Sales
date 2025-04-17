<?php
@session_start();
require_once("../../conexao.php");

$id_usuario = $_SESSION['id_usuario'];
$id = $_POST['id'];
$descricao = $_POST['descricao'];
$valor = $_POST['valor'];
$vencimento = $_POST['vencimento'];

$query = $pdo->query("SELECT * FROM contas_pagar WHERE id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg > 0) {
    $pago = $res[0]['pago'];
    $descricao2 = $res[0]['descricao'];
    if ($pago == "Sim") {
        echo "Esta conta já foi paga! Você <strong>NÃO</strong> pode editá-la!";
        exit();
    }

    if ($descricao2 == 'Compra de Produtos para o Estoque') {
        echo 'Conta vinda das compras para o Estoque. Você <strong>NÃO</strong> tem privilégios de exclusão!!';
        exit();
    }
}

//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = preg_replace('/[ -]+/', '-', @$_FILES['imagem']['name']);
$caminho = '../../assets/img/contas_pagar/' . $nome_img;
if (@$_FILES['imagem']['name'] == "") {
    $imagem = "sem-foto.jpg";
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

if ($id == "") {
    $res = $pdo->prepare("INSERT INTO contas_pagar SET  vencimento = :vencimento, 
                                                        pago = 'Não', 
                                                        data_conta = curDate(), 
                                                        usuario = '$id_usuario', 
                                                        descricao = :descricao, 
                                                        valor = :valor, 
                                                        arquivo = :foto");
    $res->bindValue(":vencimento", $vencimento);
    $res->bindValue(":descricao", $descricao);
    $res->bindValue(":valor", $valor);
    $res->bindValue(":foto", $imagem);
    $res->execute();
} else {

    if ($imagem != 'sem-foto.jpg') {
        $res = $pdo->prepare("UPDATE contas_pagar SET   vencimento = :vencimento, 
                                                        pago = 'Não', 
                                                        data_conta = curDate(), 
                                                        usuario = '$id_usuario', 
                                                        descricao = :descricao, 
                                                        valor = :valor, 
                                                        arquivo = :foto 
                                                        WHERE 
                                                        id = :id");
        $res->bindValue(":id", $id);
        $res->bindValue(":vencimento", $vencimento);
        $res->bindValue(":descricao", $descricao);
        $res->bindValue(":valor", $valor);
        $res->bindValue(":foto", $imagem);
        $res->execute();
    } else {
        $res = $pdo->prepare("UPDATE contas_pagar SET   vencimento = :vencimento, 
                                                        pago = 'Não', 
                                                        data_conta = curDate(), 
                                                        usuario = '$id_usuario', 
                                                        descricao = :descricao, 
                                                        valor = :valor 
                                                        WHERE 
                                                        id = :id");
        $res->bindValue(":id", $id);
        $res->bindValue(":vencimento", $vencimento);
        $res->bindValue(":descricao", $descricao);
        $res->bindValue(":valor", $valor);
        $res->execute();
    }
}

echo 'Salvo com Sucesso!';
