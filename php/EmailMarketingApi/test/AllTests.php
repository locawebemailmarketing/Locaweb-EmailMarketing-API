<?php
chdir(dirname(__FILE__));

if (!defined('PHPUnit_MAIN_METHOD')) {
	define('PHPUnit_MAIN_METHOD', 'Crypt_GPG_AllTests::main');
}

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

//carrega todas as classes de teste deste diretorio
function __autoload($class_name) {
	$arrParts = split('_',$class_name);
	$class_name='';
	for($i=0; $i<count($arrParts); $i++){
		$part = $arrParts[$i];
		if($i==count($arrParts)-1){
			$class_name =  $class_name."$part.php";
		}
		else{
			$class_name = $class_name."$part/";
		}
	}
	if (!file_exists($class_name)) {
		print "nao achou classe $class_name";
		exit;
	} else {
		#logErrorClass("carregou $class_name com sucesso");
		require_once $class_name;
	}
}

class AllTests {

	public static function main() {
		PHPUnit_TextUI_TestRunner :: run(self :: suite());
	}

	public static function suite() {
		$suite= new PHPUnit_Framework_TestSuite('Crypt_GPG Tests');
		$suite->addTestSuite('TestEmktCore');
		$suite->addTestSuite('TestRepositorioContatos');
		$suite->addTestSuite('TestRepositorioMensagens');
		return $suite;
	}
}

if (PHPUnit_MAIN_METHOD == 'Crypt_GPG_AllTests::main') {
	Crypt_GPG_AllTests :: main();
}
?>
