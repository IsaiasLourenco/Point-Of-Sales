<?php
@session_start();
require_once('../conexao.php');
require_once('verificar-permissao.php');

//VARIAVEIS DO MENU ADMINISTRATIVO
$menu1 = 'home';
$menu2 = 'usuarios';
$menu3 = 'fornecedores';
$menu4 = 'categorias';
$menu5 = 'produtos';
$menu6 = 'compras';

//RECUPERAR DADOS DO USUÁRIO
$query = $pdo->query("SELECT * FROM usuarios WHERE id = '$_SESSION[id_usuario]'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$id_usu = $res[0]['id'];
$nome_usu = $res[0]['nome'];
$email_usu = $res[0]['email'];
$cpf_usu = $res[0]['cpf'];
$senha_usu = $res[0]['senha'];
$nivel_usu = $res[0]['nivel'];
?>

<!DOCTYPE html>
<html lang="pt-Br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>

    <!-- FavIcon -->
    <link rel="shortcut icon" href="../assets/img/ico.ico" type="image/x-icon">

    <!-- Meta Tags para SEO -->
    <meta name="description" content="Descubra o melhor programa de Ponto de Venda (PDV) para Supermercados, Lojas de bebidas,Farmácias, Padarias, Lanchonetes e Papelarias. Soluções completas para gestão eficiente e aumento de vendas.">
    <meta name="keywords" content="Ponto de Venda, PDV, Supermercados, Farmácias, Lojas de Bebidas, Gestão de Vendas, Software de Vendas">
    <meta name="Isaias Lourenço" content="Vetor256.">
    <meta name="robots" content="index, follow">

    <!-- Script JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="../assets/DataTabels/datatables.min.css">
    <script type="text/javascript" src="../assets/DataTabels/datatables.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <!-- Busca CEP -->
    <script src="../assets/js/buscaCep.js" type="module" defer></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-warning">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><img class="img-index" src="../assets/img/logo.png" alt="Logo" width="40px" height="40px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php?pagina=<?php echo $menu1 ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?pagina=<?php echo $menu2 ?>">Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?pagina=<?php echo $menu3 ?>">Fornecedores</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Produtos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="index.php?pagina=<?php echo $menu5 ?>">Cadastro de Produtos</a></li>
                            <li><a class="dropdown-item" href="index.php?pagina=<?php echo $menu4 ?>">Cadastro de Categorias</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="index.php?pagina=<?php echo $menu6 ?>">Compras</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownRel" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Relatórios
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownRel">
                            <li><a class="dropdown-item" href="../rel/relProdutos_class.php" target="_blank">Relatório de Produtos</a></li>
                            <li><a class="dropdown-item" href="" target="_blank" data-bs-toggle="modal" data-bs-target="#modalRelCompras">Relatório de Compras</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="" target="_blank">Relatório de Categorias</a></li>
                        </ul>
                    </li>
                </ul>

                <form class="d-flex">
                    <img class="img-profile rounded-circle mt-1" src="<?php echo $icone_index ?>" width="40px" height="40px">

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark" href="#" id="dropdownPerfil" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $nome_usu ?>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownPerfil">
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalPerfil">Editar Perfil<a>
                                        <a class="dropdown-item" href="../logout.php">Sair</a>

                            </div>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-2">
        <?php
        if (@$_GET['pagina'] == $menu1) {
            require_once($menu1 . '.php');
        } else if (@$_GET['pagina'] == $menu2) {
            require_once($menu2 . '.php');
        } else if (@$_GET['pagina'] == $menu3) {
            require_once($menu3 . '.php');
        } else if (@$_GET['pagina'] == $menu4) {
            require_once($menu4 . '.php');
        } else if (@$_GET['pagina'] == $menu5) {
            require_once($menu5 . '.php');
        } else if (@$_GET['pagina'] == $menu6) {
            require_once($menu6 . '.php');
        } else {
            require_once($menu1 . '.php');
        }
        ?>
    </div>

</body>

</html>

<!-- Modal de Inserção Edição -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalPerfil">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <form method="POST" id="frm-perfil">

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome-perfil" name="nome-perfil" placeholder="Nome" value="<?php echo @$nome_usu ?>" required="">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email-perfil" name="email-perfil" placeholder="email" value="<?php echo @$email_usu ?>" required="">
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control" id="cpf" name="cpf-perfil" placeholder="CPF" value="<?php echo @$cpf_usu ?>" required="">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="text" class="form-control" id="senha-perfil" name="senha-perfil" placeholder="Senha" value="<?php echo @$senha_usu ?>" required="">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="btn-fechar-perfil">Fechar</button>
                        <button type="submit" class="btn btn-secondary" name="btn-salvar-perfil" id="btn-salvar-perfil">Salvar</button>
                    </div>

                    <input name="id-perfil" type="hidden" value="<?php echo @$id_usu ?>">
                    <input name="cpf_double-perfil" type="hidden" value="<?php echo @$cpf_usu ?>">
                    <input name="email_double-perfil" type="hidden" value="<?php echo @$email_usu ?>">

                    <small>
                        <div style="text-align: center;" id="mensagem-perfil">

                        </div>
                    </small>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- Fim Modal de Inserção Edição -->

<!-- Modal de Relatório de Compras -->
<div class="modal fade" id="modalRelCompras" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Compras</h5>
            </div>

            <form action="../rel/relCompras_class.php" method="POST" target="_blank">
                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pago</label>
                                <select class="form-control" name="status">
                                    <option value="">Todas</option>
                                    <option value="Sim">Sim</option>
                                    <option value="Não">Não</option>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Gerar Relatório</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- Fim Modal de Relatório de Compras -->

<!-- Script Mascaras -->
<script type="text/javascript" src="../assets/js/mascara.js"></script>
<!-- Fim Script Mascaras -->

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
<!-- Fim Ajax para funcionar Mascaras JS -->

<!--AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS -->
<script type="text/javascript">
    $("#frm-perfil").submit(function() {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "editar-perfil.php",
            type: 'POST',
            data: formData,
            success: function(mensagem) {
                $('#mensagem').removeClass()
                if (mensagem.trim() == "Salvo com Sucesso!") {
                    //$('#nome').val('');
                    //$('#cpf').val('');
                    $('#btn-fechar-perfil').click();
                    //window.location = "index.php?pagina=" + pagina;
                    location.reload();
                } else {
                    $('#mensagem-perfil').addClass('text-danger')
                }
                $('#mensagem-perfil').text(mensagem)
            },
            cache: false,
            contentType: false,
            processData: false,

        });
    });
</script>
<!-- FIM AJAX PARA INSERÇÃO E EDIÇÃO DOS DADOS -->

<?PHP 

?>