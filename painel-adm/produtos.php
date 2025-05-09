<?php
@session_start();
$pagina = 'produtos';
require_once('../conexao.php');
require_once('verificar-permissao.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
</head>

<body>

</body>

</html>
<h5 style="text-align: center;" class="text-secondary">PRODUTOS</h5>
<a href="index.php" title="Home"><h5 style="text-align: center;" class="text-secondary"><i class="bi bi-house-door"></i></h5></a>
<a href="index.php?pagina=<?php echo $pagina ?>&funcao=novo" type="button" class="btn btn-sm btn-secondary mt-2 mb-2">Novo Produto</a>

<div class="mt-4" style="margin-right:25px">
    <?php
    $query = $pdo->query("SELECT * FROM produtos ORDER BY id ASC");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if ($total_reg > 0) {
    ?>
        <small>
            <table id="produtos" class="table table-hover" style="width:100%; font-size: 10px;">
                <thead>
                    <tr>
                        <th style="text-align: center;">Nome</th>
                        <th style="text-align: center;">Código</th>
                        <th style="text-align: center;">Estoque</th>
                        <th style="text-align: center;">Valor Compra</th>
                        <th style="text-align: center;">Valor Venda</th>
                        <th style="text-align: center;">Fornecedor</th>
                        <th style="text-align: center;">Imagem</th>
                        <th style="text-align: center;">Ações</th>

                    </tr>
                </thead>
                <tbody>

                    <?php
                    for ($i = 0; $i < $total_reg; $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }

                        $id_cat = $res[$i]['categoria'];
                        $query_2 = $pdo->query("SELECT * from categorias where id = '$id_cat'");
                        $res_2 = $query_2->fetchAll(PDO::FETCH_ASSOC);
                        $nome_cat = $res_2[0]['nome'];


                        //BUSCAR OS DADOS DO FORNECEDOR
                        $id_forn = $res[$i]['fornecedor'];
                        $query_f = $pdo->query("SELECT * from fornecedores where id = '$id_forn'");
                        $res_f = $query_f->fetchAll(PDO::FETCH_ASSOC);
                        $total_reg_f = @count($res_f);
                        if ($total_reg_f > 0) {
                            $nome_forn = $res_f[0]['nome'];
                            $tel_forn = $res_f[0]['telefone'];
                        } else {
                            $nome_forn = '';
                            $tel_forn = '';
                        }

                    ?>

                        <tr>
                            <td style="text-align: center;"><?php echo $res[$i]['nome'] ?></td>
                            <td style="text-align: center;"><?php echo $res[$i]['codigo'] ?></td>
                            <td style="text-align: center;"><?php echo $res[$i]['estoque'] ?></td>
                            <td style="text-align: center;">R$ <?php echo number_format($res[$i]['valor_compra'], 2, ',', '.'); ?></td>
                            <td style="text-align: center;">R$ <?php echo number_format($res[$i]['valor_venda'], 2, ',', '.'); ?></td>
                            <td style="text-align: center;"><?php echo $nome_forn ?></td>
                            <td style="text-align: center;"><img src="../assets/img/produtos/<?php echo $res[$i]['imagem'] ?>" width="20"></td>
                            <td style="text-align: center;">
                                <a href="index.php?pagina=<?php echo $pagina ?>&funcao=editar&id=<?php echo $res[$i]['id'] ?>" title="Editar Registro" style="text-decoration: none">
                                    <i class="bi bi-pencil-square text-success"></i>
                                </a>

                                <a href="index.php?pagina=<?php echo $pagina ?>&funcao=deletar&id=<?php echo $res[$i]['id'] ?>" title="Excluir Registro" style="text-decoration: none">
                                    <i class="bi bi-trash text-danger mx-1"></i>
                                </a>

                                <a href="#" onclick="mostrarDados(  '<?php echo $res[$i]['descricao'] ?>',  
                                                                    '<?php echo $nome_cat ?>', 
                                                                    '<?php echo $nome_forn ?>', 
                                                                    '<?php echo $tel_forn ?> ',
                                                                    '<?php echo $res[$i]['lucro'] ?>',  
                                                                    '<?php echo $res[$i]['estoque_min'] ?>',  
                                                                    '<?php echo $res[$i]['prazo'] ?>',  
                                                                    '<?php echo $res[$i]['imagem'] ?>')"
                                    title="Dados Adicionais" style="text-decoration: none">
                                    <i class="bi bi-info-circle-fill text-primary mx-1"></i>
                                </a>


                                <a href="#" onclick="comprarProdutos('<?php echo $res[$i]['id'] ?>')" title="Comprar Produtos" style="text-decoration: none">
                                    <i class="bi bi-bag-fill text-secondary mx-1"></i>
                                </a>
                                
                                <a href="../rel/relBarras_class.php?codigo=<?php echo $res[$i]['codigo'] ?>" title="Imprimir Código de Barras" style="text-decoration: none" target="_blank">
                                    <i class="bi bi-upc-scan text-dark mx-1"></i>
                                </a>



                            </td>
                        </tr>

                    <?php } ?>

                </tbody>

            </table>
        </small>
    <?php } else {
        echo '<p>Não existem dados para serem exibidos!!';
    } ?>
