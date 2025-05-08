<?php

require_once('../conexao.php');
require_once('verificar-permissao.php');

$hoje = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$dataInicioMes = $ano_atual . "-" . $mes_atual . "-01";

// TOTAL DE PRODUTOS
$query_prod = $pdo->query("SELECT * FROM produtos");
$res_prod = $query_prod->fetchAll(PDO::FETCH_ASSOC);
$total_prod = count($res_prod);
// FIM DO TOTAL DE PRODUTOS

// ESTOQUE MÍNIMO PARA PRODUTOS
$query_min = $pdo->query("SELECT * FROM produtos WHERE estoque <= '$nivelEstoqueMinimo'");
$res_min = $query_min->fetchAll(PDO::FETCH_ASSOC);
$total_min = count($res_min);
// FIM DO ESTOQUE MÍNIMO PARA PRODUTOS

// TOTAL DE FORNECEDORES    
$query_for = $pdo->query("SELECT * FROM fornecedores");
$res_for = $query_for->fetchAll(PDO::FETCH_ASSOC);
$total_for = count($res_for);
// FIM DO TOTAL DE FORNECEDORES 

// VENDAS DO DIA
$query_dia = $pdo->query("SELECT * FROM vendas WHERE data_venda = '$hoje' AND status_venda = 'Fechada'");
$res_dia = $query_dia->fetchAll(PDO::FETCH_ASSOC);
$vendasDia = @count($res_dia);
$totalVendasDia = 0;
foreach ($res_dia as $row_dia) {
    $totalVendasDia += $row_dia['valor'];
}
$totalVendasDia = number_format($totalVendasDia, 2, ',', '.');

$query_itens = $pdo->query("SELECT * FROM itens_venda WHERE data_venda = '$hoje'");
$res_itens = $query_itens->fetchAll(PDO::FETCH_ASSOC);
$total_itens = count($res_itens);
// FIM VENDAS DO DIA

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
$valorVenceHoje = @$res[0]['valor'];
$descricaoVenceHoje = @$res[0]['descricao'];
$valorVenceHoje = number_format($valorVenceHoje, 2, ',', '.');
//FIM BUSCAR CONTAS A PAGAR VENCENDO HOJE

//BUSCAR CONTAS A PAGAR VENCIDAS
$query = $pdo->query("SELECT * FROM contas_pagar WHERE vencimento < curDate() AND pago != 'Sim' ORDER BY vencimento ASC LIMIT 1");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valorVencida = @$res[0]['valor'];
$descricaoVencida = @$res[0]['descricao'];
$valorVencida = number_format($valorVencida, 2, ',', '.');
//FIM BUSCAR CONTAS A PAGAR VENCIDAS

//BUSCAR CONTAS A RECEBER VENCENDO HOJE
$query = $pdo->query("SELECT * FROM contas_receber WHERE vencimento = curDate() AND pago != 'Sim' ORDER BY vencimento ASC LIMIT 1");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valorRecebeVenceHoje = @$res[0]['valor'];
$descricaoRecebeVenceHoje = @$res[0]['descricao'];
$valorRecebeVenceHoje = number_format($valorRecebeVenceHoje, 2, ',', '.');
//FIN BUSCAR CONTAS A RECEBER VENCENDO HOJE

