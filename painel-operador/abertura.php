<?php 
@session_start();
    require_once('../conexao.php');

    $caixa = $_POST['caixa'];
    $gerente = $_POST['gerente'];
    $valor_ab = $_POST['valor-ab'];
    $valor_ab = str_replace(',', '.', $valor_ab);
    $senha = $_POST['senha'];
    $id_usuario = $_SESSION['id_usuario'];

    //VERIFICAR A SENHA DO GERENTE
    

    //VERIFICAR SE CAIXA JÁ ESTÁ ABERTO
    $query = $pdo->prepare("SELECT * FROM caixa WHERE caixa = '$caixa' AND status_caixa = 'Aberto'");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    if (@count($res) > 0) {
        $id_gerente = $res[0]['gerente_ab'];
        $id_operador = $res[0]['operador'];
        $query2 = $pdo->query("SELECT * FROM usuarios WHERE id = '$id_gerente'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $nome_gerente = $res2[0]['nome'];
        $query3 = $pdo->query("SELECT * FROM usuarios WHERE id = '$id_operador'");
        $res3 = $query3->fetchAll(PDO::FETCH_ASSOC);
        $nome_operador = $res2[0]['nome'];
        echo 'Este caixa já está aberto!!! Foi aberto pelo gerente ' .$nome_gerente. ' e o operador dele é ' .$nome_operador. '!';
        exit();
    }

    $res = $pdo->prepare("INSERT INTO caixa SET data_ab = curDate(), hora_ab = curTime(), valor_ab = :valor_ab, gerente_ab = :gerente_ab, caixa = :caixa, operador = '$id_usuario', status_caixa = 'Aberto' ");
    $res->bindValue(":valor_ab", $valor_ab);
    $res->bindValue(":gerente_ab", $gerente);
    $res->bindValue(":caixa", $caixa);
    $res->execute();
?>