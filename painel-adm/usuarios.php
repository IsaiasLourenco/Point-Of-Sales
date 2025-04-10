<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>
    
</head>

<body>
    <div class="mt-4" style="margin-right:25px">
        <?php
        $pagina = 'usuarios';
        require_once('../conexao.php');
        ?>
        <a href="index.php?pagina=<?php echo $pagina ?>&funcao=novo" type="button" class="btn btn-sm btn-secondary mt-2 mb-2">Novo Usuário</a>

        <?php
        $query = $pdo->query("SELECT * FROM usuarios");
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
                                <th>Email</th>
                                <th>CPF</th>
                                <th>Senha</th>
                                <th>Nivel</th>
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
                                    <td><?php echo $res[$i]['email'] ?></td>
                                    <td><?php echo $res[$i]['cpf'] ?></td>
                                    <td><?php echo $res[$i]['senha'] ?></td>
                                    <td><?php echo $res[$i]['nivel'] ?></td>
                                    <td>
                                        <a href="index.php?pagina=<?php echo $pag ?>&funcao=editar&id=<?php echo $res[$i]['id'] ?>" title="Editar Registro">
                                            <i class="bi bi-pencil-square text-primary"></i>
                                        </a>

                                        <a href="index.php?pagina=<?php echo $pag ?>&funcao=deletar&id=<?php echo $res[$i]['id'] ?>" title="Excluir Registro">
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
    <!-- Modal de Inserção Edição -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalCadastro">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Inserir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <form method="POST" id="frm-cadastro">

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required="">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="email" required="">
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" required="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="senha" class="form-label">Senha</label>
                                    <input type="text" class="form-control" id="senha" name="senha" placeholder="Senha" required="">
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nivel" class="form-label">Nível</label>
                                    <select class="form-select mt-1" aria-label="Default select example" name="nivel">
                                        <option <?php if (@$nivel == 'Operador') { ?> selected <?php } ?> value="Operador">Operador</option>
                                        <option <?php if (@$nivel == 'Administrador') { ?> selected <?php } ?> value="Administrador">Administrador</option>
                                        <option <?php if (@$nivel == 'Tesoureiro') { ?> selected <?php } ?> value="Tesoureiro">Tesoureiro</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
                                    <button type="submit" class="btn btn-sm btn-secondary" name="btn-salvar" id="btn-salvar">Salvar</button>
                                </div>
                            </div>

                        </div>

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

<!--AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS COM IMAGEM -->
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
                    window.location = "index.php?pagina="+pagina;
                    //location.reload();
                } else {
                    $('#mensagem').addClass('text-danger')
                }
                $('#mensagem').text(mensagem)
            },
            cache: false,
            contentType: false,
            processData: false,
            xhr: function() { // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                    myXhr.upload.addEventListener('progress', function() {
                        /* faz alguma coisa durante o progresso do upload */
                    }, false);
                }
                return myXhr;
            }
        });
    });
</script>
<!-- FIM AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS COM IMAGEM -->

<!-- SCRIPT PARA DATATABLE -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#usuarios').DataTable({
            "ordering": false
        });
    });
</script>
<!-- FIM SCRIPT PARA DATATABLE -->