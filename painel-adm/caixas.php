<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixas</title>

</head>

<body>
    <div class="mt-4" style="margin-right:25px">
        <?php
        @session_start();
        $pagina = 'caixas';
        require_once('../conexao.php');
        require_once('verificar-permissao.php');
        ?>
        <h5 style="text-align: center;" class="txt-secondary">CAIXAS</h5>
        <a href="index.php?pagina=<?php echo $pagina ?>&funcao=novo" type="button" class="btn btn-sm btn-secondary mt-2 mb-2">Nova Caixa</a>

        <?php
        $query = $pdo->query("SELECT * FROM caixas ORDER BY id ASC");
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
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <?php
                        for ($i = 0; $i < $total_reg; $i++) {
                            foreach ($res[$i] as $key => $value); {
                            }
                        ?>
                            <tbody>
                                <tr>
                                    <td class="text-center"><?php echo $res[$i]['nome'] ?></td>
                                    <td class="text-center">
                                        <a href="index.php?pagina=<?php echo $pagina ?>&funcao=editar&id=<?php echo $res[$i]['id'] ?>" title="Editar" style="text-decoration: none;">
                                            <i class="bi bi-pencil-square text-primary"></i>
                                        </a>

                                        <a href="index.php?pagina=<?php echo $pagina ?>&funcao=deletar&id=<?php echo $res[$i]['id'] ?>" title="Excluir">
                                            <i class="bi bi-trash text-danger mx-1"></i>
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
        $query = $pdo->query("SELECT * FROM caixas WHERE id = '$_GET[id]'");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = @count($res);
        if ($total_reg) {
            $id = $res[0]['id'];
            $nome = $res[0]['nome'];
        }
    } else {
        $titulo_modal = "Inserir";
    }
    ?>

    <!-- Modal de Inserção Edição -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalCadastro">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $titulo_modal ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <form method="POST" id="frm-cadastro">

                    <div class="modal-body">

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" value="<?php echo @$nome ?>" required="">
                            </div>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
                            <button type="submit" class="btn btn-secondary" name="btn-salvar" id="btn-salvar">Salvar</button>
                        </div>

                        <input name="id" type="hidden" value="<?php echo @$id ?>">
                        <input name="nome_double" type="hidden" value="<?php echo @$nome ?>">


                        <small>
                            <div style="text-align: center;" id="mensagem">

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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-excluir">Fechar</button>
                            <button type="submit" class="btn btn-danger" name="btn-excluir" id="btn-excluir">Excluir</button>
                        </div>

                        <input name="id" type="hidden" value="<?php echo @$_GET['id'] ?>">

                        <small>
                            <div style="text-align: center;" id="mensagem-excluir"></div>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Fim Modal de Exclusão -->

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