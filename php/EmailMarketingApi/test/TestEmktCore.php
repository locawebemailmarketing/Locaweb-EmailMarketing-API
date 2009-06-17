<?php
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/../src/lib/EmktCore.php';
require_once dirname(__FILE__).'/../src/lib/EmktApiException.php';
error_reporting(E_ALL);

class TestEmktCore extends PHPUnit_Framework_TestCase {

	private $emktCore;

	public function setUp() {
		$this->emktCore = new EmktCore();
	}

	function testValidaCodigoHttpNenhumaExcecaoDeveSerLancada() {
		$this->emktCore->validaCodigoHttp('200');
		$this->assertTrue(true);
	}

	function testValidaCodigoHttpUmaExceptionDeveSerLancadaSeRetornoFor0() {
		try {
			$this->emktCore->validaCodigoHttp('0');
		}catch (Exception $e) {
			$this->assertTrue(true);
			return;
		}
		$this->fail("Uma Exception deveria ser lancada.");
	}

	function testValidaCodigoHttpUmaExceptionDeveSerLancadaSeRetornoDiferrenteDe200() {
		try {
			$this->emktCore->validaCodigoHttp('400');
		}catch (Exception $e) {
			$this->assertTrue(true);
			return;
		}
		$this->fail("Uma Exception deveria ser lancada.");
	}
}
?>
