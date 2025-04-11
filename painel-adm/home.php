<?php
@session_start();
require_once('../conexao.php');
require_once('verificar-permissao.php');
echo $_SESSION['nome_usuario'];
