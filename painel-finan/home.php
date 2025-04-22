<?php

require_once('../conexao.php');
require_once('verificar-permissao.php');

$hoje = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$dataInicioMes = $ano_atual . "-" . $mes_atual . "-01";

// SALDO DO DIA
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
    $saldoDia = number_format($saldoDia, 2, ',', '.');
    $entradasDia = number_format($entradasDia, 2, ',', '.');
    $saidasDia = number_format($saidasDia, 2, ',', '.');
}
// FIM DE SALDO DO DIA

// SALDO DO MÊS
$entradasMes = 0;
$saidasMes = 0;
$saldoMes = 0;
$query = $pdo->query("SELECT * FROM movimentacoes WHERE data_mov >= '$dataInicioMes' AND data_mov <= CURDATE() ORDER BY id ASC");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if (count($res) > 0) {
    foreach ($res as $mov) {
        if ($mov['tipo'] == 'Entrada') {
            @$entradasMes += $mov['valor'];
        } elseif ($mov['tipo'] == 'Saída') {
            @$saidasMes += $mov['valor'];
        }
    }
    $saldoMes = $entradasMes - $saidasMes;
    $saldoMes = number_format($saldoMes, 2, ',', '.');
    $entradasMes = number_format($entradasMes, 2, ',', '.');
    $saidasMes = number_format($saidasMes, 2, ',', '.');
}
// FIM SALDO DO MÊS

//BUSCAR ÚLTIMA MOVIMENTAÇÃO
$query = $pdo->query("SELECT * FROM movimentacoes ORDER BY id DESC LIMIT 1");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valorMov = number_format($res[0]['valor'], 2, ',', '.');
$descricaoMov = $res[0]['descricao'];
$tipoMov = $res[0]['tipo'];
//FIM BUSCAR ÚLTIMA MOVIMENTAÇÃO 

//BUSCAR CONTAS A PAGAR VENCENDO HOJE
$query = $pdo->query("SELECT * FROM contas_pagar WHERE vencimento = curDate() AND pago != 'Sim' ORDER BY vencimento ASC LIMIT 1");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valorVenceHoje = $res[0]['valor'];
$descricaoVenceHoje = $res[0]['descricao'];
$valorVenceHoje = number_format($valorVenceHoje, 2, ',', '.');
//FIM BUSCAR CONTAS A PAGAR VENCENDO HOJE

//BUSCAR CONTAS A PAGAR VENCIDAS
$query = $pdo->query("SELECT * FROM contas_pagar WHERE vencimento < curDate() AND pago != 'Sim' ORDER BY vencimento ASC LIMIT 1");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valorVencida = $res[0]['valor'];
$descricaoVencida = $res[0]['descricao'];
$valorVencida = number_format($valorVencida, 2, ',', '.');
//FIM BUSCAR CONTAS A PAGAR VENCIDAS

//BUSCAR CONTAS A RECEBER VENCENDO HOJE
$query = $pdo->query("SELECT * FROM contas_receber WHERE vencimento = curDate() AND pago != 'Sim' ORDER BY vencimento ASC LIMIT 1");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valorRecebeVenceHoje = $res[0]['valor'];
$descricaoRecebeVenceHoje = $res[0]['descricao'];
$valorRecebeVenceHoje = number_format($valorRecebeVenceHoje, 2, ',', '.');
//FIN BUSCAR CONTAS A RECEBER VENCENDO HOJE

//BUSCAR CONTAS A RECEBER VENCIDAS
$query = $pdo->query("SELECT * FROM contas_receber WHERE vencimento < curDate() AND pago != 'Sim' ORDER BY vencimento ASC LIMIT 1");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valorRecebeVencida = $res[0]['valor'];
$descricaoRecebeVencida = $res[0]['descricao'];
$valorRecebeVencida = number_format($valorRecebeVencida, 2, ',', '.');
//FIN BUSCAR CONTAS A RECEBER VENCIDAS

