<?php
$rolConsuta = intval($_GET['bsc_rol']);
if ($_SESSION['nivel'] > 4) {
	$tab = "adm/atualizar_dados.php"; //link q informa o script quem receber� os dados do form para atualizar
	$tab_edit = "adm/dados_pessoais.php&tabela=membro&campo="; //Link de chamada da mesma p�gina para abrir o form de edi��o do item
	$conDado  = 'SELECT *,m.obs AS mobs,DATE_FORMAT(m.datanasc,"%d/%m/%Y") AS br_datanasc, ';
	$conDado .= 'DATE_FORMAT(e.batismo_em_aguas,"%d/%m/%Y")AS dt_batismo, m.hist AS histM, ';
	$conDado .= 'c.nome AS nomeCid, m.nome AS nome, i.razao, e.pastor AS pastor ';
	$conDado .= 'FROM membro AS m, eclesiastico AS e, profissional AS p, est_civil AS ec, cidade AS c, ';
	$conDado .= 'igreja AS i WHERE m.rol="' . $rolConsuta . '" AND m.rol=e.rol AND ';
	$conDado .= 'm.rol=p.rol AND m.rol=ec.rol AND c.id=m.naturalidade AND i.rol=e.congregacao';
	$dad_cad = mysql_query($conDado);
	if (mysql_num_rows($dad_cad) < 1) //Lista independente de outras tabelas
	{
		//$dad_cad = mysql_query ("SELECT * FROM membro WHERE rol='".$rolConsuta."'");
	}

	if (mysql_num_rows($dad_cad) < 1) //Informa que o rol n�o possui niguem registrado
	{
		echo '<div class="alert alert-danger" role="alert"><h3>
	<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
	Cadastro!	<span class="small">Os dados talvez n&atilde;o
	esteja completo para este Rol</span></h3></div>';
		exit;
	}
	$arr_dad = mysql_fetch_array($dad_cad);
	if (trim($arr_dad['mobs']) != '') //Informa que o rol n�o possui niguem registrado
	{
		echo '<div class="alert alert-danger" role="alert"><h3>';
		echo '<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>';
		echo ' Pend&ecirc;ncia Dados Pessoais!	<span class="small"><br /><p><br />Resolva a pend&ecirc;ncia';
		echo ' que est&aacute; aba Pessoais e poder&aacute; ser impresso o cart&atilde;o!<p/>';
		echo '<p>Verifique o campo Pend&ecirc;ncia, fa&ccedil;a os ajustes necess&aacute;rios, altere deixando-o em branco!<p/></span></h3></div>';
		exit;
	}
	$num_rows = mysql_num_rows($dad_cad);

	$filename = 'relatorio/cartao_impr.php';

	if (!file_exists($filename)) {
		$cartao  = '<div class="alert alert-info alert-dismissible fade in" role="alert">';
		$cartao .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		$cartao .= '<h3>Modelo padr&atilde;o de dados</h3>';
		$cartao .= 'O arquivo utilizado &eacute; um modelo padr&atilde;o<br />';
		$cartao .= 'Voc&ecirc; pode editar o arquivo <strong>"'.$filename.'"</strong>, utilizando-o para personalizar as informa&ccedil;&otilde;es do seu cart&atilde;o,';
		$cartao .= ' e ele n&atilde;o ser&aacute; afetado pelas atualiza��es<br />';
		$cartao .= '</div>';
		$filename = 'relatorio/cartaoImprimiModelo.php';
	}

	$printCartao  = '<a href="'.$filename.'?bsc_rol=' . $rolConsuta . '"';
	$printCartao .= 'target="_blanck"	title="Clique aqui para imprimir o cart&atilde;o">';
	$printCartao .= '<button class="btn btn-primary btn-xs" type="submit">';
	$printCartao .= 'Visualizar impress&atilde;o? <img src="img/Bank-Check-48x48.png"';
	$printCartao .= '	width="32" height="32" align="absmiddle"/>';
	$printCartao .= '</button></a>';
	if (!$altEdit) {
		$printCartao = '';
	}

	//print_r($arr_dad);
?>
	<div id="lst_cad">
		<?PHP
		if (!empty($rolConsuta)) {
			if (file_exists("img_membros/" . $rolConsuta . ".jpg")) {
				$img = $rolConsuta . ".jpg";
			} else {
				$img = "ver_foto.jpg";
			}
			switch ($arr_dad["situacao_espiritual"]) {
				case "1":
					$estilo = "";
					break;
				case "2":
					$estilo = "<span style='color:#FF0000'>Ver comunh&atilde;o</span><br />";
					break;
				default:
					$estilo = "<span style='color:#006633''>Corrija a comunh&atilde;o com a Igreja. Use bot&atilde;o Eclesi&aacute;stico acima!</span><br />";
					break;
			}
			echo $estilo;
		?>
			<table class='table table-striped table-condensed'>
				<tr>
					<td colspan="2">
						<strong>Nome:</strong>
						<h6><?PHP echo $arr_dad['nome']; ?> &nbsp;
							<?php
							echo $printCartao;
							?>
						</h6>
					</td>
					<td rowspan="2" class="text-center"><?PHP print mostra_foto($bsc_rol); ?></td>
				</tr>
				<tr>
					<td>
						<strong>Cargo:</strong>
						<h6>
							<?PHP
							//echo $rec->diacono()." - ".$rec->presbiterio()." - ".$rec->evangelista()." - ".$rec->pastor();
							echo cargo($rolConsuta)['0'];
							?></h6>
					</td>
					<td>
						<strong>Data de Nascimento:</strong>
						<h6><?PHP echo $arr_dad['br_datanasc']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<strong>Data de Nascimento:</strong>
						<h6><?PHP echo $arr_dad['br_datanasc']; ?>
					</td>
					<td>
						<strong>Pai:</strong>
						<h6><?PHP echo $arr_dad['pai']; ?></h6>
					</td>
					<td>
						<strong>M&atilde;e:</strong>
						<h6><?PHP echo $arr_dad['mae']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<strong>Estado Civil:</strong>
						<h6><?PHP echo $arr_dad['estado_civil']; ?>
					</td>
					<td>
						<strong>Nacionalidade:</strong>
						<h6><?PHP echo $arr_dad['nacionalidade']; ?>
					</td>
					<td>
						<strong>Naturalidade:</strong>
						<h6><?PHP echo $arr_dad["naturalidade"] . ' - ' . $arr_dad["nomeCid"]; ?>
					</td>
				</tr>
				<tr>
					<td>
						<strong>UF:</strong>
						<h6><?PHP echo $arr_dad['uf_nasc']; ?>
					</td>
					<td>
						<strong>RG:</strong>
						<h6><?PHP echo $arr_dad['rg']; ?>
					</td>
					<td>
						<strong>Org&atilde;o Expedidor:</strong>
						<h6><?PHP echo $arr_dad['orgao_expedidor']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<strong>CPF:</strong>
						<h6><?PHP echo $arr_dad['cpf']; ?>
					</td>
					<td>
						<strong>Data do Batismo:</strong>
						<h6><?PHP echo $arr_dad['dt_batismo']; ?>
					</td>
					<td colspan="2">
						<strong>Congrega&ccedil;&atilde;o:</strong>
						<h6><?PHP echo $arr_dad['razao']; ?>
					</td>
				</tr>
			</table>
	<?PHP
		}
	}

	if (!empty($cartao)) {
		echo $cartao;
	}
	?>
	<div class="alert alert-info" role="alert">
		<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
		O cargo de auxiliar &eacute; uma situa&ccedil;&atilde;o que depende do
		Pastor local e assim pode ser romivada e basta zerar a data de auxiliar!
		<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
		N&atilde;o &eacute; realizado o hist&oacute;rio.
	</div>
	</div>