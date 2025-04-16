<?php
//VERIFICAR PERMISSÃO DO USUÁRIO
if (@$_SESSION['nivel_usuario'] != "Tesoureiro") {
    header("Location: ../");
    exit();
} else {
    $icone_index = '../assets/img/usuarios/tesoureiro.png';
}
?>

