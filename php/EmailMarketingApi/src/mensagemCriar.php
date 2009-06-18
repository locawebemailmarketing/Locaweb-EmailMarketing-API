<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . '/lib/RepositorioMensagens.php';

// Esses valores podem ser obtidos na página de configurações do
// Email Marketing
$hostName= 'testelmm.tecnologia.ws';
$login= 'teste';
$chaveApi= '8c4b5c8b70fa2ef5b003f09ce1ecf6d6';
$repositorio= new RepositorioMensagens($hostName, $login, $chaveApi, '');

$arrMensagem = array (
	"identificador" => "teste1",
	"assunto" => "teste",
	"nome_remetente" => "fabio",
	"email_remetente" => "fabio.perrella@gmail.com",
	"dominio_dos_links" => "testeemailmkt.mkt9.com",
	"id_campanha" => "1",
	"formato" => "texto_e_html",
	"url_mensagem_html" => "http://perrellalinux.tempsite.ws/teste.html",
	"mensagem_texto" => "lalal popop lalalal",
	"incluir_link_visualizacao" => "true",
	"texto_link_visualizacao" => "Caso não visualize esse email adequadamente [acesse este link]"
);

$idMensagem = $repositorio->adicionarMensagem($arrMensagem);
print "O id da nova mensagem: $idMensagem";
?>
