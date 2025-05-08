<?php
require_once("../conexao.php");

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = strtoupper(iconv('ISO-8859-1', 'UTF-8', strftime('%A, %d de %B de %Y', strtotime('today'))));

$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];
$status = $_GET['status'];

$status_like = '%' . $status . '%';

$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

if ($status == 'fechada') {
	$status_serv = 'Fechadas';
} else if ($status == 'aberta') {
	$status_serv = 'Abertas';
} else if ($status == 'cancelada') {
	$status_serv = 'Canceladas';
} else {
	$status_serv = '';
}

if ($dataInicial != $dataFinal) {
	$apuracao = $dataInicialF . ' até ' . $dataFinalF;
} else {
	$apuracao = $dataInicialF;
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Relatório de Vendas</title>
	<link rel="shortcut icon" href="../assets/img/ico.ico" />

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
			<span class="titulorel">Relatório de Vendas <?php echo $status_serv ?> </span>
		</div>

		<hr>

		<small><small>
				<table class='table' width='100%' cellspacing='0' cellpadding='3'>
					<tr style="background-color: #f9f9f9;">
						<th>Status</th>
						<th>Valor</th>
						<th>Data</th>
						<th>Hora</th>
						<th>Operador</th>
						<th>Pagamento</th>

					</tr>
					<?php
					$saldo = 0;
					$query = $pdo->query("SELECT * FROM	vendas WHERE data_venda >= '$dataInicial' AND data_venda <= '$dataFinal' AND status_venda LIKE '$status_like' ORDER BY id DESC");
					$res = $query->fetchAll(PDO::FETCH_ASSOC);
					$totalItens = @count($res);

					for ($i = 0; $i < @count($res); $i++) {
						foreach ($res[$i] as $key => $value) {
						}
						$st_venda = $res[$i]['status_venda'];
						$valor = $res[$i]['valor'];
						$data = $res[$i]['data_venda'];
						$hora = $res[$i]['hora'];
						$id_operador = $res[$i]['operador'];
						$id_pg = $res[$i]['forma_pgto'];

						@$saldo = $saldo + $valor;

						$data = implode('/', array_reverse(explode('-', $data)));

						$query_usu = $pdo->query("SELECT * FROM usuarios where id = '$id_operador'");
						$res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
						$nome_usu = $res_usu[0]['nome'];

						$query_pg = $pdo->query("SELECT * FROM forma_pgtos where id = '$id_pg'");
						$res_pg = $query_pg->fetchAll(PDO::FETCH_ASSOC);
						$nome_pg = $res_pg[0]['nome'];

						if ($st_venda == 'Fechada') {
							$foto = 'verde.jpg';
						} else if ($st_venda == 'Aberta') {
							$foto = 'vermelho.jpg';
						} else if ($st_venda == 'Cancelada') {
							$foto = 'amarelo.jpg';
						}
					?>

						<tr>

							<td><img src="<?php echo $url_sistema ?>assets/img/<?php echo $foto ?>" width="13px"> </td>
							<td>R$ <?php echo number_format($valor, 2, ',', '.') ?> </td>
							<td><?php echo $data ?> </td>
							<td><?php echo $hora ?> </td>
							<td><?php echo $nome_usu ?> </td>
							<td><?php echo $nome_pg ?> </td>

						</tr>
					<?php } ?>

				</table>
			</small></small>
		<hr>

		<div class="row">
			<div class="col-sm-8" style="text-align: left;">
				<span class=""> <b> Período da Apuração </b> </span>

				<span class=""> <?php echo $apuracao ?> </span>
			</div>
			<div class="col-sm-4 direita" style="text-align: right;">
				<span class=""> <b> Total R$ <?php echo number_format($saldo, 2, ',', '.') ?> </b> </span>
			</div>
		</div>

		<hr>

	</div>

	<div class="footer">
		<p style="font-size:14px; text-align:center"><?php echo $rodape_relatorios ?></p>
	</div>

</body>

</html>