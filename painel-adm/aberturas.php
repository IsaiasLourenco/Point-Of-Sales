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
                        <th class="text-center">Aberto</th>
                        <th class="text-center">Data</th>
                        <th class="text-center">Vendido</th>
                        <th class="text-center">Quebra</th>
                        <th class="text-center">Caixa</th>
                        <th class="text-center">Operador</th>
                        <th class="text-center">Relatórios do Caixa</th>
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

                        if ($res[$i]['status_caixa'] == 'Aberto') {
                            $classe = 'text-success';
                        } else if ($res[$i]['status_caixa'] == 'Fechado') {
                            $classe = 'text-danger';
                        }

                    ?>

                        <tr>
                            <td class="text-center"> <i class="bi bi-square-fill <?php echo $classe ?>"></i><span class="d-none"><?php echo $res[$i]['status_caixa'] ?></span></td>
                            <td class="text-center"><?php echo implode('/', array_reverse(explode('-', $res[$i]['data_ab']))); ?></td>
                            <td class="text-center">R$ <?php echo number_format($res[$i]['valor_vendido'], 2, ',', '.'); ?></td>
                            <td class="text-center">R$ <?php echo number_format($res[$i]['valor_quebra'], 2, ',', '.'); ?></td>
                            <td class="text-center"><?php echo str_pad($res[$i]['caixa'], 3, '0', STR_PAD_LEFT); ?></td>
                            <td class="text-center"><?php echo $nome_usuario ?></td>
                            <td class="text-center">
                                <a href="../rel/relCaixa_class.php?id=<?php echo $res[$i]['id'] ?>" title="Relatório do Caixa" style="text-decoration: none;" target="_blank">
                                    <i class="bi bi-printer-fill text-primary"></i>
                                </a>
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