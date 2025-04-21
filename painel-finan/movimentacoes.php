<?php
@session_start();
$pagina = 'movimentacoes';

require_once('../conexao.php');
require_once('verificar-permissao.php');

?>
<h5 style="text-align: center;" class="text-secondary">MOVIMENTAÇÕES</h5>
<div class="mt-4" style="margin-right:25px">
    <?php
    $entradas = 0;
    $saidas = 0;
    $total = 0;
    $query = $pdo->query("SELECT * FROM movimentacoes ORDER BY id ASC");
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    if ($total_reg > 0) {
    ?>
        <small>
            <table id="example" class="table table-hover my-4" style="width:100%; font-size: 10px;">
                <thead>
                    <tr>
                        <th class="text-center">Tipo</th>
                        <th class="text-center">Descrição</th>
                        <th class="text-center">Valor</th>
                        <th class="text-center">Usuário</th>
                        <th class="text-center">Data</th>
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

                        if ($res[$i]['tipo'] == 'Entrada') {
                            $classe = 'text-success';
                            $entradas += $res[$i]['valor'];
                        } else if ($res[$i]['tipo'] == 'Saída') {
                            $classe = 'text-danger';
                            $saidas += $res[$i]['valor'];
                        }

                        $saldo = $entradas - $saidas;

                    ?>

                        <tr>
                            <td class="text-center"><i class="bi bi-square-fill <?php echo $classe ?>"></i><span class="d-none"><?php echo $res[$i]['tipo'] ?></span></td>
                            <td class="text-center"><?php echo $res[$i]['descricao'] ?></td>
                            <td class="text-center">R$ <?php echo number_format($res[$i]['valor'], 2, ',', '.'); ?></td>
                            <td class="text-center"><?php echo $nome_usuario ?></td>
                            <td class="text-center"><?php echo implode('/', array_reverse(explode('-', $res[$i]['data_mov']))); ?></td>
                        </tr>

                    <?php } ?>

                </tbody>

            </table>

            <?php
            $entradasDia = 0;
            $saidasDia = 0;
            $saldoDia = 0;

            $query = $pdo->query("SELECT * FROM movimentacoes WHERE DATE(data_mov) = CURDATE() ORDER BY id ASC");
            $res = $query->fetchAll(PDO::FETCH_ASSOC);

            if (count($res) > 0) {
                foreach ($res as $mov) {
                    if ($mov['tipo'] == 'Entrada') {
                        $entradasDia += $mov['valor'];
                    } elseif ($mov['tipo'] == 'Saída') {
                        $saidasDia += $mov['valor'];
                    }
                }
                $saldoDia = $entradasDia - $saidasDia;
            }
            ?>
            <div class="saldos" style="display: flex; justify-content: space-between;gap: 20px;">

                <small class="mt-4">
                    <h6 style="text-align: left; margin-left: 25px;">SALDO</h6>
                    <div class="row bg-light mt-4 px-4" style="text-align: left;">
                        <strong class="mb-2 text-secondary">Vendas - R$ <?php echo number_format($entradas, 2, ',', '.') ?></strong>
                        <strong class="mb-4 text-danger">Compras - R$ <?php echo number_format($saidas, 2, ',', '.') ?></strong>
                        <hr>
                        <?php
                        if ((float)$saldo < 0) {
                            echo '<strong class="text-danger">Saldo - R$ ' . number_format($saldo, 2, ',', '.') . '</strong>';
                        } else {
                            echo '<strong class="text-primary">Saldo - R$ ' . number_format($saldo, 2, ',', '.') . '</strong>';
                        }
                        ?>
                    </div>
                </small>

                <small class="mt-4">
                    <h6 style="text-align: right; margin-right: 25px;">SALDO DO DIA</h6>
                    <div class="row bg-light mt-4 px-4" style="text-align: right;">
                        <strong class="mb-2 text-secondary">Vendas - R$ <?php echo number_format($entradasDia, 2, ',', '.') ?></strong>
                        <strong class="mb-4 text-danger">Compras - R$ <?php echo number_format($saidasDia, 2, ',', '.') ?></strong>
                        <hr>
                        <?php
                        if ((float)$saldoDia < 0) {
                            echo '<strong class="text-danger">Saldo - R$ ' . number_format($saldoDia, 2, ',', '.') . '</strong>';
                        } else {
                            echo '<strong class="text-primary">Saldo - R$ ' . number_format($saldoDia, 2, ',', '.') . '</strong>';
                        }
                        ?>
                    </div>
                </small>

            </div>

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