<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>

</head>

<body>
    <div class="mt-4" style="margin-right:25px">
        <?php
        @session_start();
        $pagina = 'produtos';
        require_once('../conexao.php');
        require_once('verificar-permissao.php');
        
        ?>
        <h5 style="text-align: center; color: darkgray;">PRODUTOS</h5>
        <a href="index.php?pagina=<?php echo $pagina ?>&funcao=novo" type="button" class="btn btn-sm btn-secondary mt-2 mb-2">Novo Produto</a>

        <?php
        $query = $pdo->query("SELECT * FROM produtos ORDER BY id ASC");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = @count($res);
        if ($total_reg) {
        ?>
            <small>
                <table id="usuarios" class="table table-hover" style="width:100%; font-size: 10px;">
                    <div class="table-responsive">
                        <thead>
                            <tr>
                                <th class="text-center">Nome</th>
                                <th class="text-center">Código</th>
                                <th class="text-center">Estoque</th>
                                <th class="text-center">Valor Compra</th>
                                <th class="text-center">Valor Venda</th>
                                <th class="text-center">Fornecedor</th>
                                <th class="text-center">Imagem</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <?php
                        for ($i = 0; $i < $total_reg; $i++) {
                            foreach ($res[$i] as $key => $value); {
                            }
                            $id_cat = $res[$i]['categoria'];
                            $query_2 = $pdo->query("SELECT * from categorias where id = '$id_cat'");
                            $res_2 = $query_2->fetchAll(PDO::FETCH_ASSOC);
                            $nome_cat = $res_2[0]['nome'];
                        ?>

                            <tbody>

                                <tr>
                                    <td class="text-center"><?php echo $res[$i]['nome'] ?></td>
                                    <td class="text-center"><?php echo $res[$i]['codigo'] ?></td>
                                    <td class="text-center"><?php echo $res[$i]['estoque'] ?></td>
                                    <td class="text-center">R$ <?php echo $res[$i]['valor_compra'] ?></td>
                                    <td class="text-center">R$ <?php echo $res[$i]['valor_venda'] ?></td>
                                    <td class="text-center"><?php echo $res[$i]['fornecedor'] ?></td>
                                    <td class="text-center"><img src="../assets/img/produtos/<?php echo $res[$i]['imagem'] ?>" width="30px"></td class="text-center">
                                    <td>
                                        <a href="index.php?pagina=<?php echo $pagina ?>&funcao=editar&id=<?php echo $res[$i]['id'] ?>" title="Editar">
                                            <i class="bi bi-pencil-square text-primary"></i>
                                        </a>

                                        <a href="index.php?pagina=<?php echo $pagina ?>&funcao=deletar&id=<?php echo $res[$i]['id'] ?>" title="Excluir">
                                            <i class="bi bi-trash text-danger mx-1"></i>
                                        </a>

                                        <a href="#" title="Dados Adiconais" onclick="mostrarDados(' <?php echo $res[$i]['nome'] ?>', 
                                                                                                    '<?php echo $res[$i]['descricao'] ?>', 
                                                                                                    '<?php echo $nome_cat ?>', 
                                                                                                    '<?php echo $res[$i]['lucro'] ?>', 
                                                                                                    '<?php echo $res[$i]['estoque_min'] ?>',
                                                                                                    '<?php echo $res[$i]['imagem'] ?>')">
                                            <i class="bi bi-info-circle-fill text-primary mx-1"></i>
                                        </a>

                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                    </div>
                </table>
            </small>
        <?php } else {
            echo '<p>Não existem dados para serem exibidos!!</p>';
        } 
        ?>
    </div>

    <?php
    if (@$_GET['funcao'] == "editar") {
        $titulo_modal = "Editar";
        $query = $pdo->query("SELECT * FROM produtos WHERE id = '$_GET[id]'");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = @count($res);
        if ($total_reg) {
            $id = $res[0]['id'];
            $codigo = $res[0]['codigo'];
            $nome = $res[0]['nome'];
            $descricao = $res[0]['descricao'];
            $valor_compra = $res[0]['valor_compra'];
            $valor_venda = $res[0]['valor_venda'];
            $categoria = $res[0]['categoria'];
            $fornecedor = $res[0]['fornecedor'];
            $estoque = $res[0]['estoque'];
            $lucro = $res[0]['lucro'];
            $estoque_min = $res[0]['estoque_min'];
            $imagem = $res[0]['imagem'];
        }
    } else {
        $titulo_modal = "Inserir";
    }
    ?>

    <!-- Modal de Inserção Edição -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalCadastro">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $titulo_modal ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <form method="post" id="form">
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="codigo" class="form-label">Código </label>
                                    <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Código" value="<?php echo @$codigo ?>" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome </label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" value="<?php echo @$nome ?>" required>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Valor Venda </label>
                                    <input type="text" class="form-control" id="valor_venda" name="valor_venda" placeholder="Valor da Venda" required value="<?php echo @$valor_venda ?>">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Categoria </label>
                                    <select class="form-select" aria-label="Default select example" name="categoria">
                                        <?php
                                        $query = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC");
                                        $res = $query->fetchAll(PDO::FETCH_ASSOC);
                                        for ($i = 0; $i < @count($res); $i++) {
                                            foreach ($res[$i] as $key => $value) {
                                            }
                                            $id_item = $res[$i]['id'];
                                            $nome_item = $res[$i]['nome'];
                                        ?>
                                            <option <?php if (@$id_item == @$categoria) { ?> selected <?php } ?> value="<?php echo $id_item ?>"><?php echo $nome_item ?></option>

                                        <?php } ?>

                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Descrição </label>
                                    <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição do Produto" value="<?php echo @$descricao ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Imagem</label>
                            <input type="file" value="<?php echo @$imagem ?>" class="form-control-file" id="imagem" name="imagem" onChange="carregarImg();">
                        </div>

                        <div id="divImgConta" class="mt-4">
                            <?php if (@$imagem != "") { ?>
                                <img src="../assets/img/<?php echo $pagina ?>/<?php echo @$imagem ?>" width="170px" id="target">
                            <?php  } else { ?>
                                <img src="../assets/img/<?php echo $pagina ?>/sem-foto.jpg" width="170px" id="target">

                            <?php } ?>
                        </div>

                        <div id="codigoBarra">

                        </div>

                        <input type="hidden" name="id" value="<?php echo @$id ?>">

                        <small>
                            <div align="center" id="mensagem">
                            </div>
                        </small>

                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
                        <button type="submit" class="btn btn-secondary" name="btn-salvar" id="btn-salvar">Salvar</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
    <!-- Fim Modal de Inserção Edição -->

    <!-- Modal de Exclusão -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalDeleta">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Excluir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <form method="POST" id="frm-excluir">
                    <div class="modal-body text-center">

                        <p>Deseja realmente excluir o registro <?php echo $_GET['id'] ?> ?</p>

                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-excluir">Fechar</button>
                            <button type="submit" class="btn btn-danger" name="btn-excluir" id="btn-excluir">Excluir</button>
                        </div>

                        <input name="id" type="hidden" value="<?php echo @$_GET['id'] ?>">

                        <small>
                            <div align="center" id="mensagem-excluir"></div>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Fim Modal de Exclusão -->

    <!-- Modal Dados Adicionais -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalDados">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dados Adicionais</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body mb-5">

                    <strong>Nome: </strong>
                    <span id="nome-registro"></span>
                    <hr>
                    <strong>Descrição: </strong>
                    <span id="descricao-registro"></span>
                    <hr>
                    <strong>Categoria: </strong>
                    <span id="categoria-registro"></span>
                    <hr>
                    <strong>Lucro: </strong>
                    <span id="lucro-registro"></span>
                    <hr>
                    <strong>Estoque Mínimo: </strong>
                    <span id="estoque-min-registro"></span>
                    <hr>
                    <img id="imagem-registro" src="" class="mx-auto d-block" width="250px">

                </div>

            </div>
        </div>
    </div>
    <!-- Fim Modal Dados Adicionais -->