// TOTAL DE CONTAS A PAGAR NO MÊS
$query = $pdo->query("SELECT * FROM contas_pagar WHERE data_conta >= '$dataInicioMes' AND data_conta <= curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_mes = @count($res);
$totalPagarMes = 0;
foreach ($res as $row) {
    $totalPagarMes += $row['valor'];
}
$totalPagarMes = number_format($totalPagarMes, 2, ',', '.');
// FIM TOTAL DE CONTAS A PAGAR NO MÊS

// TOTAL DE CONTAS A RECEBER NO MÊS
$query = $pdo->query("SELECT * FROM contas_receber WHERE data_compra >= '$dataInicioMes' AND data_compra <= curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_receber_mes = @count($res);
$totalReceberMes = 0;
foreach ($res as $row) {
    $totalReceberMes += $row['valor'];
}
$totalReceberMes = number_format($totalReceberMes, 2, ',', '.');
// FIM TOTAL DE CONTAS A RECEBER NO MÊS

// TOTAL DE VENDAS NO MÊS
$query = $pdo->query("SELECT * FROM vendas WHERE data_venda >= '$dataInicioMes' AND data_venda <= curDate()");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$vendasMes = @count($res);
$totalvendasMes = 0;
foreach ($res as $row) {
    $totalvendasMes += $row['valor_recebido'];
}
$totalvendasMes = number_format($totalvendasMes, 2, ',', '.');
// FIM TOTAL DE VENDAS NO MÊS
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Painel Adm</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">

    <div class="container-fluid">
        <section id="minimal-statistics">
            <div class="row">
                <div class="col-12 mt-3 mb-1">
                    <h4 class="text-uppercase">Estatísticas do Painel Financeiro</h4>
                    <p>Entradas, Saídas e Movimentação.</p>
                </div>
            </div>

            <div class="row mb-4">

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=movimentacoes'">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-cash text-success fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <span class="text-success">
                                            <h3>R$ <?php echo $entradasDia ?> </h3>
                                        </span>
                                        <span>Entradas do dia</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=movimentacoes'">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-cash text-danger fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <span class="text-danger">
                                            <h3>R$ <?php echo $saidasDia ?> </h3>
                                        </span>
                                        <span>Saídas do Dia</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=movimentacoes'">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <?php if ($saldoDia < 0) {
                                        echo '<div class="align-self-center col-3">';
                                        echo '<i class="bi bi-cash-stack text-danger fs-1 float-start"></i>';
                                        echo '</div>';
                                        echo '<div class="col-9 text-end">';
                                        echo '<span class="text-danger"><h3>R$ ' . $saidasDia . '</h3></span>'; // Aqui está o ajuste correto
                                        echo '<span>Saldo do dia</span>';
                                        echo '</div>';
                                    } else if ($saldoDia == 0) {
                                        echo '<div class="align-self-center col-3">';
                                        echo '<i class="bi bi-cash-stack text-primary fs-1 float-start"></i>';
                                        echo '</div>';
                                        echo '<div class="col-9 text-end">';
                                        echo '<span class="text-primary"><h3>R$ ' . $saldoDia . '</h3></span>'; // Correção aqui também
                                        echo '<span>Saldo do dia</span>';
                                        echo '</div>';
                                    } else {
                                        echo '<div class="align-self-center col-3">';
                                        echo '<i class="bi bi-cash-stack text-success fs-1 float-start"></i>';
                                        echo '</div>';
                                        echo '<div class="col-9 text-end">';
                                        echo '<span class="text-success"><h3>R$ ' . $saldoDia . '</h3></span>'; // Correção aqui também
                                        echo '<span>Saldo do dia</span>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=movimentacoes'">
                        <div class="card-content">
                            <div class="card-body">
                                <?php if ($tipoMov != "Entrada") {
                                    echo '<div class="align-self-center col-3">';
                                    echo '<i class="bi bi-exclamation-triangle text-danger fs-1 float-start"></i>';
                                    echo '</div>';
                                    echo '<div class="col-9 text-end">';
                                    echo '<span class="text-danger"><h3>R$ ' . @$valorMov . '</h3></span>'; // Aqui está o ajuste correto
                                    echo '<span class="text-danger">' . $descricaoMov . '</span><br>';
                                    echo '<span>Última movimentação</span>';
                                    echo '</div>';
                                } else {
                                    echo '<div class="align-self-center col-3">';
                                    echo '<i class="bi bi-exclamation-triangle text-success fs-1 float-start"></i>';
                                    echo '</div>';
                                    echo '<div class="col-9 text-end">';
                                    echo '<span class="text-success"><h3>R$ ' . @$valorMov . '</h3></span>'; // Aqui está o ajuste correto
                                    echo '<span class="text-success">' . $descricaoMov . '</span><br>';
                                    echo '<span>Última movimentação</span>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mb-4">

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=contas_pagar_hoje'">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-calendar2-x fs-1 float-start" style="color: #cc7722;"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <span style="color: #cc7722;">
                                            <h3>R$ <?php echo $valorVenceHoje ?> </h3>
                                        </span>
                                        <span style="color: #cc7722;"><?php echo $descricaoVenceHoje ?></span><br>
                                        <span>Vencendo hoje</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=contas_pagar_vencidas'">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-calendar2-x text-danger fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <span class="text-danger">
                                            <h3>R$ <?php echo $valorVencida ?> </h3>
                                        </span>
                                        <span class="text-danger"><?php echo $descricaoVencida ?></span><br>
                                        <span>Vencidas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=contas_receber_hoje'">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-calendar-check text-primary fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <span class="text-primary">
                                            <h3>R$ <?php echo $valorRecebeVenceHoje ?> </h3>
                                        </span>
                                        <span class="text-primary"><?php echo $descricaoRecebeVenceHoje ?></span><br>
                                        <span>Á receber, vencendo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=contas_receber_vencidas'">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="align-self-center col-3">
                                    <i class="bi bi-calendar-check text-success fs-1 float-start"></i>
                                </div>
                                <div class="col-9 text-end">
                                    <span class="text-success">
                                        <h3>R$ <?php echo $valorRecebeVencida ?> </h3>
                                    </span>
                                    <span class="text-success"><?php echo $descricaoRecebeVencida ?></span><br>
                                    <span>Á receber, vencidas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <section id="stats-subtitle">
            <div class="row">
                <div class="col-12 mt-3 mb-1">
                    <h4 class="text-uppercase">Saldos por período</h4>
                    <p>Estatísticas Mensais.</p>
                </div>
            </div>

            <div class="row mb-4">

                <!-- SALDO MENSAL -->
                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=movimentacoes'">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="row media align-items-stretch">
                                    <?php if (@$saldoMes < 0) {
                                        echo '<div class="align-self-center col-1">';
                                        echo '<i class="bi bi-calendar2-x text-danger fs-1 mr-2"></i>';
                                        echo '</div>';
                                        echo '<div class="media-body col-6">';
                                        echo '<h4>Saldo Mensal</h4>';
                                        echo '<span>Total do Mês</span>';
                                        echo '</div>';
                                        echo '<div class="text-end col-5">';
                                        echo '<span class="text-danger"><h2>R$ ' . @$saldoMes . '</h2></span>';
                                        echo '</div>';
                                    } else if (@$saldoMes == 0) {
                                        echo '<div class="align-self-center col-1">';
                                        echo '<i class="bi bi-calendar text-primary fs-1 mr-2"></i>';
                                        echo '</div>';
                                        echo '<div class="media-body col-6">';
                                        echo '<h4>Saldo Mensal</h4>';
                                        echo '<span>Total do Mês</span>';
                                        echo '</div>';
                                        echo '<div class="text-end col-5">';
                                        echo '<span class="text-primary"><h2>R$ ' . @$saldoMes . '</h2></span>';
                                        echo '</div>';
                                    } else {
                                        echo '<div class="align-self-center col-1">';
                                        echo '<i class="bi bi-calendar-check text-success fs-1 mr-2"></i>';
                                        echo '</div>';
                                        echo '<div class="media-body col-6">';
                                        echo '<h4>Saldo Mensal</h4>';
                                        echo '<span>Total do Mês</span>';
                                        echo '</div>';
                                        echo '<div class="text-end col-5">';
                                        echo '<span class="text-success"><h2>R$ ' . @$saldoMes . '</h2></span>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SALDO MENSAL -->

                <!-- TOTAL CONTAS A PAGAR NO MÊS -->
                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=contas_pagar'">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="row media align-items-stretch">
                                    <div class="align-self-center col-1">
                                        <i class="bi bi-calendar4-event text-danger fs-1 mr-2"></i>
                                    </div>
                                    <div class="media-body col-6">
                                        <h4>Pagamento de Contas</h4>
                                        <span>Total de <?php echo $contas_pagar_mes ?> contas no Mês</span>
                                    </div>
                                    <div class="text-end col-5">
                                        <span class="text-danger">
                                            <h2>R$ <?php echo $totalPagarMes ?></h2>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIM TOTAL CONTAS A PAGAR NO MÊS -->

            </div>

            <div class="row mb-4">
                <!-- TOTAL CONTAS A RECEBER NO MÊS -->
                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=contas_receber'">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="row media align-items-stretch">
                                    <div class="align-self-center col-1">
                                        <i class="bi bi-calendar2-minus text-success fs-1 mr-2"></i>
                                    </div>
                                    <div class="media-body col-6">
                                        <h4>Recebimento de Contas</h4>
                                        <span>Total de <?php echo $contas_receber_mes ?> contas no Mês</span>
                                    </div>
                                    <div class="text-end col-5">
                                        <span class="text-success">
                                            <h2>R$ <?php echo $totalReceberMes ?> </h2>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIM TOTAL CONTAS A RECEBER NO MÊS -->

                <!-- TOTAL DE VENDAS -->
                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=vendas'">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="row media align-items-stretch">
                                    <div class="align-self-center col-1">
                                        <i class="bi bi-calendar-minus text-success fs-1 mr-2"></i>
                                    </div>
                                    <div class="media-body col-6">
                                        <h4>Vendas</h4>
                                        <span>Total de <?php echo $vendasMes ?> vendas no Mês</span>
                                    </div>
                                    <div class="text-end col-5">
                                        <span class="text-success">
                                            <h2>R$ <?php echo $totalvendasMes ?></h2>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FIM TOTAL DE VENDAS -->

            </div>

        </section id="stats-subtitle">
        <div class="row mb-2">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Gráfico</h4>
            </div>
        </div>

        <style type="text/css">
            #principal {
                width: 500px;
                height: 100%;
                margin-left: 10px;
                font-family: Verdana, Helvetica, sans-serif;
                font-size: 14px;

            }

            #barra {
                margin: 0 2px;
                vertical-align: bottom;
                display: inline-block;

            }

            .cor1,
            .cor2,
            .cor3,
            .cor4 {
                color: #FFF;
                padding: 5px;
            }

            .cor1 {
                background-color: #FF0000;
            }

            .cor2 {
                background-color: #0000FF;
            }

            .cor3 {
                background-color: #FF6600;
            }

            .cor4 {
                background-color: #009933;
            }
        </style>

        <?php
        // definindo porcentagem
        $height1 = '28px';
        $height2 = '49px';
        $height3 = '33px';
        $height4 = '13px';
        $total  = 4; // total de barras
        ?>
        <div id="principal">
            <p>Vendas anuais</p>
            <?php
            for ($i = 1; $i <= $total; $i++) {
                $height = ${'height' . $i};
            ?>
                <div id="barra">
                    <div class="cor<?php echo $i ?>" style="height:<?php echo $height ?>"> <?php echo $height ?> </div>
                </div>
            <?php } ?>
        </div>
        <section>

        </section>

    </div>
</body>

</html>