<?php
controle('admin_user');
if (empty($_SESSION['valid_user']))
	header("Location: ../");
//echo strlen($_POST["nome"]);
$arq = 'forms/usuario.php';
if ($_POST["cpf"] == "" && $_POST["nome"] == "") {
	echo '<div class="alert alert-info alert-dismissible fade in" role="alert">';
	echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
	echo '<span aria-hidden="true">&otimes;</span>';
	echo '</button> <strong>Lembre-se!</strong> Voc&ecirc; s&oacute; ir&aacute; cadastrar usu&aacute;rios ';
	echo 'no Sistema com o mesmo acesso voc&ecirc; tem!</div>';
} elseif ($_POST["senha"] <> $_POST["senha1"] || $_POST["senha"] == "") {
	echo '<div class="alert alert-danger alert-dismissible fade in" role="alert">';
	echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
	echo '<span aria-hidden="true">&otimes;</span></button> ';
	echo '<h3><strong>As senhas n&atilde;o coincidem</strong></h3>';
	echo 'A senha e a confirma&ccedil;&atilde;o da senha devem obrigatoriamente iguais!</div>';
	echo "<script>alert('Senhas n�o conferem!');</script>";
} elseif (!validaCPF($_POST["cpf"])) {
	echo "<script>alert('CPF inv�lido!');</script>";
} elseif ($_POST["nome"] == "" || strlen($_POST["nome"]) < 10) {
	echo "<script>alert('Nome inv�lido, N�o deve estar em branco e com ao menos 10 caracteres!');</script>";
} elseif ($_POST["setor"] <> $_SESSION["setor"] && $_SESSION["setor"] < "50") {
	echo "<script>alert('Voc� n�o pode atribuir direitos a setores que n�o lhe pertence!{$_SESSION["setor"]} - {$_POST["setor"]}');</script>";
	echo "Voc&ecirc; n&atilde;o pode atribuir direitos a setores que n&atilde;o lhe pertence!";
} else {
	$arq = 'models/cad_usuario.php';
}
require_once 'forms/manutencao.php';
require_once $arq;