//BUSCAR CONTAS A RECEBER VENCIDAS
$query = $pdo->query("SELECT * FROM contas_receber WHERE vencimento < curDate() AND pago != 'Sim' ORDER BY vencimento ASC LIMIT 1");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$valorRecebeVencida = @$res[0]['valor'];
$descricaoRecebeVencida = @$res[0]['descricao'];
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
$query = $pdo->query("SELECT * FROM vendas WHERE data_venda >= '$dataInicioMes' AND data_venda <= curDate() AND status_venda = 'Fechada'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$vendasMes = @count($res);
$totalvendasMes = 0;
foreach ($res as $row) {
    $totalvendasMes += $row['valor_recebido'];
}
$totalvendasMes = number_format($totalvendasMes, 2, ',', '.');
// FIM TOTAL DE VENDAS NO MÊS

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
                    <h4 class="text-uppercase">Estatísticas</h4>
                </div>
            </div>

            <div class="row mb-4">

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=produtos'">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-archive-fill text-success fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <span class="text-success">
                                            <h3><?php echo $total_prod ?> </h3>
                                        </span>
                                        <br>
                                        <span>Produtos</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=estoque'">
                        <div class="card-content">
                            <div class="card-body">
                                <?php if (@$total_min <= 0) {
                                    echo '<div class="row">';
                                    echo '<div class="align-self-center col-3">';
                                    echo '<i class="bi bi-archive text-danger fs-1 float-start"></i>';
                                    echo '</div>';
                                    echo '<div class="col-9 text-end">';
                                    echo '<span class="text-danger"><h3>' . @$total_min . '</h3></span>';
                                    echo '<br>';
                                    echo '<span>Estoque Baixo</span><br>';
                                    echo '</div>';
                                    echo '</div>';
                                } else {
                                    echo '<div class="row">';
                                    echo '<div class="align-self-center col-3">';
                                    echo '<i class="bi bi-archive text-success fs-1 float-start"></i>';
                                    echo '</div>';
                                    echo '<div class="col-9 text-end">';
                                    echo '<span class="text-success"><h3>' . @$total_min . '</h3></span>';
                                    echo '<br>';
                                    echo '<span>Estoque Baixo</span><br>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=fornecedores'">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-3">
                                        <i class="bi bi-box-seam text-primary fs-1 float-start"></i>
                                    </div>
                                    <div class="col-9 text-end">
                                        <span class="text-primary">
                                            <h3><?php echo $total_for ?></h3>
                                        </span>
                                        <br>
                                        <span>Fornecedores</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card"
                        style="background-color: lightgray;"
                        onclick="window.location.href='index.php?pagina=vendas'">
                        <div class="card-content">
                            <div class="card-body">
                                <?php if (@$totalVendasDia < 0) {
                                    echo '<div class="row">';
                                    echo '<div class="align-self-center col-3">';
                                    echo '<i class="bi bi-cash-stack text-danger fs-1 float-start"></i>';
                                    echo '</div>';
                                    echo '<div class="col-9 text-end">';
                                    echo '<span class="text-danger"><h3>R$ ' . @$totalVendasDia . '</h3></span>'; // Aqui está o ajuste correto
                                    echo '<span>Vendas do Dia</span><br>';
                                    echo '<span>' . sprintf("%03d", $total_itens) . ' produto(s) vendido(s)</span>';
                                    echo '</div>';
                                    echo '</div>';
                                } else if (@$totalVendasDia == 0) {
                                    echo '<div class="row">';
                                    echo '<div class="align-self-center col-3">';
                                    echo '<i class="bi bi-cash-stack text-primary fs-1 float-start"></i>';
                                    echo '</div>';
                                    echo '<div class="col-9 text-end">';
                                    echo '<span class="text-primary"><h3>R$ ' . @$totalVendasDia . '</h3></span>'; // Aqui está o ajuste correto
                                    echo '<span>Vendas do Dia</span><br>';
                                    echo '<span>' . sprintf("%03d", $total_itens) . ' produto(s) vendido(s)</span>';
                                    echo '</div>';
                                    echo '</div>';
                                } else {
                                    echo '<div class="row">';
                                    echo '<div class="align-self-center col-3">';
                                    echo '<i class="bi bi-cash-stack text-success fs-1 float-start"></i>';
                                    echo '</div>';
                                    echo '<div class="col-9 text-end">';
                                    echo '<span class="text-success"><h3>R$ ' . @$totalVendasDia . '</h3></span>'; // Aqui está o ajuste correto
                                    echo '<span>Vendas do Dia</span><br>';
                                    echo '<span>' . sprintf("%03d", $total_itens) . ' produto(s) vendidos(s)</span>';
                                    echo '</div>';
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
                        onclick="alert('Avisar financeiro sobre contas vencendo!!');">
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
                        onclick="alert('Avisar financeiro sobre contas vencidas!!');">
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
                        onclick="alert('Avisar financeiro sobre contas a receber Hoje!!');">
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
                        onclick="alert('Avisar financeiro sobre contas já vencidas!!');">
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

        </section>

        <section id="stats-subtitle">
            <div class="row mb-2">
                <div class="col-12 mt-3 mb-1">
                    <h4 class="text-uppercase">Gráfico</h4>
                    <p style="font-weight: bold;">Vendas do ano de <?php echo $ano_atual ?></p>
                </div>
            </div>

            <style type="text/css">
                #principal {
                    display: flex;
                    align-items: flex-end;
                    gap: 10px;
                    width: 100%;
                    height: 100%;
                    margin-left: 10px;
                    font-family: Verdana, Helvetica, sans-serif;
                    font-size: 14px;
                }

                #barra {
                    margin: 0 2px;
                    vertical-align: bottom;
                    display: flex;
                    flex-direction: column-reverse;
                    align-items: center;
                    width: 120px;
                    padding: 20px;
                }

                .cor1,
                .cor2,
                .cor3,
                .cor4,
                .cor5,
                .cor6,
                .cor7,
                .cor8,
                .cor9,
                .cor10,
                .cor11,
                .cor12 {
                    color: #FFF;
                    padding: 5px;
                    width: 100px;
                }

                .cor1 {
                    background-color: #FF0000;
                    color: black;
                }

                .cor2 {
                    background-color: #0000FF;
                    color: black;
                }

                .cor3 {
                    background-color: #FF6600;
                    color: black;
                }

                .cor4 {
                    background-color: #009933;
                    color: black;
                }

                .cor5 {
                    background-color: rgb(145, 211, 142);
                    color: black;
                }
                
                .cor6 {
                    background-color: #505050;
                    color: black;
                }
                
                .cor7 {
                    background-color: #000000;
                    color: black;
                }
                
                .cor8 {
                    background-color: #9c14d1;
                    color: black;
                }
                
                .cor9 {
                    background-color: #085d84;
                    color: black;
                }
                
                .cor10 {
                    background-color: #B84848;
                    color: black;
                }
                
                .cor11 {
                    background-color: #49B8E0;
                    color: black;
                }
                
                .cor12 {
                    background-color: rgb(79, 60, 107);
                    color: black;
                }
            </style>

            <div id="principal">

                <?php
                // BUSCAR TOTAL DE VENDAS NOS MESES DO ANO
                $total  = 12; // total de barras
                $escala = 2;
                $meses = [
                    1 => "Jan",
                    2 => "Fev",
                    3 => "Mar",
                    4 => "Abr",
                    5 => "Mai",
                    6 => "Jun",
                    7 => "Jul",
                    8 => "Ago",
                    9 => "Set",
                    10 => "Out",
                    11 => "Nov",
                    12 => "Dez"
                ];
                for ($altura = 1; $altura < 13; $altura++) {
                    $inicioMes = $ano_atual . "-" . $altura . "-01";
                    $fimMes = $ano_atual . "-" . $altura . "-31";
                    $TotalVendidoMes = 0;
                    $queryMesAno = $pdo->query("SELECT SUM(valor) AS total_vendido FROM vendas 
                            WHERE data_venda BETWEEN '$inicioMes' AND '$fimMes' 
                            AND status_venda = 'Fechada'");
                    $resMesAno = $queryMesAno->fetch(PDO::FETCH_ASSOC);
                    $TotalVendidoMes = $resMesAno['total_vendido'] ?? 0; // Se for NULL, define como 0
                ?>
                    <div id="barra">
                        <div class="cor<?php echo $altura ?>" style="height:<?php echo ($TotalVendidoMes / 50) + 20 ?>px">
                            R$ <?php echo number_format($TotalVendidoMes, 2, ',', '.') ?>
                        </div>
                        <div><?php echo $meses[$altura] ?></div>
                    </div>
                <?php } ?>
        </section>
    </div>
</body>

</html>