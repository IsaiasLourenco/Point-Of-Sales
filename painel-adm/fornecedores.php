<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fornecedores</title>

</head>

<body>
    <div class="mt-4" style="margin-right:25px">
        <?php
        @session_start();
        $pagina = 'fornecedores';
        require_once('../conexao.php');
        require_once('verificar-permissao.php');
        ?>
        <h5 style="text-align: center;" class="text-secondary">FORNECEDORES</h5>
        <a href="index.php" title="Home"><h5 style="text-align: center;" class="text-secondary"><i class="bi bi-house-door"></i></h5></a>
        <a href="index.php?pagina=<?php echo $pagina ?>&funcao=novo" type="button" class="btn btn-sm btn-secondary mt-2 mb-2">Novo Fornecedor</a>

        <?php
        $query = $pdo->query("SELECT * FROM fornecedores ORDER BY id ASC");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = @count($res);
        if ($total_reg) {
        ?>
            <small>
                <table id="usuarios" class="table table-hover" style="width:100%; font-size: 10px;">
                    <div class="table-responsive">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th class="text-center">CNPJ</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Telefone</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <?php
                        for ($i = 0; $i < $total_reg; $i++) {
                            foreach ($res[$i] as $key => $value); {
                            }
                        ?>
                            <tbody>
                                <tr>
                                    <td><?php echo $res[$i]['nome'] ?></td>
                                    <td class="text-center"><?php echo $res[$i]['cnpj'] ?></td>
                                    <td class="text-center"><?php echo $res[$i]['email'] ?></td>
                                    <td class="text-center"><?php echo $res[$i]['telefone'] ?></td>
                                    <td>
                                        <a href="index.php?pagina=<?php echo $pagina ?>&funcao=editar&id=<?php echo $res[$i]['id'] ?>" title="Editar" style="text-decoration: none;">
                                            <i class="bi bi-pencil-square text-success"></i>
                                        </a>

                                        <a href="index.php?pagina=<?php echo $pagina ?>&funcao=deletar&id=<?php echo $res[$i]['id'] ?>" title="Excluir" style="text-decoration: none;">
                                            <i class="bi bi-trash text-danger mx-1"></i>
                                        </a>

                                        <a href="#" title="Dados Adiconais" onclick="mostrarDados(' <?php echo $res[$i]['nome'] ?>', 
                                                                                                    '<?php echo $res[$i]['cep'] ?>', 
                                                                                                    '<?php echo $res[$i]['rua'] ?>', 
                                                                                                    '<?php echo $res[$i]['numero'] ?>', 
                                                                                                    '<?php echo $res[$i]['bairro'] ?>', 
                                                                                                    '<?php echo $res[$i]['cidade'] ?>', 
                                                                                                    '<?php echo $res[$i]['estado'] ?>')">
                                            <i class="bi bi-house-fill text-primary mx-1"></i>
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
        } ?>
    </div>

    <?php
    if (@$_GET['funcao'] == "editar") {
        $titulo_modal = "Editar";
        $query = $pdo->query("SELECT * FROM fornecedores WHERE id = '$_GET[id]'");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = @count($res);
        if ($total_reg) {
            $id = $res[0]['id'];
            $nome = $res[0]['nome'];
            $cnpj = $res[0]['cnpj'];
            $email = $res[0]['email'];
            $telefone = $res[0]['telefone'];
            $cep = $res[0]['cep'];
            $rua = $res[0]['rua'];
            $numero = $res[0]['numero'];
            $bairro = $res[0]['bairro'];
            $cidade = $res[0]['cidade'];
            $estado = $res[0]['estado'];
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

                <form method="POST" id="frm-cadastro">

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" value="<?php echo @$nome ?>" required="">
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="cnpj" class="form-label">CNPJ</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj" placeholder="CNPJ" value="<?php echo @$cnpj ?>" required="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="email" value="<?php echo @$email ?>" required="">
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" placeholder="telefone" value="<?php echo @$telefone ?>" required="">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep" placeholder="CEP" value="<?php echo @$cep ?>" required="">
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="rua" class="form-label">Rua</label>
                                    <input type="text" class="form-control" id="rua" name="rua" placeholder="Rua" value="<?php echo @$rua ?>" readonly>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="numero" class="form-label">Número</label>
                                    <input type="text" class="form-control" id="numero" name="numero" placeholder="Número" value="<?php echo @$numero ?>" required="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro" value="<?php echo @$bairro ?>" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Cidade" value="<?php echo @$cidade ?>" readonly>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="estado" name="estado" placeholder="Estado" value="<?php echo @$estado ?>" readonly>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
                            <button type="submit" class="btn btn-secondary" name="btn-salvar" id="btn-salvar">Salvar</button>
                        </div>

                        <input name="id" type="hidden" value="<?php echo @$id ?>">
                        <input name="cnpj_double" type="hidden" value="<?php echo @$cnpj ?>">
                        <input name="email_double" type="hidden" value="<?php echo @$email ?>">

                        <small>
                            <div align="center" id="mensagem">

                            </div>
                        </small>

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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-del">Fechar</button>
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
                    <strong>CEP: </strong>
                    <span id="cep-registro"></span>
                    <hr>
                    <strong>Rua: </strong>
                    <span id="rua-registro"></span>
                    <hr>
                    <strong>Numero: </strong>
                    <span id="numero-registro"></span>
                    <hr>
                    <strong>Bairro: </strong>
                    <span id="bairro-registro"></span>
                    <hr>
                    <strong>Cidade: </strong>
                    <span id="cidade-registro"></span>
                    <hr>
                    <strong>Estado: </strong>
                    <span id="estado-registro"></span>

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
                    $('#btn-fechar-del').click();
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

<!-- SCRIPT MOSTRAR DADOS -->
<script type="text/javascript">
    function mostrarDados(nome, cep, rua, numero, bairro, cidade, estado) {
        event.preventDefault();

        $('#nome-registro').text(nome);
        $('#cep-registro').text(cep);
        $('#rua-registro').text(rua);
        $('#numero-registro').text(numero);
        $('#bairro-registro').text(bairro);
        $('#cidade-registro').text(cidade);
        $('#estado-registro').text(estado);

        var myModal = new bootstrap.Modal(document.getElementById('modalDados'), {
            backdrop: 'static'
        })
        myModal.show();
    }
</script>
<!-- FIM SCRIPT MOSTRAR DADOS -->