</body>

</html>

<!-- Ajax chama Inclusão -->
<?php
if (@$_GET['funcao'] == 'novo') { ?>
    <script type="text/javascript">
        var myModal = new bootstrap.Modal(document.getElementById('modalCadastro'), {
            backdrop: 'static'
        });
        myModal.show();
    </script>
<?php } ?>
<!-- Fim Ajax chama Inclusão -->

<!-- Ajax chama Edição -->
<?php
if (@$_GET['funcao'] == 'editar') { ?>
    <script type="text/javascript">
        var myModal = new bootstrap.Modal(document.getElementById('modalCadastro'), {
            backdrop: 'static'
        });
        myModal.show();
    </script>
<?php } ?>
<!-- Fim Ajax chama Edição -->

<!-- Ajax chama Exclusão -->
<?php
if (@$_GET['funcao'] == 'deletar') { ?>
    <script type="text/javascript">
        var myModal = new bootstrap.Modal(document.getElementById('modalDeleta'), {
            backdrop: 'static'
        });
        myModal.show();
    </script>
<?php } ?>
<!-- Fim Ajax chama Exclusão -->

<!--AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS -->
<script type="text/javascript">
    $("#frm-cadastro").submit(function() {
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
                    //location.reload();
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

<!--AJAX EXCLUSÃO DOS DADOS -->
<script type="text/javascript">
    $("#frm-excluir").submit(function() {
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
                    //$('#nome').val('');
                    //$('#cpf').val('');
                    $('#btn-fechar').click();
                    window.location = "index.php?pagina=" + pagina;
                    //location.reload();
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
<!--AJAX EXCLUSÃO DOS DADOS -->

<!-- SCRIPT PARA DATATABLE -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#usuarios').DataTable({
            "ordering": false
        });
    });
</script>
<!-- FIM SCRIPT PARA DATATABLE -->


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
<!--FINAL SCRIPT PARA CARREGAR IMAGEM -->

<!-- SCRIPT MOSTRAR DADOS -->
<script type="text/javascript">
    function mostrarDados(nome, descricao, categoria, lucro, estoque_min, imagem) {
        event.preventDefault();

        $('#nome-registro').text(nome);
        $('#descricao-registro').text(descricao);
        $('#categoria-registro').text(categoria);
        $('#lucro-registro').text(lucro);
        $('#estoque-min-registro').text(estoque_min);
        $('#imagem-registro').attr('src', '../assets/img/produtos/' + imagem);

        var myModal = new bootstrap.Modal(document.getElementById('modalDados'), {
            backdrop: 'static'
        })
        myModal.show();
    }
</script>
<!-- FIM SCRIPT MOSTRAR DADOS -->

<!-- SCRIPT GERA CÓDIGO DE BARRAS -->
<script type="text/javascript">
	$("#codigo").keyup(function () {
        gerarCodigo();
	});
</script>
<script type="text/javascript">
    var pagina = "<?= $pagina ?>";
	function gerarCodigo(){
        $.ajax({
            url: pagina + "/barras.php",
            method: 'POST',
            data: $('#form').serialize(),
            dataType: 'html',
            success: function(result){
                $("#codigoBarra").html(result);
            }
        });
    }
</script>
<!-- FIM SCRIPT GERA CÓDIGO DE BARRAS -->