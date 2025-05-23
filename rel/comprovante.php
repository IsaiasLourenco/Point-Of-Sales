<?php

include('../conexao.php');

$id = $_GET['id'];

//BUSCAR AS INFORMAÇÕES DO PEDIDO
$res = $pdo->query("SELECT * from vendas where id = '$id' ");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$hora = $dados[0]['hora'];
$total_venda = $dados[0]['valor'];
$valor_recebido = $dados[0]['valor_recebido'];
$tipo_pgto = $dados[0]['forma_pgto'];
$status = $dados[0]['status_venda'];
$troco = $dados[0]['troco'];
$data = $dados[0]['data_venda'];
$desconto = $dados[0]['desconto'];
$operador = $dados[0]['operador'];

$data2 = implode('/', array_reverse(explode('-', $data)));

$res = $pdo->query("SELECT * from forma_pgtos where codigo = '$tipo_pgto' ");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$nome_pgto = $dados[0]['nome'];

$res = $pdo->query("SELECT * from usuarios where id = '$operador' ");
$dados = $res->fetchAll(PDO::FETCH_ASSOC);
$nome_operador = $dados[0]['nome'];
$desconto_valor = floatval(preg_replace('/[^0-9.]/', '', $desconto));
$desconto_formatado = '';
if ($desconto_porcentagem == 'Sim') {
    $desconto_formatado = number_format($desconto_valor, 2, ',', '.') . '%';
} else {
    $desconto_formatado = 'R$ ' . number_format($desconto_valor, 2, ',', '.');
}


?>


<style type="text/css">
	* {
		margin: 0px;
		padding: 5px;
		background-color: #f7fccc;

	}

	.text {
		&-center {
			text-align: center;
		}
	}

	.ttu {
		text-transform: uppercase;
		font-weight: bold;
		font-size: 1.2em;
	}

	.printer-ticket {
		display: table !important;
		width: 100%;
		max-width: 400px;
		font-weight: light;
		line-height: 1.3em;
		padding: 5px;
		font-family: Tahoma, Geneva, sans-serif;
		font-size: 12px;



	}

	th {
		font-weight: inherit;
		padding: 5px;
		text-align: center;
		border-bottom: 1px dashed #BCBCBC;
	}

	.cor {
		color: #BCBCBC;
	}


	.title {
		font-size: 1.5em;
	}

	.margem-superior {
		padding-top: 25px;
	}
</style>



<table class="printer-ticket">

	<tr>
		<th class="title" colspan="3"><?php echo $nome_sistema ?></th>

	</tr>
	<tr>
		<th colspan="3"><?php echo $data2 ?> - <?php echo $hora ?></th>
	</tr>
	<tr>
		<th colspan="3">
			Endereço <?php echo $endereco_sistema ?> <br />
			Telefone <?php echo $telefone_sistema ?> CNPJ <?php echo $cnpj_sistema ?>
		</th>
	</tr>
	<tr>
		<th class="ttu margem-superior" colspan="3">
			Cupom não fiscal
			<?php
			if ($status == 'Cancelada') {
				echo ' - Venda Cancelada';
				
			} ?>
		</th>
	</tr>

	<tbody>

		<?php

		$res = $pdo->query("SELECT * from itens_venda where venda = '$id' order by id asc");
		$dados = $res->fetchAll(PDO::FETCH_ASSOC);
		$linhas = count($dados);

		$sub_tot;
		for ($i = 0; $i < count($dados); $i++) {
			foreach ($dados[$i] as $key => $value) {
			}

			$id_produto = $dados[$i]['produto'];
			$quantidade = $dados[$i]['quantidade'];
			$id_item = $dados[$i]['id'];


			$res_p = $pdo->query("SELECT * from produtos where id = '$id_produto' ");
			$dados_p = $res_p->fetchAll(PDO::FETCH_ASSOC);
			$nome_produto = $dados_p[0]['nome'];
			$valor = $dados_p[0]['valor_venda'];


			$total_item = $valor * $quantidade;


		?>

			<tr>

				<td colspan="2" width="50%"><?php echo $quantidade ?> - <?php echo $nome_produto ?>

				</td>


				<td style="text-align: right;">R$ <?php

										@$total_item;
										@$sub_tot = @$sub_tot + @$total_item;
										$sub_total = $sub_tot;
										
										if ($desconto_porcentagem == 'Sim') {
											$valor_desconto = ($sub_tot * $desconto_valor) / 100;
										} else {
											$valor_desconto = $desconto_valor;
										}
										$total_final = $sub_tot - $valor_desconto;

										$sub_total = number_format($sub_total, 2, ',', '.');
										$total_item = number_format($total_item, 2, ',', '.');
										$total = number_format($total_final, 2, ',', '.');


										echo $total_item;
										?></td>
			</tr>

		<?php }

		$valor_recebido = number_format($valor_recebido, 2, ',', '.');
		$troco = number_format($troco, 2, ',', '.');
		?>

	</tbody>
	<tfoot>

		<tr>
			<td colspan="3" class="cor">
				--------------------------------------------------------------------------------------------------------------------------------------------------------------
			</td>
		</tr>


		<tr>
			<td colspan="2">Sub-total</td>
			<td style="text-align: right;">R$ <?php echo @$sub_total ?></td>
		</tr>
		<tr>
			<td colspan="2">Desconto</td>
			<td style="text-align: right;"><?php echo $desconto_formatado ?></td>
		</tr>

		<tr>
			<?php 
				if ($status == 'Cancelada') {
					echo "<td colspan='2'>Total</td>";
					echo "<td style='text-align: right;'>VENDA CANCELADA</td>";
				} else {
					echo "<td colspan='2'>Total</td>";
					echo "<td style='text-align: right;'>R$ $total</td>";
				}
			?>
		</tr>

		<tr>
			<td colspan="2">Total Pago</td>
			<td style="text-align: right;">R$ <?php echo $valor_recebido ?></td>
		</tr>

		<tr>
			<td colspan="2">Troco</td>
			<td style="text-align: right;">R$ <?php echo $troco ?></td>
		</tr>

		<tr>
			<td colspan="3" class="cor">
				--------------------------------------------------------------------------------------------------------------------------------------------------------------
			</td>
		</tr>

		<tr>
			<td style="text-align: center;" class="ttu" colspan="3">
				forma de pagamento
			</td>

		</tr>

		<tr>
			<td colspan="3" class="cor">
				--------------------------------------------------------------------------------------------------------------------------------------------------------------
			</td>
		</tr>

		<tr>
			<td colspan="2">Forma de Pagamento</td>
			<td style="text-align: right;"><?php echo $nome_pgto ?></td>
		</tr>
		<tr>
			<td colspan="2">Operador</td>
			<td style="text-align: right;"><?php echo $nome_operador ?></td>
		</tr>



		<tr>
			<td colspan="3" class="cor">
				--------------------------------------------------------------------------------------------------------------------------------------------------------------
			</td>
		</tr>

		<tr>
			<td colspan="3" style="text-align: center;">
				https://vetor256.com
			</td>
		</tr>
	</tfoot>
</table>