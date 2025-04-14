<?php
//VERIFICAR PERMISSÃO DO USUÁRIO
if (@$_SESSION['nivel_usuario'] != "Administrador") {
    header("Location: ../");
    exit();
} else {
    $icone_index = '../assets/img/usuarios/administrador.png';
}