</div>


<?php
if (@$_GET['funcao'] == "editar") {
    $titulo_modal = 'Editar Registro';
    $query = $pdo->query("SELECT * from produtos where id = '$_GET[id]'");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if ($total_reg > 0) {
        $nome = $res[0]['nome'];
        $codigo = $res[0]['codigo'];
        $categoria = $res[0]['categoria'];
        $fornecedor = $res[0]['fornecedor'];
        $descricao = $res[0]['descricao'];
        $estoque = $res[0]['estoque'];
        $valor_compra = $res[0]['valor_compra'];
        $valor_venda = $res[0]['valor_venda'];
        $prazo = $res[0]['prazo'];
        $imagem = $res[0]['imagem'];
    }
} else {
    $titulo_modal = 'Inserir';
}
?>

<!-- MODAL INSERÇÃO EDIÇÃO -->
<div class="modal fade" tabindex="-1" id="modalCadastrar" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $titulo_modal ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="form">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="codigo" class="form-label">Código</label>
                                <input type="number" class="form-control" id="codigo" name="codigo" placeholder="Código" required="" value="<?php echo @$codigo ?>">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required="" value="<?php echo @$nome ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="valor_venda" class="form-label">Valor Venda</label>
                                <input type="text" class="form-control" id="valor_venda" name="valor_venda" placeholder="Valor Venda" required="" value="<?php echo @$valor_venda ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição do Produto</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição" required="" value="<?php echo @$descricao ?>">
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Categoria</label>
                                <select class="form-select mt-1" aria-label="Default select example" name="categoria">
                                    <?php
                                    $query = $pdo->query("SELECT * from categorias order by nome asc");
                                    $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                    $total_reg = @count($res);
                                    if ($total_reg > 0) {
                                        for ($i = 0; $i < $total_reg; $i++) {
                                            foreach ($res[$i] as $key => $value) {
                                            }
                                    ?>
                                            <option <?php if (@$categoria == $res[$i]['id']) { ?> selected <?php } ?> value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>
                                    <?php }
                                    } else {
                                        echo '<option value="">Cadastre uma Categoria</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="prazo" class="form-label">Prazo para pagamento/compras</label>
                                <input type="number" class="form-control" id="prazo" name="prazo" placeholder="Prazo" required="" value="<?php echo @$prazo ?>">
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Imagem</label>
                            <input type="file" value="<?php echo @$imagem ?>" class="form-control-file" id="imagem" name="imagem" onChange="carregarImg();">
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div id="divImgConta" class="mt-4 mb-4">
                            <?php if (@$imagem != "") { ?>
                                <img src="../assets/img/produtos/<?php echo $imagem ?>" width="150px" id="target">
                            <?php  } else { ?>
                                <img src="../assets/img/produtos/sem-foto.jpg" width="150px" id="target">
                            <?php } ?>
                        </div>
                    </div>

                    <div id="codigoBarra"></div>


                    <small>
                        <div align="center" class="mt-1" id="mensagem">

                        </div>
                    </small>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
                    <button type="submit" class="btn btn-sm btn-secondary" name="btn-salvar" id="btn-salvar">Salvar</button>

                    <input name="id" type="hidden" value="<?php echo @$_GET['id'] ?>">
                    <input name="nome_double" type="hidden" value="<?php echo @$nome ?>">
                    <input name="codigo_double" type="hidden" value="<?php echo @$codigo ?>">


                </div>
            </form>
        </div>
    </div>
</div>
<!-- FIM MODAL INSERÇÃO EDIÇÃO -->

<!-- MODAL DELEÇÃO -->
<div class="modal fade" tabindex="-1" id="modalDeletar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $titulo_modal ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="form-excluir">
                <div class="modal-body">

                    <p>Deseja Realmente Excluir o Registro <?php echo $_GET['id'] ?>?</p>

                    <small>
                        <div align="center" class="mt-1" id="mensagem-excluir">

                        </div>
                    </small>

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-fechar-excluir" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button name="btn-excluir" id="btn-excluir" type="submit" class="btn btn-danger">Excluir</button>

                    <input name="id" type="hidden" value="<?php echo @$_GET['id'] ?>">

                </div>
            </form>
        </div>
    </div>
</div>
<!-- FIM MODAL DELEÇÃO -->

<!-- MODAL DADOS -->
<div class="modal fade" tabindex="-1" id="modalDados">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="nome-registro"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body mb-4">

                <strong>Descrição: </strong>
                <span id="descricao-registro"></span>
                <hr>
                <strong>Categoria: </strong>
                <span id="categoria-registro"></span>
                <hr>
                <div class="div-forn">
                    <strong>Fornecedor: </strong>
                    <span id="fornecedor-registro"></span>
                    <hr>
                    <strong>Telefone: </strong>
                    <span id="telefone-registro"></span>
                    <hr>
                </div>
                <strong>Lucro: </strong>
                <span id="lucro-registro"></span>
                <hr>
                <strong>Estoque Mínimo: </strong>
                <span id="estoque-min-registro"></span>
                <hr>
                <strong>Prazo Pagamento: </strong>
                <span id="prazo-registro"></span>
                <hr>
                <img id="imagem-registro" src="" class="mt-4" width="200">

            </div>

        </div>
    </div>
</div>
<!-- FIM MODAL DADOS -->

<!-- MODAL COMPRAS -->
<div class="modal fade" tabindex="-1" id="modalComprar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fazer Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="form-comprar">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="fornecedor" class="form-label">Fornecedor</label>
                        <select class="form-select mt-1" aria-label="Default select example" name="fornecedor">
                            <?php

                            $query = $pdo->query("SELECT * from fornecedores order by nome asc");
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
                                echo '<option value="">Cadastre um Fornecedor</option>';
                            } ?>

                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6">

                            <div class="mb-3">
                                <label for="valor_compra" class="form-label">Valor Compra</label>
                                <input type="text" class="form-control" id="valor_compra" name="valor_compra" placeholder="Valor Compra" required="">
                            </div>

                        </div>
                        <div class="col-6">

                            <div class="mb-3">
                                <label for="quantidade" class="form-label">Quantidade</label>
                                <input type="number" class="form-control" id="quantidade" name="quantidade" placeholder="Quantidade" required="">
                            </div>

                        </div>
                    </div>

                    <small>
                        <div style="text-align: center;" class="mt-1" id="mensagem-comprar"></div>
                    </small>

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-fechar-comprar" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button name="btn-salvar-comprar" id="btn-salvar-comprar" type="submit" class="btn btn-danger">Salvar</button>

                    <input name="id-comprar" id="id-comprar" type="hidden">

                </div>
            </form>
        </div>
    </div>
