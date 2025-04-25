<?php
@session_start();
require_once('../conexao.php');
require_once('verificar-permissao.php');

$id_usuario = $_SESSION['id_usuario'];

$pagina = 'pdv';

//VERIFICAR SE O CAIXA ESTÁ ABERTO
$query = $pdo->query("SELECT * FROM caixa WHERE operador = '$id_usuario' AND status_caixa = 'Aberto' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);
if ($total_reg == 0) {
    echo "<script language='javascript'>window.location='index.php'</script>";
} else {
    $id_caixa = $res[0]['id'];
    $id_operador = $res[0]['operador'];
    $id_caixa = str_pad($id_caixa, 3, "0", STR_PAD_LEFT);

    $query_op = $pdo->query("SELECT * FROM usuarios WHERE id = '$id_operador'");
    $res_op = $query_op->fetchAll(PDO::FETCH_ASSOC);
    $total_reg_op = @count($res_op);
    if ($total_reg_op > 0) {
        $nome_operador = $res_op[0]['nome'];
    }
}

if ($desconto_porcentagem == 'Sim') {
    $desc = '%';
} else {
    $desc = 'R$';
}

?>

<!DOCTYPE html>
<html class="wide wow-animation" lang="pt-br">

<head>
    <title><?php echo $nome_sistema ?></title>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="../assets/css/telapdv.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="shortcut icon" href="../assets/img/ico.ico" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>

<body>

    <div class='checkout'>

        <div class="row">
            <div class="col-md-5 col-sm-12">
                <div class='order py-2'>
                    <p class="background">
                        Caixa <strong><?php echo $id_caixa ?></strong> aberto. Operado por <strong><?php echo $nome_operador ?></strong>
                        &nbsp;&nbsp;
                        <a href="index.php" title="Voltar para a Home">
                            <i class="bi bi-house"></i>
                        </a>
                    </p>
                    <p class="background">LISTA DE PRODUTOS</p>
                    <span id="listar">
                    </span>
                </div>
            </div>

            <div id='payment' class='payment col-md-7'>
                <form method="post" id="form-buscar">
                    <div class="row py-2">
                        <div class="col-md-7">

                            <p class="background">CÓDIGO DE BARRAS</p>
                            <input type="text" class="form-control form-control-lg" id="codigo" name="codigo" placeholder="Código de Barras">

                            <p class="background mt-3">PRODUTO</p>
                            <input type="text" class="form-control  form-control-md" id="produto" name="produto" placeholder="Produto">

                            <p class="background mt-3">DESCRIÇÃO</p>
                            <input type="text" class="form-control  form-control-md" id="descricao" name="descricao" placeholder="Descrição do Produto">

                            <div class="row">
                                <div class="col-6">
                                    <p class="background mt-3">QUANTIDADE</p>
                                    <input type="text" class="form-control  form-control-md" id="quantidade" name="quantidade" placeholder="Quantidade">

                                    <p class="background mt-1">VALOR UNITÁRIO</p>
                                    <input type="text" class="form-control  form-control-md" id="valor_unitario" name="valor_unitario" placeholder="Valor">

                                    <p class="background mt-1">ESTOQUE</p>
                                    <input type="text" class="form-control  form-control-md" id="estoque" name="estoque" placeholder="Estoque">
                                </div>

                                <div class="col-6 mt-4">
                                    <img id="imagem" src="../assets/img/produtos/sem-foto.jpg" width="100%">
                                </div>
                            </div>

                        </div>

                        <div class="col-md-5">

                            <p class="background">TOTAL DO ITEM</p>
                            <input type="text" class="form-control form-control-md" id="total_item" name="total_item" placeholder="Total por Itens">

                            <p class="background mt-3">SUB TOTAL</p>
                            <input type="text" class="subTotal form-control  form-control-md" id="sub_total_item" name="sub_total" placeholder="Sub Total">

                            <p class="background mt-3">DESCONTO EM <?php echo $desc ?></p>
                            <input type="text" class="form-control  form-control-md" id="desconto" name="desconto" placeholder="Desconto em <?php echo $desc ?>">

                            <p class="background mt-3">TOTAL COMPRA</p>
                            <input type="text" class="form-control  form-control-md" id="total_compra" name="total_compra" placeholder="Total da Compra" required="">

                            <p class="background mt-3">VALOR RECEBIDO</p>
                            <input type="text" class="form-control  form-control-md" id="valor_recebido" name="valor_recebido" placeholder="R$ 0,00">

                            <p class="background mt-3">TROCO</p>
                            <input type="text" class="form-control  form-control-md" id="troco" name="valor_troco" placeholder="Valor Troco">

                            <input type="hidden" name="forma_pgto_input" id="forma_pgto_input">

                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>

</body>

</html>

<!-- MODAL DELETAR ITEM -->
<div class="modal fade" tabindex="-1" id="modalExcluir">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titulo">Excluir Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="form-excluir">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Gerente</label>
                        <select class="form-select mt-1" aria-label="Default select example" name="gerente">
                            <?php
                            $query = $pdo->query("SELECT * FROM usuarios WHERE nivel = 'Administrador' ORDER BY nome ASC");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
                            $total_reg = @count($res);
                            if ($total_reg > 0) {
                                for ($i = 0; $i < $total_reg; $i++) {
                                    foreach ($res[$i] as $key => $value) {
                                    }
                            ?>
                                    <option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>
                            <?php }
                            } else {
                                echo '<option value="">Cadastre um Gerente Administrador</option>';
                            } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Senha Gerente</label>
                        <input type="password" class="form-control" id="senha_gerente" name="senha_gerente" placeholder="Senha Gerente" required="">
                    </div>

                    <small>
                        <div style="text-align:center" class="mt-1" id="mensagem-excluir">

                        </div>
                    </small>

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-fechar-excluir" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button name="btn-excluir" id="btn-excluir" type="submit" class="btn btn-danger">Excluir</button>

                    <input name="id" type="hidden" id="id_deletar_item">

                </div>
            </form>
        </div>
    </div>
</div>
<!-- FIM MODAL DELETAR ITEM -->

<!-- MODAL FECHAR VENDA -->
<div class="modal fade" tabindex="-1" id="modalVenda">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Fechar Venda</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="form-fechar-venda">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Forma de Pagamento</label>
                        <select class="form-select mt-1" aria-label="Default select example" name="forma_pgto" id="forma_pgto">
                            <?php
                            $query = $pdo->query("SELECT * from forma_pgtos order by id asc");
                            $res = $query->fetchAll(PDO::FETCH_ASSOC);
                            $total_reg = @count($res);
                            if ($total_reg > 0) {
                                for ($i = 0; $i < $total_reg; $i++) {
                                    foreach ($res[$i] as $key => $value) {
                                    }
                            ?>
                                    <option value="<?php echo $res[$i]['codigo'] ?>"><?php echo $res[$i]['nome'] ?></option>
                            <?php }
                            } else {
                                echo '<option value="">Cadastre uma Forma de Pagamento</option>';
                            } ?>
                        </select>
                    </div>

                    <small>
                        <div style="text-align: center;" class="mt-1" id="mensagem-venda">
                        </div>
                    </small>

                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-fechar-venda" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button name="btn-venda" id="btn-venda" type="submit" class="btn btn-danger">Fechar Venda</button>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- FIM MODAL FECHAR VENDA -->

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
<!-- Fim Ajax para funcionar Mascaras JS -->

<!-- SCRIPT PARA INICIAR VENDA -->
<script type="text/javascript">
    $(document).ready(function() {
        listarProdutos();
        document.getElementById('codigo').focus();
        document.getElementById('quantidade').value = '001';
        buscarDados();
    });
</script>
<!-- FIM SCRIPT PARA INICIAR VENDA -->

<!-- AJAX PARA BUSCAR DADOS DO PRODUTO E MOSTRAR NOS CAMPOS -->
<script type="text/javascript">
    document.getElementById('quantidade').addEventListener('input', function() {
        // Obtém o valor digitado no campo Quantidade
        let quantidade = document.getElementById('quantidade').value;

        // Verifica se o número é muito longo (ajuste o limite conforme necessário)
        if (quantidade.length > 5) { // Exemplo: limite de 5 dígitos
            alert("O valor digitado é muito longo! Verifique a quantidade.");

            // Adiciona um pequeno atraso antes de resetar e mover o foco
            setTimeout(() => {
                // Reseta o campo Quantidade para "001"
                document.getElementById('quantidade').value = "001";

                // Foca automaticamente no campo Código de Barras
                document.getElementById('codigo').focus();
            }, 200); // Espera 200ms para dar tempo de leitura do alerta
        }
    });
    let isProcessing = false; //Flag para evitar duplicidades no leitor
    let Timer;
    $("#codigo").keyup(function() {
        clearTimeout(Timer); //Limpa o timer
        Timer = setTimeout(() => {
            if (!isProcessing) {
                isProcessing = true; //Ativa a Flag anti duplicidades
                buscarDados();
            }
        }, 100);
    });
</script>
<!-- CONTINUAÇÃO -->
<script type="text/javascript">
    var pagina = "<?= $pagina ?>";
    let subTotal = 0;

    function buscarDados() {
        console.log($('#form-buscar').serialize());
        $.ajax({
            url: pagina + "/buscar-dados.php",
            method: 'POST',
            data: $('#form-buscar').serialize(),
            dataType: "html",

            success: function(result) {
                console.log(result);
                var array = result.split("&-/z");
                var nome = array[0];
                var descricao = array[1];
                var valor = array[2];
                var estoque = array[3];
                var imagem = array[4];
                var totalItem = array[5];
                var totalCompra = array[6];

                if (nome.trim() != "PRODUTO NÃO ENCONTRADO!!!") {
                    document.getElementById('produto').value = nome;
                    document.getElementById('descricao').value = descricao;
                    valor = "R$ " + valor.replace(".", ",");
                    document.getElementById('valor_unitario').value = valor;
                    document.getElementById('estoque').value = estoque;
                    $('#imagem').attr('src', '../assets/img/produtos/' + imagem);

                    var audio = new Audio('../assets/img/barCode.wav');
                    audio.addEventListener('canplaythrough', function() {
                        audio.play();
                    })
                    totalItem = parseFloat(totalItem).toFixed(2);
                    totalItem = "R$ " + totalItem.replace(".", ",");
                    document.getElementById('total_item').value = totalItem;

                    document.getElementById("total_compra").value = "R$ " + totalCompra;

                    // **Novas funcionalidades**
                    // Resetar quantidade para "001"
                    document.getElementById('quantidade').value = "001";

                    // Limpar o campo código de barras
                    document.getElementById('codigo').value = "";
                    document.getElementById('codigo').focus();

                    // Garantir que listarProdutos() seja executado depois
                    setTimeout(() => {
                        listarProdutos();
                    }, 100);

                }

                isProcessing = false; //Desativa a flag antiduplicidade após concluir a requisição do produto
            },
            error: function() {
                isProcessing = false; //Destrava a flag em caso de erro.

            }
        });
    }
</script>
<!-- FIM AJAX PARA BUSCAR DADOS DO PRODUTO E MOSTRAR NOS CAMPOS -->

<!-- AJAX PARA LISTAR PRODUTOS -->
<script type="text/javascript">
    var pagina = "<?= $pagina ?>";

    function listarProdutos() {
        $.ajax({
            url: pagina + "/listar-produtos.php",
            method: 'POST',
            data: $('#form-buscar').serialize(),
            dataType: "html",

            success: function(result) {
                $("#listar").html(result);
                // Recalcula o subtotal com base nos itens listados
                recalcularSubTotal();

            },
            error: function(xhr, status, error) {
                console.error("Erro ao listar produtos:", status, error);
            }

        });
    }
</script>
<!-- FIM AJAX PARA LISTAR PRODUTOS -->

<!-- RECALCULA TOTAIS -->
<script type="text/javascript">
    function recalcularSubTotal() {
        let novoSubTotal = 0;

        $(".item-total").each(function() {

            let valor = $(this).text().replace("R$ ", "").replace(",", ".");
            let valorFloat = parseFloat(valor);

            if (!isNaN(valorFloat)) {
                novoSubTotal += valorFloat; // Soma os valores ao subtotal
            }
        });

        let subTotalF = novoSubTotal.toFixed(2).replace(".", ",");
        let valor = document.getElementById('sub_total_item').value = "R$ " + subTotalF;

        // Atualiza a variável global, se necessário
        subTotal = novoSubTotal;
    }
</script>
<!-- FIM RECALCULA TOTAIS -->

<!-- SCRIPT PARA CHAMAR MODAL EXCLUI ITEM -->
<script type="text/javascript">
    function modalExcluir(id) {
        event.preventDefault();
        document.getElementById('id_deletar_item').value = id;
        var myModal = new bootstrap.Modal(document.getElementById('modalExcluir'), {})
        myModal.show();
    }
</script>
<!-- FIM SCRIPT PARA CHAMAR MODAL EXCLUI ITEM -->

<!-- AJAX EXCLUI ITEM -->
<script type="text/javascript">
    $("#form-excluir").submit(function() {
        var pagina = "<?= $pagina ?>";
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: pagina + "/excluir-item.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem').removeClass()
                if (mensagem.trim() == "Excluído com Sucesso!") {
                    $('#mensagem-excluir').addClass('text-success')
                    $('#btn-fechar-excluir').click();
                    window.location = "pdv.php";
                } else {
                    $('#mensagem-excluir').addClass('text-danger')
                }
                $('#mensagem-excluir').text(mensagem)
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });
</script>
<!-- FIM AJAX EXCLUI ITEM -->

<!-- SCRIPT PARA APLICAR DESCONTO -->
<script type="text/javascript">
    // Formatar o campo quando o usuário sair do campo (blur)
    $("#desconto").on("blur", function() {
        let valor = $(this).val().replace("R$", "").replace(",", ".").trim(); // Remove "R$" e vírgula para tratar como número
        if (valor === "" || isNaN(valor)) {
            valor = 0; // Define valor como 0 se o campo estiver vazio ou inválido
        } else {
            valor = parseFloat(valor); // Converte para número
        }

        // Formata o valor no campo com "R$" e duas casas decimais
        $(this).val("R$ " + valor.toFixed(2).replace(".", ","));

        // Chama a função para aplicar o desconto com o valor formatado corretamente
        aplicarDesconto();
    });

    // Função para aplicar o desconto
    function aplicarDesconto() {
        let valorCampo = $("#desconto").val().replace("R$", "").replace(",", ".").trim(); // Remove "R$" e converte para número
        let desconto = parseFloat(valorCampo) || 0; // Garante um número válido

        $.ajax({
            url: pagina + "/aplicar-desconto.php", // Endpoint para processar o desconto no back-end
            method: "POST",
            data: {
                desconto: desconto
            }, // Envia apenas o valor numérico ao servidor
            dataType: "html",
            success: function(result) {
                console.log(result); // Verifique o retorno para depuração, se necessário
                var array = result.split("&-/z");
                var totalCompra = array[0]; // Total atualizado com desconto

                // Atualiza o campo de total da compra
                document.getElementById("total_compra").value = "R$ " + totalCompra;
            },
            error: function(xhr, status, error) {
                console.error("Erro ao aplicar o desconto:", status, error); // Log de erro
            },
        });
    }
</script>
<!-- FIM SCRIPT PARA APLICAR DESCONTO -->