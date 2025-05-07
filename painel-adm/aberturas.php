<?php
@session_start();
$pagina = 'aberturas';

require_once('../conexao.php');
require_once('verificar-permissao.php');

?>
<h5 style="text-align: center;" class="txt-secondary">ABERTURAS DE CAIXAS</h5>
<div class="mt-4" style="margin-right:25px">
    <?php
    $query = $pdo->query("SELECT * FROM caixa  ORDER BY id DESC");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if ($total_reg > 0) {
    ?>
        <small>
            <table id="example" class="table table-hover my-4" style="width:100%; font-size: 10px;">
                <thead>
                    <tr>
                        <th class="text-center">Data</th>
                        <th class="text-center">Vendido</th>
                        <th class="text-center">Quebra</th>
                        <th class="text-center">Caixa</th>
                        <th class="text-center">Operador</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    for ($i = 0; $i < $total_reg; $i++) {
                        foreach ($res[$i] as $key => $value) {
                        }

                        //BUSCAR OS DADOS DO USUARIO
                        $id_usu = $res[$i]['operador'];
                        $query_f = $pdo->query("SELECT * from usuarios where id = '$id_usu'");
                        $res_f = $query_f->fetchAll(PDO::FETCH_ASSOC);
                        $total_reg_f = @count($res_f);
                        if ($total_reg_f > 0) {
                            $nome_usuario = $res_f[0]['nome'];
                        }

                        if ($res[$i]['status_caixa'] == 'Fechada') {
                            $classe = 'text-success';
                        } else if ($res[$i]['status_caixa'] == 'Aberta') {
                            $classe = 'text-danger';
                        } else if ($res[$i]['status_caixa'] == 'Cancelada') {
                            $classe = 'text-warning';
                        }

                    ?>

                        <tr>
                            <td class="text-center"><?php echo implode('/', array_reverse(explode('-', $res[$i]['data_ab']))); ?></td>=
                            <td class="text-center">R$ <?php echo number_format($res[$i]['valor_vendido'], 2, ',', '.'); ?></td>
                            <td class="text-center">R$ <?php echo number_format($res[$i]['valor_quebra'], 2, ',', '.'); ?></td>
                            <td class="text-center"><?php echo str_pad($res[$i]['caixa'], 3, '0', STR_PAD_LEFT); ?></td>
                            <td class="text-center"><?php echo $nome_usuario ?></td>

                            <td class="text-center">
                                <a href="../comprovante_class.php?id=<?php echo $res[$i]['id'] ?>" title="Imprimir" style="text-decoration: none;" target="_blank">
                                    <i class="bi bi-printer-fill text-primary"></i>
                                </a>

                                <a href="index.php?pagina=<?php echo $pagina ?>&funcao=deletar&id=<?php echo $res[$i]['id'] ?>" title="Cancelar">
                                    <i class="bi bi-x-circle-fill text-danger mx-1"></i>
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
                <h5 class="modal-title">Cancelar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <form method="POST" id="frm-excluir">
                <div class="modal-body text-center">

                    <p>Deseja realmente <strong>cancelar</strong> a compra <?php echo $_GET['id'] ?> ?</p>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-excluir">Fechar</button>
                        <button type="submit" class="btn btn-danger" name="btn-excluir" id="btn-excluir">Cancelar</button>
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
                if (mensagem.trim() == "Cancelado com Sucesso!") {
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