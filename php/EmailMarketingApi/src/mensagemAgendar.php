<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . '/lib/RepositorioMensagens.php';

// Esses valores podem ser obtidos na página de configurações do
// Email Marketing
$hostName= 'testelmm.tecnologia.ws';
$login= 'teste';
$chaveApi= '8c4b5c8b70fa2ef5b003f09ce1ecf6d6';
$repositorio= new RepositorioMensagens($hostName, $login, $chaveApi, '');

$arrAgendamento= array (
	"data_agendamento" => "2009-07-16 13:30:00",
	"listas" => "1"
);

$idMensagem = $repositorio->agendarMensagem($arrAgendamento, '33');
print "Sua mensagem foi agendada com sucesso!";
?>
