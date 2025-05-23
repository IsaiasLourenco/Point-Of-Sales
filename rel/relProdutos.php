<?php
require_once("../conexao.php");

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = strtoupper(iconv('ISO-8859-1', 'UTF-8', strftime('%A, %d de %B de %Y', strtotime('today'))));
?>

<!DOCTYPE html>
<html>

<head>
    <title>Catálogo de Produtos</title>
    <!-- FavIcon -->
    <link rel="shortcut icon" href="<?php echo $url_sistema ?>assets/img/ico.ico" type="image/x-icon">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>
        @page {
            margin: 0px;

        }

        .footer {
            margin-top: 20px;
            width: 100%;
            background-color: #ebebeb;
            padding: 10px;
            position: absolute;
            bottom: 0;
        }

        .cabecalho {
            background-color: #ebebeb;
            padding: 10px;
            margin-bottom: 30px;
            width: 100%;
            height: 100px;
        }

        .titulo {
            margin: 0;
            font-size: 28px;
            font-family: Arial, Helvetica, sans-serif;
            color: #6e6d6d;

        }

        .subtitulo {
            margin: 0;
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            color: #6e6d6d;
        }

        .areaTotais {
            border: 0.5px solid #bcbcbc;
            padding: 15px;
            border-radius: 5px;
            margin-right: 25px;
            margin-left: 25px;
            position: absolute;
            right: 20;
        }

        .areaTotal {
            border: 0.5px solid #bcbcbc;
            padding: 15px;
            border-radius: 5px;
            margin-right: 25px;
            margin-left: 25px;
            background-color: #f9f9f9;
            margin-top: 2px;
        }

        .pgto {
            margin: 1px;
        }

        .fonte13 {
            font-size: 13px;
        }

        .esquerda {
            display: inline;
            width: 50%;
            float: left;
        }

        .direita {
            display: inline;
            width: 50%;
            float: right;
        }

        .table {
            padding: 15px;
            font-family: Verdana, sans-serif;
            margin-top: 20px;
        }

        .texto-tabela {
            font-size: 12px;
        }

        .esquerda_float {

            margin-bottom: 10px;
            float: left;
            display: inline;
        }

        .titulos {
            margin-top: 10px;
        }

        .image {
            margin-top: -10px;
        }

        .margem-direita {
            margin-right: 80px;
        }

        .margem-direita50 {
            margin-right: 50px;
        }

        hr {
            margin: 8px;
            padding: 1px;
        }

        .titulorel {
            margin: 0;
            font-size: 25px;
            font-family: Arial, Helvetica, sans-serif;
            color: #6e6d6d;

        }

        .margem-superior {
            margin-top: 30px;
        }

        .areaSubtituloCab {
            margin-top: 15px;
            margin-bottom: 15px;
        }
    </style>

</head>

<body>

    <?php if ($cabecalho_img_rel == 'Sim') { ?>
        <div class="img-cabecalho">
            <img src="<?php echo $url_sistema ?>assets/img/topo.jpg" width="100%">
        </div>
    <?php } else { ?>
        <div class="cabecalho">

            <div class="row titulos">
                <div class="col-sm-2 esquerda_float image">
                    <img src="<?php echo $url_sistema ?>assets/img/logo.jpg" width="90px">
                </div>
                <div class="col-sm-10 esquerda_float">
                    <h2 class="titulo"><b><?php echo strtoupper($nome_sistema) ?></b></h2>

                    <div class="areaSubtituloCab">
                        <h6 class="subtitulo"><?php echo $endereco_sistema . ' Tel: ' . $telefone_sistema  ?></h6>

                        <p class="subtitulo"><?php echo $data_hoje ?></p>
                    </div>

                </div>
            </div>

        </div>
    <?php } ?>

    <div class="container">

        <div style="text-align: center;">
            <span class="titulorel">Catálogo de Produtos </span>
        </div>
        <hr>
        <table class='table' width='100%' cellspacing='0' cellpadding='3'>
            <tr bgcolor='#f9f9f9'>
                <th>Nome</th>
                <th>Estoque</th>
                <th>Valor Venda</th>
                <th>Valor Compra</th>
                <th>Foto</th>

            </tr>
            <?php

            $query = $pdo->query("SELECT * FROM produtos ORDER BY id ASC");
            $res = $query->fetchAll(PDO::FETCH_ASSOC);
            $totalItens = @count($res);
            $totalItens = sprintf('%03d', $totalItens);

            for ($i = 0; $i < @count($res); $i++) {
                foreach ($res[$i] as $key => $value) {
                }
                $nome = $res[$i]['nome'];
                $valor_compra = $res[$i]['valor_compra'];
                $valor_venda = $res[$i]['valor_venda'];
                $estoque = $res[$i]['estoque'];
                $foto = $res[$i]['imagem'];
                $id = $res[$i]['id'];
                $valor_compra = number_format($valor_compra, 2, ',', '.');
                $valor_venda = number_format($valor_venda, 2, ',', '.');
            ?>
                <tr>
                    <td><?php echo $nome ?> </td>
                    <td><?php echo $estoque ?> </td>
                    <td>R$ <?php echo $valor_venda ?> </td>
                    <td>R$ <?php echo $valor_compra ?> </td>
                    <td><img src="<?php echo $url_sistema ?>assets/img/produtos/<?php echo $foto ?>" width="35px"> </td>
                </tr>
            <?php } ?>
        </table>
        <hr>

        <div class="row margem-superior">
            <div class="col-md-12">
                <div style="text-align: right;">

                    <span class=""> <b> Total de Produtos : <?php echo $totalItens ?> </b> </span>
                </div>

            </div>
        </div>

        <hr>

    </div>
    <div class="footer">
        <p style="font-size:14px; text-align:center"><?php echo $rodape_relatorios ?> - https://isaiaslourenco.github.io/vetor256/</p>
    </div>

</body>

</html>