<?php
@session_start();
$pagina = 'vendas';

require_once('../conexao.php');
require_once('verificar-permissao.php');

?>
<h5 style="text-align: center;" class="text-secondary">VENDAS</h5>
<div class="mt-4" style="margin-right:25px">
    <?php
    $query = $pdo->query("SELECT * FROM vendas ORDER BY id DESC");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if ($total_reg > 0) {
    ?>
        <small>
            <table id="example" class="table table-hover my-4" style="width:100%; font-size: 10px;">
                <thead>
                    <tr>
                        <th class="text-center">Status</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Data</th>
                        <th class="text-center">Usuário</th>
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

                        if ($res[$i]['status_venda'] == 'Fechada') {
                            $classe = 'text-success';
                        } else if ($res[$i]['status_venda'] == 'Aberta') {
                            $classe = 'text-danger';
                        }

                    ?>

                        <tr>
                            <td class="text-center"> <i class="bi bi-square-fill <?php echo $classe ?>"></i></td>
                            <td class="text-center">R$ <?php echo number_format($res[$i]['valor'], 2, ',', '.'); ?></td>
                            <td class="text-center"><?php echo implode('/', array_reverse(explode('-', $res[$i]['data_venda']))); ?></td>
                            <td class="text-center"><?php echo $nome_usuario ?></td>  

                        </tr>

                    <?php } ?>

                </tbody>

            </table>
        </small>
    <?php } else {
        echo '<p>Não existem dados para serem exibidos!!';
    } ?>
</div>

<!-- SCRIPT PARA DATATABLE -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable({
            "ordering": false
        });
    });
</script>
<!-- FIM SCRIPT PARA DATATABLE -->