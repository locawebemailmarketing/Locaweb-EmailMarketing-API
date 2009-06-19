<?php
error_reporting(E_ALL);
require_once dirname(__FILE__) . '/lib/RepositorioMensagens.php';

// Esses valores podem ser obtidos na página de configurações do
// Email Marketing
$hostName	= '';
$login		= '';
$chaveApi	= '';
$repositorio= new RepositorioMensagens($hostName, $login, $chaveApi);

$arrAgendamento= array (
	"data_agendamento" => "2009-06-19 10:07:00",
	"todos_contatos" => "true"
);

$repositorio->agendarMensagem($arrAgendamento, '36');
print "Sua mensagem foi agendada com sucesso!";
?>
