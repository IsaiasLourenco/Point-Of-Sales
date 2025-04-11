<?php
require_once("../../conexao.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$nome_double = $_POST['nome_double'];

//DUPLICIDADE NOME DA CATEGORIA
if ($nome_double != $nome) {
    $res_double = $pdo->prepare("SELECT * FROM categorias WHERE nome = :nome");
    $res_double->bindValue(":nome", "$nome");
    $res_double->execute();
    $nome_usu = $res_double->fetch(PDO::FETCH_ASSOC);
    if ($nome_usu) {
        echo 'Nome da Categoria já cadastrada!';
        exit();
    }
}

//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = preg_replace('/[ -]+/' , '-' , @$_FILES['imagem']['name']);
$caminho = '../../assets/img/categorias/' . $nome_img;
if (@$_FILES['imagem']['name'] == "") {
    $imagem = "sem-foto.jpg";
} else {
    $imagem = $nome_img;
}

$imagem_temp = @$_FILES['imagem']['tmp_name'];
$ext = pathinfo($imagem, PATHINFO_EXTENSION);
if($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif'){
    move_uploaded_file($imagem_temp, $caminho);
} else {
    echo 'Extensão de Imagem não permitida!';
    exit();
}

if ($id == "") {
    $res = $pdo->prepare("INSERT INTO categorias SET nome = :nome, imagem = :imagem");
    $res->bindValue(":nome", "$nome");
    $res->bindValue(":imagem", "$imagem");
    $res->execute();
} else {
    $res = $pdo->prepare("UPDATE categorias SET nome = :nome, imagem = :imagem WHERE id = :id");
    $res->bindValue(":id", "$id");
    $res->bindValue(":nome", "$nome");
    $res->bindValue(":imagem", "$imagem");
    $res->execute();
}
echo 'Salvo com Sucesso!';
?>