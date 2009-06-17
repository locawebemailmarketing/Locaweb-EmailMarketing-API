<?php
	error_reporting(E_ALL);
	require_once dirname(__FILE__).'/lib/RepositorioContatos.php';
	// Esses valores podem ser obtidos na p�gina de configura��es do
	// Email Marketing

	$hostName = '';
	$login 	  = '';
	$chaveApi = '';
	$repositorio = new RepositorioContatos($hostName, $login, $chaveApi);

	print "\ninserir contatos\n";

	// Campos dispon�veis: bairro,cep,cidade,datadenascimento,departamento,email,empresa,endereco,estado
	//                     htmlemail,nome,sexo,sobrenome
	//
	// Todos os campos s�o opcionais com a exce��o do campo email:
	// array_push($contatos, array('bairro'=>'',"cep"=>"", "cidade"=>"", "datadenascimento"=>"",
	//							"departamento"=>"","email"=>"campo obrigatorio","empresa"=>"","endereco"=>"",
	//							"estado"=>"", "htmlemail"=>"","nome"=>"","sexo"=>"","sobrenome"=>""));

	$contatos = array();
	array_push($contatos, array('email'=>'jose.silva@e.com.br', 'nome'=>'Jos� Silva'));
	array_push($contatos, array('email'=>'maria.silva@e.com.br', 'nome'=>'Maria Silva'));

	//Inserir contato na lista
	$repositorio->importar($contatos, array(1));
?>