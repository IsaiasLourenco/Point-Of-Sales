<?php
require_once('../conexao.php');
require_once('gerarBarras.php');
$codigo = $_GET['codigo'];
?>

<style>
	@page {
		margin: 8px;
	}

	.margin {
		margin-right: 10px;
		display: inline-block;
		font-size: 14px;
		text-align: center;
		letter-spacing: 2px;
	}

	.linhaCodigos {
		margin-bottom: 10px;

	}
</style>

<?php for ($i2 = 0; $i2 < 14; $i2++) { ?>

	<div class="linhaCodigos">

		<?php for ($i = 0; $i < 5; $i++) { ?>

			<span class="margin">
				<?php
				echo geraCodigoBarra($codigo);
				?>
				<br>
				<?php echo $codigo ?>
			</span>

		<?php } ?>


	</div>

<?php } ?>