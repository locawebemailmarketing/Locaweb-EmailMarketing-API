<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . '/lib/RepositorioMensagens.php';

// Esses valores podem ser obtidos na p�gina de configura��es do
// Email Marketing
$hostName = '';
$login 	  = '';
$chaveApi = '';
$repositorio= new RepositorioMensagens($hostName, $login, $chaveApi);

$arrMensagem = array (
	"identificador" => "teste1",
	"assunto" => "teste",
	"nome_remetente" => "mario",
	"email_remetente" => "homologacao@locaweb.com.br ",
	"dominio_dos_links" => "homologacao.mkt9.com",
	"id_campanha" => "2",
	"formato" => "texto_e_html",
	"url_mensagem_html" => "http://dominio.tempsite.ws/teste.html",
	"mensagem_texto" => "lalal popop lalalal popop",
	"incluir_link_visualizacao" => "true",
	"texto_link_visualizacao" => "Caso n�o visualize esse email adequadamente [acesse este link]"
);

$idMensagem = $repositorio->adicionarMensagem($arrMensagem);
print "O id da nova mensagem: $idMensagem";
?>
