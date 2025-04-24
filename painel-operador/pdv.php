<?php
@session_start();
require_once('../conexao.php');
require_once('verificar-permissao.php');

$id_usuario = $_SESSION['id_usuario'];

$pagina = 'pdv';

//VERIFICAR SE O CAIXA ESTÁ ABERTO
$query = $pdo->query("SELECT * FROM caixa WHERE operador = '$id_usuario' AND status_caixa = 'Aberto' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg == 0) {
    echo "<script language='javascript'>window.location='index.php'</script>";
} else {
    $id_caixa = $res[0]['id'];
    $id_operador = $res[0]['operador'];
    $id_caixa = str_pad($id_caixa, 3, "0", STR_PAD_LEFT);

    $query_op = $pdo->query("SELECT * FROM usuarios WHERE id = '$id_operador'");
    $res_op = $query_op->fetchAll(PDO::FETCH_ASSOC);
    $total_reg_op = @count($res_op);
    if ($total_reg_op > 0) {
        $nome_operador = $res_op[0]['nome'];
    }
}

if ($desconto_porcentagem == 'Sim') {
    $desc = '%';
} else {
    $desc = 'R$';
}

?>

<!DOCTYPE html>
<html class="wide wow-animation" lang="pt-br">

<head>
    <title><?php echo $nome_sistema ?></title>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="../assets/css/telapdv.css">
    <link rel="shortcut icon" href="../assets/img/ico.ico" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>

<body>

    <div class='checkout'>

        <div class="row">
            <div class="col-md-5 col-sm-12">
                <div class='order py-2'>
                    <p class="background">
                        Caixa <strong><?php echo $id_caixa ?></strong> aberto. Operado por <strong><?php echo $nome_operador ?></strong>
                        &nbsp;&nbsp;
                        <a href="index.php" title="Voltar para a Home">
                            <i class="bi bi-house"></i>
                        </a>
                    </p>
                    <p class="background">LISTA DE PRODUTOS</p>
                    <span id="listar">
                    </span>
                </div>
            </div>

            <div id='payment' class='payment col-md-7'>
                <form method="post" id="form-buscar">
                    <div class="row py-2">
                        <div class="col-md-7">

                            <p class="background">CÓDIGO DE BARRAS</p>
                            <input type="text" class="form-control form-control-lg" id="codigo" name="codigo" placeholder="Código de Barras">

                            <p class="background mt-3">PRODUTO</p>
                            <input type="text" class="form-control  form-control-md" id="produto" name="produto" placeholder="Produto">

                            <p class="background mt-3">DESCRIÇÃO</p>
                            <input type="text" class="form-control  form-control-md" id="descricao" name="descricao" placeholder="Descrição do Produto">

                            <div class="row">
                                <div class="col-6">
                                    <p class="background mt-3">QUANTIDADE</p>
                                    <input type="text" class="form-control  form-control-md" id="quantidade" name="quantidade" placeholder="Quantidade">

                                    <p class="background mt-1">VALOR UNITÁRIO</p>
                                    <input type="text" class="form-control  form-control-md" id="valor_unitario" name="valor_unitario" placeholder="Valor">

                                    <p class="background mt-1">ESTOQUE</p>
                                    <input type="text" class="form-control  form-control-md" id="estoque" name="estoque" placeholder="Estoque">
                                </div>

                                <div class="col-6 mt-4">
                                    <img id="imagem" src="../assets/img/produtos/sem-foto.jpg" width="100%">
                                </div>
                            </div>

                        </div>

                        <div class="col-md-5">

                            <p class="background">TOTAL DO ITEM</p>
                            <input type="text" class="form-control form-control-md" id="total_item" name="total_item" placeholder="Código de Barras">

                            <p class="background mt-3">SUB TOTAL</p>
                            <input type="text" class="form-control  form-control-md" id="sub_total_item" name="sub_total" placeholder="Sub Total">

                            <p class="background mt-3">DESCONTO EM <?php echo $desc ?></p>
                            <input type="text" class="form-control  form-control-md" id="desconto" name="desconto" placeholder="Desconto em <?php echo $desc ?>">

                            <p class="background mt-3">TOTAL COMPRA</p>
                            <input type="text" class="form-control  form-control-md" id="total_compra" name="total_compra" placeholder="Total da Compra" required="">

                            <p class="background mt-3">VALOR RECEBIDO</p>
                            <input type="text" class="form-control  form-control-md" id="valor_recebido" name="valor_recebido" placeholder="R$ 0,00">

                            <p class="background mt-3">TROCO</p>
                            <input type="text" class="form-control  form-control-md" id="troco" name="valor_troco" placeholder="Valor Troco">

                            <input type="hidden" name="forma_pgto_input" id="forma_pgto_input">

                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>