</div>
<!-- FIM MODAL COMPRAS -->

<!-- SCRIPT CHAMA MODAL INSERIR -->
<?php
if (@$_GET['funcao'] == "novo") { ?>
    <script type="text/javascript">
        var myModal = new bootstrap.Modal(document.getElementById('modalCadastrar'), {
            backdrop: 'static'
        })

        myModal.show();
    </script>
<?php } ?>
<!-- FIM SCRIPT CHAMA MODAL INSERIR -->

<!-- SCRIPT CHAMA MODAL EDITAR -->
<?php
if (@$_GET['funcao'] == "editar") { ?>
    <script type="text/javascript">
        var myModal = new bootstrap.Modal(document.getElementById('modalCadastrar'), {
            backdrop: 'static'
        })

        myModal.show();
    </script>
<?php } ?>
<!-- FIM SCRIPT CHAMA MODAL EDITAR -->

<!-- SCRIPT CHAMA MODAL DELEÇÃO -->
<?php
if (@$_GET['funcao'] == "deletar") { ?>
    <script type="text/javascript">
        var myModal = new bootstrap.Modal(document.getElementById('modalDeletar'), {

        })

        myModal.show();
    </script>
<?php } ?>
<!-- FIM SCRIPT CHAMA MODAL DELEÇÃO -->

<!--AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS -->
<script type="text/javascript">
    $("#form").submit(function() {
        document.getElementById('codigo').focus();
        var pagina = "<?= $pagina ?>";
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: pagina + "/inserir.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {

                $('#mensagem').removeClass()

                if (mensagem.trim() == "Salvo com Sucesso!") {

                    //$('#nome').val('');
                    //$('#cpf').val('');
                    $('#btn-fechar').click();
                    window.location = "index.php?pagina=" + pagina;

                } else {

                    $('#mensagem').addClass('text-danger')
                }

                $('#mensagem').text(mensagem)

            },

            cache: false,
            contentType: false,
            processData: false,

        });
    });
