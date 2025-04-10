<?php
require_once('../config.php');
//VARIÁVEIS DO MENU ADMINISTRATIVO
$menu1 = 'home';
$menu2 = 'usuarios';
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                </ul>

                <form class="d-flex">
                    <img class="img-profile rounded-circle mt-1" src="../assets/img/eu-II.jpg" width="40px" height="40px">

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark" href="#" id="dropdownPerfil" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Isaias Lourenço
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownPerfil">
                                <a class="dropdown-item" href="#">Editar Perfil<a>
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
        } else {
            require_once($menu1 . '.php');
        }
        ?>
    </div>

</body>

</html>

<!-- Script Mascaras -->
<script type="text/javascript" src="../assets/js/mascara.js"></script>
<!-- Fim Script Mascaras -->

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
<!-- Fim Ajax para funcionar Mascaras JS -->