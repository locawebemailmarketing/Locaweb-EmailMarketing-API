<?php
	error_reporting(E_ALL);
	require_once dirname(__FILE__).'/lib/RepositorioContatos.php';
	// Esses valores podem ser obtidos na página de configurações do
	// Email Marketing

	$hostName = '';
	$login 	  = '';
	$chaveApi = '';
	$repositorio = new RepositorioContatos($hostName, $login, $chaveApi);

	print "\n desativar contatos\n";

	$contatos = array();
	array_push($contatos, array('email'=>'100medodoescuro@bol.com.br'));
	array_push($contatos, array('email'=>'maria.silva@e.com.br'));

	//Caso queira remover de listas, informar os IDs desta no 2o parametro.
	$repositorio->desativar($contatos,array());
?>