</script>
<!-- FIM AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS -->

<!--AJAX PARA EXCLUIR DADOS -->
<script type="text/javascript">
    $("#form-excluir").submit(function() {
        var pagina = "<?= $pagina ?>";
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: pagina + "/excluir.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {

                $('#mensagem').removeClass()

                if (mensagem.trim() == "Excluído com Sucesso!") {

                    $('#mensagem-excluir').addClass('text-success')

                    $('#btn-fechar-excluir').click();
                    window.location = "index.php?pagina=" + pagina;

                } else {

                    $('#mensagem-excluir').addClass('text-danger')
                }

                $('#mensagem-excluir').text(mensagem)

            },

            cache: false,
            contentType: false,
            processData: false,

        });
    });
</script>
<!--FIM AJAX PARA EXCLUIR DADOS -->

<!--AJAX ORDENAR DATATABLE -->
<script type="text/javascript">
    $(document).ready(function() {
        gerarCodigo();
        $('#produtos').DataTable({
            "ordering": false
        });
    });
</script>
<!--FIM AJAX ORDENAR DATATABLE -->

<!--SCRIPT PARA CARREGAR IMAGEM -->
<script type="text/javascript">
    function carregarImg() {

        var target = document.getElementById('target');
        var file = document.querySelector("input[type=file]").files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);


        } else {
            target.src = "";
        }
    }
</script>
<!--FIM SCRIPT PARA CARREGAR IMAGEM -->

<!--SCRIPT PARA MODAL DADOS -->
<script type="text/javascript">
    function mostrarDados(descricao, categoria, nome_forn, tel_forn, lucro, estoque_min, prazo, imagem) {
        event.preventDefault();


        $('#descricao-registro').text(descricao);
        $('#categoria-registro').text(categoria);
        if (nome_forn === '') {
            const divFornecedor = document.querySelector('.div-forn');
            if (divFornecedor) {
                divFornecedor.style.display = 'none';
            }
        } else {
            const divFornecedor = document.querySelector('.div-forn');
            if (divFornecedor) {
                divFornecedor.style.display = 'block'; // Mostra a di
            }
            $('#fornecedor-registro').text(nome_forn);
            $('#telefone-registro').text(tel_forn);
        }
        $('#lucro-registro').text(lucro);
        $('#estoque-min-registro').text(estoque_min);
        $('#prazo-registro').text(prazo);
        $('#imagem-registro').attr('src', '../assets/img/produtos/' + imagem);

        var myModal = new bootstrap.Modal(document.getElementById('modalDados'), {

        })

        myModal.show();
    }
</script>
<!--FIM SCRIPT PARA MODAL DADOS -->

<!--AJAX PARA GERAR CÓDIGO DE BARRAS -->
<script type="text/javascript">
    $("#codigo").keyup(function() {
        gerarCodigo();
    });
</script>
<!-- CONTINUAÇÃO -->
<script type="text/javascript">
    var pagina = "<?= $pagina ?>";

    function gerarCodigo() {
        $.ajax({
            url: pagina + "/barras.php",
            method: 'POST',
            data: $('#form').serialize(),
            dataType: "html",

            success: function(result) {
                $("#codigoBarra").html(result);
            }
        });
    }
</script>
<!--FIIM AJAX PARA GERAR CÓDIGO DE BARRAS -->

<!--AJAX PARA CHAMAR MODAL COMPRAS -->
<script type="text/javascript">
    function comprarProdutos(id) {
        event.preventDefault();

        $('#id-comprar').val(id);

        var myModal = new bootstrap.Modal(document.getElementById('modalComprar'), {

        })
        myModal.show();
    }
</script>
<!--FIM AJAX PARA CHAMAR MODAL COMPRAS -->

<!--AJAX PARA COMPRAR PRODUTO -->
<script type="text/javascript">
    $("#form-comprar").submit(function() {
        var pagina = "<?= $pagina ?>";
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: pagina + "/comprar-produto.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {

                $('#mensagem-comprar').removeClass()

                if (mensagem.trim() == "Salvo com Sucesso!") {

                    //$('#nome').val('');
                    //$('#cpf').val('');
                    $('#btn-fechar-comprar').click();
                    window.location = "index.php?pagina=" + pagina;

                } else {

                    $('#mensagem-comprar').addClass('text-danger')
                }

                $('#mensagem-comprar').html(mensagem)

            },

            cache: false,
            contentType: false,
            processData: false,

        });
    });
</script>
<!--FIM AJAX PARA COMPRAR PRODUTO -->