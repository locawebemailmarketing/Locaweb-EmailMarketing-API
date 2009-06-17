<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__) . '/../src/lib/RepositorioContatos.php';
require_once dirname(__FILE__).'/../src/lib/EmktApiException.php';

error_reporting(E_ALL);

class TestRepositorioContatos extends PHPUnit_Framework_TestCase {

	private $emktCoreMock;

	private $repositorio;

	private $urlEsperada;

	public function setUp() {
		$this->emktCoreMock = $this->getMock("EmktCore");
		$this->repositorio = new RepositorioContatos("test", "gustavo",
			"e538ea", 'locaweb.com.br',
			 $this->emktCoreMock);
		$this->urlObterContatosEsperada = "http://test.locaweb.com.br/admin/api/gustavo/" .
			"contatos/validos?chave=e538ea" .
			"&pagina=1";
	}

	function testObterValidosUrlValida() {
		$this->emktCoreMock->expects($this->once())
			->method('enviaRequisicaoGet')
			->with($this->urlObterContatosEsperada);
		$this->repositorio->obterValidos(1);
	}

	function testObterValidosDeveRetornarNull() {
		$this->emktCoreMock->expects($this->once())
			->method('enviaRequisicaoGet')
			->with($this->urlObterContatosEsperada);
		$this->assertNull($this->repositorio->obterValidos(1));
	}

	function testObterValidosDeveRetornarUmContatoValido() {
		$respostaMock =
			'[{"email":"xconta4@testecarganl.tecnologia.ws",' .
			'"nome":"nomeTeste"}]';
		$this->emktCoreMock->expects($this->once())->method('enviaRequisicaoGet')
			->with($this->urlObterContatosEsperada)
			->will($this->returnValue($respostaMock));
		$contatos = $this->repositorio->obterValidos(1);
		$this->assertEquals(1, count($contatos));
		$this->assertEquals('xconta4@testecarganl.tecnologia.ws',
			 $contatos[0]->email);
		$this->assertEquals('nomeTeste', $contatos[0]->nome);
	}

	function testObterValidosDeveLancarUmaExcecaoComErroDeParseNoJson() {
		$respostaMock = '[{"a":"b}]';
		$this->emktCoreMock->expects($this->once())->method('enviaRequisicaoGet')
			->with($this->urlObterContatosEsperada)
			->will($this->returnValue($respostaMock));
		try {
			$contatos = $this->repositorio->obterValidos(1);
			$this->assertFail("EmktApiException esperada nao ocorreu.");
		} catch (EmktApiException $e){
			$this->assertTrue(true);
		}
	}

	function testImportarUmContatoSemLista() {
		$contatos = array(array("email"=>"exemplo@test.com.br","nome"=>"nome1"));
		try {
			$this->repositorio->importar($contatos, array());
			$this->fail("EmktApiException esperada nao ocorreu");
		} catch (EmktApiException $e) {
			$this->assertTrue(true);
		}
	}

	function testImportarComUmaListaNula() {
		$contatos = array(array("email"=>"exemplo@test.com.br","nome"=>"nome1"));
		try {
			$this->repositorio->importar($contatos, null);
			$this->fail("EmktApiException esperada nao ocorreu");
		} catch (EmktApiException $e) {
			$this->assertTrue(true);
		}
	}

	function testImportarUmContatoEmUmaLista() {
		$urlEsperada = 'http://test.locaweb.com.br/admin/api/gustavo/contatos/importacao?lista=1&chave=e538ea';
		$arrContatos = array(array("email"=>"exemplo@test.com.br","nome"=>"nome1"));
		$contatosJson = '[{"email":"exemplo@test.com.br","nome":"nome1"}]';

		$this->emktCoreMock->expects($this->once())->method('enviaRequisicaoPost')->with($urlEsperada, $contatosJson);
		$listaIds = array(1);
		$this->repositorio->importar($arrContatos, $listaIds);
	}

	function testImportarDoisContatosEmDuasListas() {
		$urlEsperada = 'http://test.locaweb.com.br/admin/api/gustavo/' .
				'contatos/importacao?lista=1;2&chave=e538ea';
		$contatos = array(
				array("email"=>"exemplo1@test.com.br","nome"=>"nome1"),
				array("email"=>"exemplo2@test.com.br","nome"=>"nome2")
				);
		$contatosJson = '[{"email":"exemplo1@test.com.br","nome":"nome1"},{"email":"exemplo2@test.com.br","nome":"nome2"}]';
		$this->emktCoreMock->expects($this->once())->method('enviaRequisicaoPost')
			->with($urlEsperada, $contatosJson);
		$listaIds = array(1,2);
		$this->repositorio->importar($contatos, $listaIds);
	}

	function testImportarUmaEmktApiExceptionDeveOcorrer() {
		$e = new EmktApiException();
		try {
			$this->repositorio->importar('', array(1));
			$this->assertFail("EmktApiException esperada nao ocorreu.");
		} catch (EmktApiException $e) {
			$this->assertTrue(true);
		}
	}

	function testImportarContendoCaracteresAcentuados() {
		$urlEsperada = 'http://test.locaweb.com.br/admin/api/gustavo/' .
				'contatos/importacao?lista=1&chave=e538ea';
		$contatos = array(array("email"=>"exemplo1@test.com.br","nome"=>"José"));
		$contatosJson = '[{"email":"exemplo1@test.com.br","nome":"Jos\u00e9"}]';
		$this->emktCoreMock->expects($this->once())->method('enviaRequisicaoPost')
			->with($urlEsperada, $contatosJson);
		$listaIds = array(1);
		$this->repositorio->importar($contatos, $listaIds);
	}
}
?>