<?php
require_once("../conexao.php");

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = strtoupper(iconv('ISO-8859-1', 'UTF-8', strftime('%A, %d de %B de %Y', strtotime('today'))));

$id = $_GET['id'];


$query = $pdo->query("SELECT * FROM caixa WHERE id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$status = $res[0]['status_caixa'];
$data = $res[0]['data_ab'];
$hora = $res[0]['hora_ab'];
$id_gerente = $res[0]['gerente_ab'];
$abertura = $res[0]['valor_ab'];
$data_fecha = $res[0]['data_fec'];
$hora_fecha = $res[0]['hora_fec'];
$id_gt_fecha = $res[0]['gerente_fec'];
$valor_fecha = $res[0]['valor_fec'];
$valor = $res[0]['valor_vendido'];
$quebra = $res[0]['valor_quebra'];
$caixa = $res[0]['caixa'];
$id_operador = $res[0]['operador'];
$id = $res[0]['id'];

$query_usu = $pdo->query("SELECT * FROM usuarios where id = '$id_operador'");
$res_usu = $query_usu->fetchAll(PDO::FETCH_ASSOC);
$nome_usu = $res_usu[0]['nome'];

$query_gt = $pdo->query("SELECT * FROM usuarios where id = '$id_gerente'");
$res_gt = $query_gt->fetchAll(PDO::FETCH_ASSOC);
$nome_gt = $res_gt[0]['nome'];

$query_fecha = $pdo->query("SELECT * FROM usuarios where id = '$id_gt_fecha'");
$res_fecha = $query_fecha->fetchAll(PDO::FETCH_ASSOC);
@$nome_gt_fecha = $res_fecha[0]['nome'];

if ($res[0]['status_caixa'] == 'Aberto') {
	$foto = 'verde.jpg';
} else if ($res[0]['status_caixa'] == 'Fechado') {
	$foto = 'vermelho.jpg';
}

$data = implode('/', array_reverse(explode('-', $data)));
$data_fecha = implode('/', array_reverse(explode('-', $data_fecha)));
$valor = number_format($valor, 2, ',', '.');
$quebra = number_format($quebra, 2, ',', '.');
$abertura = number_format($abertura, 2, ',', '.');
$valor_fecha = number_format($valor_fecha, 2, ',', '.');
$caixa = str_pad($caixa, 3, '0', STR_PAD_LEFT);

?>

<!DOCTYPE html>
<html>

<head>
	<title>Fluxo de Caixa</title>
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
			<span class="titulorel">Fluxo de Caixa</span><br>
		</div>

		<hr>

		<div class="row">
			<div class="col-sm-6 d-flex justify-content-start">
				<span><strong> Caixa </strong></span>
				<span class="ms-2"><?php echo $caixa ?> - <?php echo $status ?></span>
			</div>

			<div class="col-sm-6 d-flex justify-content-center">
				<span><strong> Operador </strong></span>
				<span class="ms-2"><?php echo $nome_usu ?></span>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 d-flex justify-content-center text-danger">
				<span><strong> Valor Quebra </strong></span>
				<span class="ms-2">R$ <?php echo $quebra ?></span>
			</div>

			<div class="col-sm-6 d-flex justify-content-end text-success">
				<span><strong> Total Vendido </strong></span>
				<span class="ms-2">R$ <?php echo $valor ?></span>
			</div>
		</div>

		<hr>
		
		<div class="row">
			<div class="col-sm-6 d-flex justify-content-start">
				<span><strong> Data Abertura </span>
				<span class="ms-2"><?php echo $data ?></span>
			</div>

			<div class="col-sm-6 d-flex justify-content-center">
				<span><strong> Hora Abertura </strong></span>
				<span class="ms-2"><?php echo $hora ?></span>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 d-flex justify-content-center">
				<span><strong> Responsável </strong></span>
				<span class="ms-2"><?php echo $nome_gt ?></span>
			</div>

			<div class="col-sm-6 d-flex justify-content-end">
				<span><strong> Valor da Abertura </strong></span>
				<span class="ms-2">R$ <?php echo $abertura ?></span>
			</div>
		</div>

		<hr>

		<hr>
		
		<div class="row">
			<div class="col-sm-6 d-flex justify-content-start">
				<span><strong> Data Fechamento </span>
				<span class="ms-2"><?php echo $data_fecha ?></span>
			</div>

			<div class="col-sm-6 d-flex justify-content-center">
				<span><strong> Hora Fechamento </strong></span>
				<span class="ms-2"><?php echo $hora_fecha ?></span>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 d-flex justify-content-center">
				<span><strong> Responsável </strong></span>
				<span class="ms-2"><?php echo $nome_gt_fecha ?></span>
			</div>

			<div class="col-sm-6 d-flex justify-content-end">
				<span><strong> Valor do Fechamento </strong></span>
				<span class="ms-2">R$ <?php echo $valor_fecha ?></span>
			</div>
		</div>

		<hr>

		<table class='table' width='100%' cellspacing='0' cellpadding='3'>
			<tr style="background-color: #f9f9f9;">

				<th>Aberto</th>
				<th>Data</th>
				<th>Hora</th>
				<th>Pagamento</th>

			</tr>
			<?php
			$query = $pdo->query("SELECT * FROM vendas WHERE abertura = '$id' ORDER BY id DESC");
			$res = $query->fetchAll(PDO::FETCH_ASSOC);
			$totalItens = @count($res);

			for ($i = 0; $i < @count($res); $i++) {
				foreach ($res[$i] as $key => $value) {
				}
				$status = $res[$i]['status_venda'];
				$data = $res[$i]['data_venda'];
				$hora = $res[$i]['hora'];
				$id_pagamento = $res[$i]['forma_pgto'];
				$id = $res[$i]['id'];

				if ($res[$i]['status_venda'] == 'Fechada') {
					$foto = 'verde.jpg';
				} else if ($res[$i]['status_venda'] == 'Aberta') {
					$foto = 'vermelho.jpg';
				} else if ($res[$i]['status_venda'] == 'Cancelada') {
					$foto = 'amarelo.jpg';
				}

				$data = implode('/', array_reverse(explode('-', $data)));

				$query_pag = $pdo->query("SELECT * FROM forma_pgtos WHERE id = '$id_pagamento'");
				$res_pag = $query_pag->fetchAll(PDO::FETCH_ASSOC);
				$nome_pag = $res_pag[0]['nome'];
			?>

				<tr>

					<td><img src="<?php echo $url_sistema ?>assets/img/<?php echo $foto ?>" width="13px"> </td>
					<td><?php echo $data ?> </td>
					<td><?php echo $hora ?> </td>
					<td><?php echo $nome_pag ?> </td>

				</tr>
			<?php } ?>

		</table>

		<hr>

		<hr>

	</div>

	<div class="footer">
		<p style="font-size:14px; text-align: center"><?php echo $rodape_relatorios ?></p>
	</div>

</body>

</html>