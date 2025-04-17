<?php
@session_start();
$pagina = 'compras';

require_once('../conexao.php');
require_once('verificar-permissao.php');

?>
<h5 style="text-align: center; color: darkgray;">COMPRAS</h5>
<div class="mt-4" style="margin-right:25px">
    <?php
    $query = $pdo->query("SELECT * FROM compras ORDER BY id ASC");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if ($total_reg > 0) {
    ?>
        <small>
            <table id="example" class="table table-hover my-4" style="width:100%; font-size: 10px;">
                <thead>
                    <tr>
                        <th class="text-center">Pago</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Data</th>
                        <th class="text-center">Usuário</th>
                        <th class="text-center">Fornecedor</th>
                        <th class="text-center">Tel Fornecedor</th>
                        <th class="text-center">Excluir</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    for ($i = 0; $i < $total_reg; $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }

                        //BUSCAR OS DADOS DO USUARIO
                        $id_usu = $res[$i]['usuario'];
                        $query_f = $pdo->query("SELECT * from usuarios where id = '$id_usu'");
                        $res_f = $query_f->fetchAll(PDO::FETCH_ASSOC);
                        $total_reg_f = @count($res_f);
                        if ($total_reg_f > 0) {
                            $nome_usuario = $res_f[0]['nome'];
                        }

                        //BUSCAR OS DADOS DO FORNECEDOR
                        $id_forn = $res[$i]['fornecedor'];
                        $query_f = $pdo->query("SELECT * from fornecedores where id = '$id_forn'");
                        $res_f = $query_f->fetchAll(PDO::FETCH_ASSOC);
                        $total_reg_f = @count($res_f);
                        if ($total_reg_f > 0) {
                            $nome_forn = $res_f[0]['nome'];
                            $tel_forn = $res_f[0]['telefone'];
                        }

                        if ($res[$i]['pago'] == 'Sim') {
                            $classe = 'text-success';
                        } else {
                            $classe = 'text-danger';
                        }

                    ?>

                        <tr>
                            <td class="text-center"> <i class="bi bi-square-fill <?php echo $classe ?>"></i></td>
                            <td class="text-center">R$ <?php echo number_format($res[$i]['total'], 2, ',', '.'); ?></td>
                            <td class="text-center"><?php echo implode('/', array_reverse(explode('-', $res[$i]['data_compra']))); ?></td>
                            <td class="text-center"><?php echo $nome_usuario ?></td>
                            <td class="text-center"><?php echo $nome_forn ?></td>
                            <td class="text-center"><?php echo $tel_forn ?></td>
                            <td class="text-center">
                                <?php if ($res[$i]['pago'] != 'Sim') { ?>
                                    <a href="index.php?pagina=<?php echo $pagina ?>&funcao=deletar&id=<?php echo $res[$i]['id'] ?>" title="Excluir">
                                        <i class="bi bi-trash text-danger mx-1"></i>
                                    </a>
                                <?php } ?>
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

                    <p>Deseja realmente excluir a compra <?php echo $_GET['id'] ?> ?</p>

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

<!-- SCRIPT PARA DATATABLE -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable({
            "ordering": false
        });
    });
</script>
<!-- FIM SCRIPT PARA DATATABLE -->

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
                $('#mensagem-excluir').html(mensagem)
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });
</script>
<!--AJAX EXCLUSÃO DOS DADOS -->