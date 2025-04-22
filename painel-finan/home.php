<?php

require_once('../conexao.php');
require_once('verificar-permissao.php');

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Painel Adm</title>
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
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-6">
                                        <i class="bi bi-cash text-success fs-1 float-start"></i>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h3>R$ <?php echo @$entradasDia ?></h3>
                                        <span>Entradas do dia</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-6">
                                        <i class="bi bi-cash text-danger fs-1 float-start"></i>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h3>R$ <?php echo @$saidasDia ?></h3>
                                        <span>Saídas do Dia</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-6">
                                        <i class="bi bi-cash-stack text-primary fs-1 float-start"></i>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h3>R$ <?php echo @$saldoDia ?></h3>
                                        <span>Saldo do Dia</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-6">
                                        <i class="bi bi-calendar-check text-success fs-1 float-start"></i>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h3>R$ <?php echo @$saldoMesF ?></h3>
                                        <span>Saldo do Mês</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>



            <div class="row mb-4">

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-6">
                                        <i class="bi-pencil-square text-primary fs-1 float-start"></i>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h3>278</h3>
                                        <span>New Posts</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-6">
                                        <i class="bi-pencil-square text-primary fs-1 float-start"></i>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h3>278</h3>
                                        <span>New Posts</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-6">
                                        <i class="bi-pencil-square text-primary fs-1 float-start"></i>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h3>278</h3>
                                        <span>New Posts</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="align-self-center col-6">
                                        <i class="bi-pencil-square text-primary fs-1 float-start"></i>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h3>278</h3>
                                        <span>New Posts</span>
                                    </div>
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
                    <h4 class="text-uppercase">Statistics With Subtitle</h4>
                    <p>Statistics on minimal cards with Title &amp; Sub Title.</p>
                </div>
            </div>

            <div class="row mb-4">

                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="row media align-items-stretch">
                                    <div class="align-self-center col-1">
                                        <i class="bi-pencil-square text-primary fs-1 mr-2"></i>
                                    </div>
                                    <div class="media-body col-6">
                                        <h4>Total Posts</h4>
                                        <span>Monthly blog posts</span>
                                    </div>
                                    <div class="text-end col-5">
                                        <h1>18,000</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="row media align-items-stretch">
                                    <div class="align-self-center col-1">
                                        <i class="bi-pencil-square text-primary fs-1 mr-2"></i>
                                    </div>
                                    <div class="media-body col-6">
                                        <h4>Total Posts</h4>
                                        <span>Monthly blog posts</span>
                                    </div>
                                    <div class="text-end col-5">
                                        <h1>18,000</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row mb-4">

                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="row media align-items-stretch">
                                    <div class="align-self-center col-1">
                                        <i class="bi-pencil-square text-primary fs-1 mr-2"></i>
                                    </div>
                                    <div class="media-body col-6">
                                        <h4>Total Posts</h4>
                                        <span>Monthly blog posts</span>
                                    </div>
                                    <div class="text-end col-5">
                                        <h1>18,000</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-content">
                            <div class="card-body cleartfix">
                                <div class="row media align-items-stretch">
                                    <div class="align-self-center col-1">
                                        <i class="bi-pencil-square text-primary fs-1 mr-2"></i>
                                    </div>
                                    <div class="media-body col-6">
                                        <h4>Total Posts</h4>
                                        <span>Monthly blog posts</span>
                                    </div>
                                    <div class="text-end col-5">
                                        <h1>18,000</h1>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </section>
    </div>
</body>

</html>