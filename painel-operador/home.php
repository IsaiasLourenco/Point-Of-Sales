<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Operador</title>
</head>

<body>
    <?php
    @session_start();
    require_once('../conexao.php');
    require_once('verificar-permissao.php');


    ?>
</body>

</html>

<!-- Modal de Abertura do Caixa -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalAbertura">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Abrir Caixa</h5>
            </div>

            <form method="POST" id="frm-abertura">

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="caixa" class="form-label">Caixa</label>
                                <select class="form-select mt-1" aria-label="Default select example" name="caixa">
                                    <?php
                                    $query = $pdo->query("SELECT * FROM caixas ORDER BY nome ASC");
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
                                        echo '<option value="">Cadastre um CaiXa</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gerente" class="form-label">Gerente</label>
                                <select class="form-select mt-1" aria-label="Default select example" name="gerente">
                                    <?php
                                    $query = $pdo->query("SELECT * FROM usuarios WHERE nivel = 'Administrador' ORDER BY nome ASC");
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
                                        echo '<option value="">Cadastre um AdministradorCaiXa</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="valor-ab" class="form-label">Valor Abertura</label>
                                <input type="text" class="form-control" id="valor-ab" name="valor-ab" placeholder="Valor" required="">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha Gerência</label>
                                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required="">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-danger" name="btn-salvar-perfil" id="btn-salvar-abertura">Abrir Caixa</button>
                    </div>

                    <input name="id-abertura" type="hidden" value="<?php echo @$id_usu ?>">

                    <small>
                        <div style="text-align: center;" id="mensagem-abertura">

                        </div>
                    </small>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fim Modal de Abertura do Caixa -->

<!-- Script chama modal de Abertura do Caixa -->
<script type="text/javascript">
    $(document).ready(function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalAbertura'), {
            backdrop: 'static'
        })
        myModal.show();
    });
</script>
<!-- Fim Script chama modal de Abertura do Caixa -->

<!--AJAX PARA ABERTURA DO CAIXA -->
<script type="text/javascript">
    $("#form-abertura").submit(function() {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: "abertura.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {

                $('#mensagem-abertura').removeClass()

                if (mensagem.trim() == "Salvo com Sucesso!") {

                    //$('#nome').val('');
                    //$('#cpf').val('');
                    //$('#btn-fechar').click();
                    window.location = "index.php?pagina=pdv";

                } else {

                    $('#mensagem-abertura').addClass('text-danger')
                }

                $('#mensagem-abertura').text(mensagem)

            },

            cache: false,
            contentType: false,
            processData: false,

        });
    });
</script>
<!--FIM AJAX PARA ABERTURA DO CAIXA -->