</body>

</html>

<!-- MODAL DELETAR ITEM -->
<div class="modal fade" tabindex="-1" id="modalDeletar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Excluir Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="form-excluir">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Gerente</label>
                        <select class="form-select mt-1" aria-label="Default select example" name="gerente">
                            <?php
                            $query = $pdo->query("SELECT * from usuarios where nivel = 'Administrador' order by nome asc");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
                            $total_reg = @count($res);
                            if ($total_reg > 0) {
                                for ($i = 0; $i < $total_reg; $i++) {
                                    foreach ($res[$i] as $key => $value) {
                                    }
                            ?>
                                    <option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>
                            <?php }
                            } else {
                                echo '<option value="">Cadastre um Gerente Administrador</option>';
                            } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Senha Gerente</label>
                        <input type="password" class="form-control" id="senha_gerente" name="senha_gerente" placeholder="Senha Gerente" required="">
                    </div>

                    <small>
                        <div style="text-align:center" class="mt-1" id="mensagem-excluir">

                        </div>
                    </small>

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-fechar" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button name="btn-excluir" id="btn-excluir" type="submit" class="btn btn-danger">Excluir</button>

                    <input name="id" type="hidden" id="id_deletar_item">

                </div>
            </form>
        </div>
    </div>
</div>
<!-- FIM MODAL DELETAR ITEM -->

<!-- MODAL FECHAR VENDA -->
<div class="modal fade" tabindex="-1" id="modalVenda">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Fechar Venda</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="form-fechar-venda">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Forma de Pagamento</label>
                        <select class="form-select mt-1" aria-label="Default select example" name="forma_pgto" id="forma_pgto">
                            <?php
                            $query = $pdo->query("SELECT * from forma_pgtos order by id asc");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
                            $total_reg = @count($res);
                            if ($total_reg > 0) {
                                for ($i = 0; $i < $total_reg; $i++) {
                                    foreach ($res[$i] as $key => $value) {
                                    }
                            ?>
                                    <option value="<?php echo $res[$i]['codigo'] ?>"><?php echo $res[$i]['nome'] ?></option>
                            <?php }
                            } else {
                                echo '<option value="">Cadastre uma Forma de Pagamento</option>';
                            } ?>
                        </select>
                    </div>

                    <small>
                        <div style="text-align: center;" class="mt-1" id="mensagem-venda">
                        </div>
                    </small>

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-fechar-venda" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button name="btn-venda" id="btn-venda" type="submit" class="btn btn-danger">Fechar Venda</button>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- FIM MODAL FECHAR VENDA -->

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
<!-- Fim Ajax para funcionar Mascaras JS -->

<!-- SCRIPT PARA CHAMAR MODAL -->
<script type="text/javascript">
    $(document).ready(function() {
        document.getElementById('codigo').focus();
        document.getElementById('quantidade').value = '001';
    });
</script>
<!-- FIM SCRIPT PARA CHAMAR MODAL -->

<!--AJAX PARA BUSCAR DADOS DO PRODUTO -->
<script type="text/javascript">
        let isProcessing = false; //Flag para evitar duplicidades no leitor
    $("#codigo").keyup(function() {
        if (!isProcessing) {
            isProcessing = true; //Ativa a Fla anti duplicidades
            buscarDados();
        }
    });
</script>
<!-- CONTINUAÇÃO -->
<script type="text/javascript">
    var pagina = "<?= $pagina ?>";

    function buscarDados() {
        $.ajax({
            url: pagina + "/buscar-dados.php",
            method: 'POST',
            data: $('#form-buscar').serialize(),
            dataType: "html",

            success: function(result) {
                var array = result.split("&-/z");
                var nome = array[0];
                var descricao = array[1];
                var valor = array[2];
                var estoque = array[3];
                var imagem = array[4];
                document.getElementById('produto').value = nome;
                document.getElementById('descricao').value = descricao;
                document.getElementById('valor_unitario').value = valor;
                document.getElementById('estoque').value = estoque;
                $('#imagem').attr('src', '../assets/img/produtos/' + imagem);

                if (nome.trim() != "PRODUTO NÃO ENCONTRADO!!!") {
                    var audio = new Audio('../assets/img/barCode.wav');
                    audio.addEventListener('canplaythrough', function() {
                        audio.play();
                    })
                }

                
                isProcessing = false; //Desativa a flag antiduplicidade após concluir a requisição do produto
            },
            error: function(){
                isProcessing = false; //Destrava a flag em caso de erro.
            }
        });
    }
</script>
<!--FIM AJAX PARA BUSCAR DADOS DO PRODUTO -->