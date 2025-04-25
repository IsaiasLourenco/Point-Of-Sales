<?php
@session_start();
require_once("../../conexao.php");

$id_usuario = $_SESSION['id_usuario'];

echo '<ul class="order-list">';

$total_venda = 0;
$query = $pdo->query("SELECT * FROM itens_venda WHERE usuario = '$id_usuario' AND venda = 0 ORDER BY id DESC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res); 
if ($total_reg > 0) {
    for ($i = 0; $i < $total_reg; $i++) {
        foreach ($res[$i] as $key => $value) {}
        $id_item = $res[$i]['id'];
        $id_produto = $res[$i]['produto'];
        $valor_unitario = number_format($res[$i]['valor_unitario'], 2, ',', '.');
        $quantidade = $res[$i]['quantidade'];
        $valor_total = $res[$i]['valor_total'];
        
        $total_venda += floatval($valor_total);
        $valor_total = number_format($valor_total, 2, ',', '.');

        $query_prod = $pdo->query("SELECT * FROM produtos WHERE id = '$id_produto'");
        $res_prod = $query_prod->fetchAll(PDO::FETCH_ASSOC);
        $nome_produto = $res_prod[0]['nome'];
        $imagem_produto = $res_prod[0]['imagem'];

        echo '<li class="mb-1" style="display: flex; align-items: center; padding: 10px 0; line-height: 1.8;">
        <img src="../assets/img/produtos/'.$imagem_produto.'" style="margin-left: 40px;">
        <div style="margin-left: 10px;">
            <h4>'.$quantidade.' - '.mb_strtoupper($nome_produto). ' 
                <a href="#" onclick="modalExcluir('.$id_item.')" title="Excluir Item" style="text-decoration: none">
                    <i class="bi bi-trash text-danger mx-1"></i>
                </a>
            </h4>
            <h5 class="item-total" style="color:black;">R$ '.$valor_total.'</h5>
        </div>
    </li>';
    }
    $total_venda = number_format($total_venda, 2, ',', '.');
    echo '</ul>';
    echo '<h4 class="total mt-4">Total de Itens ('.$total_reg.')</h4>';
    echo '<h1 style="color: red;">R$ <span id="sub_total">'.$total_venda.'</span></h1>';
}
?>