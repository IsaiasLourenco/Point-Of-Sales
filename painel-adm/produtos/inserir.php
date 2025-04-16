<?php 
require_once("../../conexao.php");

$id = $_POST['id'];
$codigo = $_POST['codigo'];
$nome = $_POST['nome'];
$valor_venda = $_POST['valor_venda'];
$valor_venda = str_replace(',', '.', $valor_venda);
$descricao = $_POST['descricao'];
$categoria = $_POST['categoria'];
$nome_double = $_POST['nome_double'];
$codigo_double = $_POST['codigo_double'];

// EVITAR DUPLICIDADE NO NOME
if($nome_double != $nome){
	$query_con = $pdo->prepare("SELECT * from produtos WHERE nome = :nome");
	$query_con->bindValue(":nome", $nome);
	$query_con->execute();
	$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res_con) > 0){
		echo 'Produto já Cadastrado!';
		exit();
	}
}

// EVITAR DUPLICIDADE NO CÓDIGO
if($codigo_double != $codigo){
	$query_cod = $pdo->prepare("SELECT * from produtos WHERE codigo = :codigo");
	$query_cod->bindValue(":codigo", $codigo);
	$query_cod->execute();
	$res_cod = $query_cod->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res_cod) > 0){
		echo 'Código do produto já Cadastrado!';
		exit();
	}
}

//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = preg_replace('/[ -]+/', '-', @$_FILES['imagem']['name']);
$caminho = '../../assets/img/produtos/' .$nome_img;
if (@$_FILES['imagem']['name'] == ""){
  $imagem = "sem-foto.jpg";
}else{
    $imagem = $nome_img;
}

$imagem_temp = @$_FILES['imagem']['tmp_name']; 
$ext = pathinfo($imagem, PATHINFO_EXTENSION);   
if($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif'){ 
move_uploaded_file($imagem_temp, $caminho);
}else{
	echo 'Extensão de Imagem não permitida!';
	exit();
}

if($id == ""){
	$res = $pdo->prepare("INSERT INTO produtos SET codigo = :codigo, nome = :nome, valor_venda = :valor_venda, descricao = :descricao, categoria = :categoria, imagem = :imagem");
	$res->bindValue(":codigo", $codigo);
	$res->bindValue(":nome", $nome);
	$res->bindValue(":valor_venda", $valor_venda);
	$res->bindValue(":descricao", $descricao);
	$res->bindValue(":categoria", $categoria);
	$res->bindValue(":imagem", $imagem);
	$res->execute();
}else{
	if($imagem != 'sem-foto.jpg'){
		$res = $pdo->prepare("UPDATE produtos SET codigo = :codigo, nome = :nome, valor_venda = :valor_venda, descricao = :descricao, categoria = :categoria, imagem = :imagem WHERE id = :id");
		$res->bindValue(":id", $id);
		$res->bindValue(":codigo", $codigo);
		$res->bindValue(":nome", $nome);
		$res->bindValue(":valor_venda", $valor_venda);
		$res->bindValue(":descricao", $descricao);
		$res->bindValue(":categoria", $categoria);
		$res->bindValue(":imagem", $imagem);
		$res->execute();
	}else{
		$res = $pdo->prepare("UPDATE produtos SET codigo = :codigo, nome = :nome, valor_venda = :valor_venda, descricao = :descricao, categoria = :categoria WHERE id = :id");
		$res->bindValue(":id", $id);
		$res->bindValue(":codigo", $codigo);
		$res->bindValue(":nome", $nome);
		$res->bindValue(":valor_venda", $valor_venda);
		$res->bindValue(":descricao", $descricao);
		$res->bindValue(":categoria", $categoria);
		$res->execute();
	}
	
}

echo 'Salvo com Sucesso!';
?>