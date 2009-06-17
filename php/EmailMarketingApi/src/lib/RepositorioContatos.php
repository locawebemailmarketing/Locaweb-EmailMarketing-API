<?php
require_once dirname(__FILE__).'/EmktCore.php';

/**
 *  Locaweb LTDA.
 *
 *  Estс щ uma API exemplo que facilita a utilizaчуo dos web services do Email Marketing.
 *
 * @version 0.1
 * @see http://wiki.locaweb.com.br/pt-br/APIs_do_Email_Marketing
 */
class RepositorioContatos {

	/**
	 * Nome do servidor.
	 */
  	private $hostName;

	/**
	 * Login usado no Email Marketing.
	 */
	private $login;

	/**
	 * Chave gerada para uso dessa API.
	 */
	private $chave;

	private $emktCore;

	private $hostNameSufix;

	/**
	 * @param string hostName usado no Email Marketing.
	 * @param string Login usado no Email Marketing.
	 * @param string Chave gerada para uso dessa API.
	 */
	public function RepositorioContatos($hostName, $login, $chave,
		 $hostNameSufix='.locaweb.com.br', EmktCore $emktCore = null) {
		// hostNameSufix de producao tecnologia.ws
		$this->hostName = $hostName;
		$this->login = $login;
		$this->chave = $chave;
		$this->hostNameSufix = $hostNameSufix;
		if($emktCore==null){
			$emktCore = new EmktCore();
		}
		$this->emktCore = $emktCore;
	}


/*************** Inicio metodos de Listagem de Contatos ***********************
 * Os mщtodos de listagem possuem o parтmetro pagina. Ele informa qual pсgina
 * da pesquisa deve ser retornada. Atualmente o limite de contatos por pсgina
 * щ de 25mil contatos por pсgina. Por isso, caso tenha 40mil contatos em sua
 * base por exemplo, precisarс fazer 2 chamadas passando o parтmetro pagina=1
 * (que devolverс os contatos de 1 a 24999) e em seguida pagina=2 (que
 * devolverс os contatos de 25000 a 40000)
 */

	/**
	 * Obter todos os contatos que estуo no estado vсlido.
	 *
	 * @param integer pagina
	 */
	public function obterValidos($pagina=1){
		return $this->obterPorStatus($pagina,'validos');
	}

	/**
	 * Obter todos os contatos que estуo no estado invсlido.
	 *
	 * @param integer pagina
	 */
	public function obterInvalidos($pagina=1){
		return $this->obterPorStatus($pagina,'invalidos');
	}

	/**
	 * Obter todos os contatos que estуo no estado nуo confirmados.
	 *
	 * @param integer pagina
	 */
	public function obterNaoConfirmados($pagina=1){
		return $this->obterPorStatus($pagina,'nao_confirmados');
	}

	/**
	 * Obter todos os contatos que estуo no estado descadastrados.
	 *
	 * @param integer pagina
	 */
	public function obterDescadastrados($pagina=1){
		return $this->obterPorStatus($pagina,'descadastrados');
	}

	private function geraUrl() {
		return "http://{$this->hostName}.{$this->hostNameSufix}/admin/api/" .
				"{$this->login}/contatos";
	}

	private function obterPorStatus($pagina=1, $status) {
		$url = $this->geraUrl() .
			"/{$status}?chave={$this->chave}&pagina={$pagina}";

		$resultado = $this->emktCore->enviaRequisicaoGet($url);
		if($resultado==null) {
			return null;
		}
		$resultado = json_decode($resultado, true);
		if($resultado===null) {
			throw new EmktApiException('Erro ao transformar em JSON.');
		}

		foreach($resultado as $numLinha => $linha) {
			foreach($linha as $chave => $valor) {
				$resultado[$numLinha][$chave] = utf8_decode($valor);
			}
		}

		return $resultado;
	}

	/**
	 * Faz a importaчуo dos contatos.
	 *
	 * @param array $arrContatos
	 * @param array $listaIds
	 */
	public function importar($arrContatos, $listaIds) {
		if(!is_array($listaIds) || count($listaIds)==0) {
			throw new EmktApiException("Array de ids das listas nao pode ser vazio.");
		}
		if(!is_array($arrContatos) || empty($arrContatos)){
			throw new EmktApiException("Array de contatos nao pode ser vazio.");
		}

		$url = $this->geraUrl() ."/importacao?lista=" . implode(";", $listaIds). "&chave={$this->chave}";

		foreach($arrContatos as $numLine => $line){
			foreach($line as $key => $val) {
				$arrContatos[$numLine][$key] = utf8_encode($val);
			}
		}

		$contatosJson = json_encode($arrContatos);

		return $this->emktCore->enviaRequisicaoPost($url, $contatosJson);
	}
}
?>