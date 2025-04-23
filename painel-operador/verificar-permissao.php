<?php
//VERIFICAR PERMISSÃO DO USUÁRIO
if (@$_SESSION['nivel_usuario'] != "Operador") {
    header("Location: ../");
    exit();
} else {
    $icone_index = '../assets/img/usuarios/operador.png';
}
?>

