<?php
	error_reporting(E_ALL);
	require_once dirname(__FILE__).'/lib/RepositorioContatos.php';

	// Esses valores podem ser obtidos na página de configurações do
	// Email Marketing
	$hostName = '';
	$login 	  = '';
	$chaveApi = '';
	$repositorio = new RepositorioContatos($hostName, $login, $chaveApi);

	print "\ncontatos validos\n";
	for($pagina=1; $contatos = $repositorio->obterContatos(RepositorioContatos::VALIDOS, $pagina); $pagina++) {
		foreach($contatos as $contato) {
			//se desejar visualizar todos os campos disponíveis, descomentar a linha abaixo:
			//print join(',',array_keys($contato)); exit;
			print "email: {$contato['email']}" .
					", nome: {$contato['nome']}" .
					", datanasc: {$contato['datadenascimento']}" .
					", estado: {$contato['estado']}" .
					", sexo: {$contato['sexo']}" .
					"\n";
		}
	}

	print "\ncontatos validos da lista 1\n";
	for($pagina=1; $contatos = $repositorio->obterContatos(RepositorioContatos::VALIDOS, $pagina, 1); $pagina++) {
		foreach($contatos as $contato) {
			//se desejar visualizar todos os campos disponíveis, descomentar a linha abaixo:
			//print join(',',array_keys($contato)); exit;
			print "email: {$contato['email']}" .
					", nome: {$contato['nome']}" .
					", datanasc: {$contato['datadenascimento']}" .
					", estado: {$contato['estado']}" .
					", sexo: {$contato['sexo']}" .
					"\n";
		}
	}


?>