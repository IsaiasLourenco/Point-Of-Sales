<?php
$pag = 'contas_pagar_hoje';
@session_start();

require_once('../conexao.php');
require_once('verificar-permissao.php')

?>
<h5 style="text-align: center;" class="text-secondary">À PAGAR HOJE</h5>

<div class="mt-4" style="margin-right:25px">
	<?php
	$query = $pdo->query("SELECT * FROM contas_pagar WHERE vencimento = curDate() AND pago != 'Sim' ORDER BY id ASC");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($res);
	if ($total_reg > 0) {
	?>
		<small>
			<table id="example" class="table table-hover my-4" style="width:100%; font-size: 10px;">
				<thead>
					<tr>
						<th style="text-align: center;">Pago</th>
						<th style="text-align: center;">Descrição</th>
						<th style="text-align: center;">Valor</th>
						<th style="text-align: center;">Usuário</th>
						<th style="text-align: center;">Vencimento</th>
						<th style="text-align: center;">Arquivo</th>
						<th style="text-align: center;">Pagar</th>
					</tr>
				</thead>
				<tbody>
					<?php
					for ($i = 0; $i < $total_reg; $i++) {
						foreach ($res[$i] as $key => $value) {
						}
						$id_usu = $res[$i]['usuario'];
						$query_p = $pdo->query("SELECT * from usuarios where id = '$id_usu'");
						$res_p = $query_p->fetchAll(PDO::FETCH_ASSOC);
						$nome_usu = $res_p[0]['nome'];
						if ($res[$i]['pago'] == 'Sim') {
							$classe = 'text-success';
						} else {
							$classe = 'text-danger';
						}
						$extensao = strchr($res[$i]['arquivo'], '.');
						if ($extensao == '.pdf') {
							$arquivo_pasta = 'pdf.png';
						} else {
							$arquivo_pasta = $res[$i]['arquivo'];
						}
					?>
						<tr>
							<td style="text-align: center;"> <i class="bi bi-square-fill <?php echo $classe ?>"></i></td>
							<td style="text-align: center;"><?php echo $res[$i]['descricao'] ?></td>
							<td style="text-align: center;">R$ <?php echo number_format($res[$i]['valor'], 2, ',', '.'); ?></td>
							<td style="text-align: center;"><?php echo $nome_usu ?></td>
							<td style="text-align: center;"><?php echo implode('/', array_reverse(explode('-', $res[$i]['vencimento']))); ?></td>
							<td style="text-align: center;"><a href="../assets/img/contas_pagar/<?php echo $res[$i]['arquivo'] ?>" title="Ver Arquivo" style="text-decoration: none" target="_blank">
									<img src="../assets/img/contas_pagar/<?php echo $arquivo_pasta ?>" width="20">
								</a>
							</td>
							<td style="text-align: center;">
								<a href="index.php?pagina=<?php echo $pag ?>&funcao=baixar&id=<?php echo $res[$i]['id'] ?>" title="Pagar" style="text-decoration: none">
									<i class="bi bi-check-square-fill text-success mx-1"></i>
								</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</small>
	<?php } else {
		echo '<p>Não existem dados para serem exibidos!!';
	} ?>
</div>

<!-- MODAL PAGAR -->
<div class="modal fade" tabindex="-1" id="modalBaixar">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Pagar Conta</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form method="POST" id="form-baixar">
				<div class="modal-body">
					<p>Deseja Realmente confirmar o Recebimento da conta <strong><?php echo @$_GET['id'] ?></strong>?</p>
					<small>
						<div align="center" class="mt-1" id="mensagem-baixar">
						</div>
					</small>
				</div>
				<div class="modal-footer">
					<button type="button" id="btn-fechar-baixar" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
					<button name="btn-baixar" id="btn-excluir" type="submit" class="btn btn-danger">Pagar</button>
					<input name="id-pagar" type="hidden" value="<?php echo @$_GET['id'] ?>">
				</div>
			</form>
		</div>
	</div>
</div>
<!-- FIM MODAL PAGAR -->

<!-- CHAMA PAGAMENTO -->
<?php
if (@$_GET['funcao'] == "baixar") { ?>
	<script type="text/javascript">
		var myModal = new bootstrap.Modal(document.getElementById('modalBaixar'), {

		})

		myModal.show();
	</script>
<?php } ?>
<!-- FIM CHAMA PAGAMENTO -->

<!--AJAX PARA PAGAR CONTAS -->
<script type="text/javascript">
	$("#form-baixar").submit(function() {
		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "contas_pagar/pagar.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {

				$('#mensagem-baixar').removeClass()

				if (mensagem.trim() == "Conta efetivamente Paga!") {

					$('#mensagem-baixar').addClass('text-success')

					$('#btn-fechar-baixar').click();
					window.location = "index.php?pagina=contas_pagar_hoje";

				} else {

					$('#mensagem-baixar').addClass('text-danger')
				}

				$('#mensagem-baixar').html(mensagem)

			},

			cache: false,
			contentType: false,
			processData: false,

		});
	});
</script>
<!-- FIM AJAX PARA PAGAR CONTAS -->

<!-- ORDENAR DATABLE -->
<script type="text/javascript">
	$(document).ready(function() {
		$('#example').DataTable({
			"ordering": false
		});
	});
</script>
<!-- FIM ORDENAR DATABLE -->

<!--SCRIPT PARA CARREGAR IMAGEM -->
<script type="text/javascript">
	function carregarImg() {
		
		var target = document.getElementById('target');
		var file = document.querySelector("input[type=file]").files[0];
		
		var arquivo = file['name'];
		resultado = arquivo.split(".", 2);
		//console.log(resultado[1]);
		
		if (resultado[1] === 'pdf') {
			$('#target').attr('src', "../assets/img/contas_pagar/pdf.png");
			return;
		}
		
		var reader = new FileReader();
		
		reader.onloadend = function() {
			target.src = reader.result;
		};
		
		if (file) {
			reader.readAsDataURL(file);
			
			
		} else {
			target.src = "";
		}
	}
</script>
<!--FIM SCRIPT PARA CARREGAR IMAGEM -->