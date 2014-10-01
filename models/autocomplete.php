<?php
/**
 * @author Wellington Ribeiro - IdealMind.com.br
 * @since 31/10/2009
 */

require_once '../func_class/funcoes.php';
require_once '../func_class/classes.php';
conectar();
$q = mysql_real_escape_string( $_GET['q'] );

$sqllinhas = "SELECT * FROM membro where locate('$q',nome) > 0 ";
//crit�rios de fon�tica

$reslinhas = mysql_query( $sqllinhas );
$linhas = mysql_num_rows($reslinhas);

$quantNomes = substr_count(trim($q),' ');

switch ($quantNomes) {
	case '3':
	 list($q1,$q2,$q3,$q4) = explode (' ',$q);
	 $sql = "SELECT * FROM membro where  nome LIKE '%$q1%' AND nome LIKE '%$q2%' AND nome LIKE '%$q3%' AND nome LIKE '%$q4%' order by locate('$q1',nome) limit 10";
	break;
	case '2':
	 list($q1,$q2,$q3) = explode (' ',$q);
	 $sql = "SELECT * FROM membro where  nome LIKE '%$q1%' AND nome LIKE '%$q2%' AND nome LIKE '%$q3%' order by locate('$q1',nome) limit 10";
	break;
	case '1':
	 list($q1,$q2) = explode (' ',$q);
	 $sql = "SELECT * FROM membro where  nome LIKE '%$q1%' AND nome LIKE '%$q2%' order by locate('$q1',nome) limit 10";
	break;
	
	default:
	$sql = "SELECT * FROM membro where locate('$q',nome) > 0 order by locate('$q',nome) limit 10";
	break;
}


$res = mysql_query( $sql );

while( $campo = mysql_fetch_array( $res ) )
{
	//echo "Id: {$campo['id']}\t{$campo['sigla']}\t{$campo['estado']}<br />";
	$id = $campo['fone_resid'];
	//$estado = $campo['nome'];
	$estado = strtoupper(strtr( $campo['nome'], '��������������������������','AAAAEEIOOOUUCAAAAEEIOOOUUC' ));
	$endereco = strtoupper(strtr( $campo ['endereco'], '��������������������������','AAAAEEIOOOUUCAAAAEEIOOOUUC' ));
	$endereco .=', '.$campo['numero'];
	$cargo = cargo($campo['rol']);
	$sigla = $cargo.' - '.htmlentities($campo['celular'],ENT_QUOTES,'iso-8859-1');
	$estado = addslashes($estado);
	$destaque = "<span style=\"font-weight:bold\">\$1</span>";
	switch ($quantNomes) {
		case '3':
			$patterns = array("/(" . $q1 . ")/i","/(" . $q2 . ")/i","/(" . $q3 . ")/i","/(" . $q4 . ")/i");
			$replacements = array($destaque,$destaque,$destaque,$destaque);
			// preg_replace($patterns, $replacements, $string);
			$html = preg_replace($patterns, $replacements, $estado);
						
		break;
		case '2':
			$patterns = array("/(" . $q1 . ")/i","/(" . $q2 . ")/i","/(" . $q3 . ")/i");
			$replacements = array($destaque,$destaque,$destaque);
			// preg_replace($patterns, $replacements, $string);
			$html = preg_replace($patterns, $replacements, $estado);
		break;
		case '1':
			$patterns = array("/(" . $q1 . ")/i","/(" . $q2 . ")/i");
			$replacements = array($destaque,$destaque);
			// preg_replace($patterns, $replacements, $string);
			$html = preg_replace($patterns, $replacements, $estado);
		break;
		
		default:
			$html = preg_replace("/(" . $q . ")/i", "<span style=\"font-weight:bold\">\$1</span>", $estado);
		break;
	}
	
	echo "<li onselect=\"this.setText('$estado').setValue('$id','$endereco','$sigla');\">$html ($sigla)</li>\n";
}
if ($linhas>10) {
	echo '<p style="text-align: right;">Total de '.$linhas.' ocorr&ecirc;ncias<br />';
	echo 'S&atilde;o mostradas at&eacute; as 10 primeiras!</p>';
}
?>