<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '/../src/lib/EmktCore.php';
require_once dirname(__FILE__) . '/../src/lib/RepositorioMensagens.php';
require_once dirname(__FILE__) . '/../src/lib/EmktApiException.php';

error_reporting(E_ALL);

class TestRepositorioMensagens extends PHPUnit_Framework_TestCase {

	private $emktCoreMock;

	private $repositorio;

	private $urlEsperada;

	public function setUp() {
		$this->emktCoreMock= $this->getMock("EmktCore");
		$this->repositorio= new RepositorioMensagens("test", "gustavo", "e538ea", "locaweb.com.br", $this->emktCoreMock);
		$this->urlEsperada= 'http://test.locaweb.com.br/admin/api/gustavo/contatos/mensagem?chave=e538ea';
	}

	public function testAdicionarMensagemUrlDeveSerValida() {
		$this->emktCoreMock->expects($this->once())->method('enviaRequisicaoPost')
				->with($this->urlEsperada);
		$this->repositorio->adicionarMensagem(array("atributo1"=>"valor1"));
	}


	public function testAdicionarMensagemUmaMensagemDeveSerAdicionadaComCamposAcentuados() {
		$jsonEsperado = '{"identificador":"teste","assunto":"teste","nome_remetente":"fabio",' .
				'"email_remetente":"fabio@gmail.com","dominio_dos_links":"teste.mktTest.com",' .
				'"id_campanha":"777","formato":"texto_e_html",' .
				'"url_mensagem_html":"http\/\/perrellalinux.tempsite.ws\/teste.html",' .
				'"mensagem_texto":"mensagem de texto","incluir_link_visualizacao":"true",' .
				'"texto_link_visualizacao":"Caso n\u00e3o visualize esse email ' .
				'adequadamente [acesse este link]"}';
		$idMensagemEsperado = 123;
		$atributosMensagemJsonEsperador = "";
		$this->emktCoreMock->expects($this->once())->method('enviaRequisicaoPost')
				->with($this->urlEsperada, $jsonEsperado)
				->will($this->returnValue($idMensagemEsperado));

		$atributosMensagem = array (
			"identificador" => "teste",
			"assunto" => "teste",
			"nome_remetente" => "fabio",
			"email_remetente" => "fabio@gmail.com",
			"dominio_dos_links" => "teste.mktTest.com",
			"id_campanha" => "777",
			"formato" => "texto_e_html",
			"url_mensagem_html" => "http//perrellalinux.tempsite.ws/teste.html",
			"mensagem_texto" => "mensagem de texto",
			"incluir_link_visualizacao" => "true",
			"texto_link_visualizacao" => "Caso não visualize esse email adequadamente [acesse este link]"
		);

		$this->assertEquals($idMensagemEsperado,
				$this->repositorio->adicionarMensagem($atributosMensagem));
	}

	public function testAdicionarMensagemDeveSerLancadaUmaEmktApiExceptionCasoOArraySejaVazioOuNulo() {
		try{
			$this->repositorio->adicionarMensagem(array());
			$this->fail("EmktApiException esperada nao ocorreu.");
		} catch(EmktApiException $e) {
			$this->assertTrue(true);
		}

		try{
			$this->repositorio->adicionarMensagem(null);
			$this->fail("EmktApiException esperada nao ocorreu.");
		} catch(EmktApiException $e) {
			$this->assertTrue(true);
		}
	}

}
?>