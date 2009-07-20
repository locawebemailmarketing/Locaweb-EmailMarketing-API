<?php
require_once dirname(__FILE__).'/EmktCore.php';

/**
 *  Locaweb LTDA.
 *
 *  Estб й uma API exemplo que facilita a utilizaзгo dos web services do Email Marketing.
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

	const VALIDOS = 'validos';
	const INVALIDOS = 'invalidos';
	const NAO_CONFIRMADOS = 'nao_confirmados';
	const DESCADASTRADOS = 'descadastrados';

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
 * Os mйtodos de listagem possuem o parвmetro pagina. Ele informa qual pбgina
 * da pesquisa deve ser retornada. Atualmente o limite de contatos por pбgina
 * й de 25mil contatos por pбgina. Por isso, caso tenha 40mil contatos em sua
 * base por exemplo, precisarб fazer 2 chamadas passando o parвmetro pagina=1
 * (que devolverб os contatos de 1 a 24999) e em seguida pagina=2 (que
 * devolverб os contatos de 25000 a 40000)
 */

	/**
	 * Retorna contatos.
	 *
	 * @param string  status do contatos, podem ser validos, invalidos,
	 *  			  nao_confirmados ou descadastrados.
	 * @param integer pagina, nъmero da paginaзгo da busca.
	 * @param integer idLista, id da lista que estгo os contatos que se
	 *                deseja obter.
	 */
	public function obterContatos($status, $pagina=1, $idLista=0) {

		$listaParametro = '';
		if($idLista) {
			$listaParametro = "lista=$idLista&";
		}

		$url = $this->geraUrl() .
			"/{$status}?".$listaParametro."chave={$this->chave}&pagina={$pagina}";

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
	 * Faz a importaзгo dos contatos.
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

		$url = $this->geraUrl() ."/importacao?listas=" . implode(";", $listaIds). "&chave={$this->chave}";

		foreach($arrContatos as $numLine => $line){
			foreach($line as $key => $val) {
				$arrContatos[$numLine][$key] = utf8_encode($val);
			}
		}

		$contatosJson = json_encode($arrContatos);

		return $this->emktCore->enviaRequisicaoPost($url, $contatosJson);
	}

	/**
	 * Desativa um contato ou remove de lista caso seja passado o ID de Listas
	 *
	 * @param array $arrContatos
	 * @param array $listaIds
	 */
	public function desativar($arrContatos, $listaIds=array()){
		if(!is_array($arrContatos) || empty($arrContatos)){
			throw new EmktApiException("Array de contatos nao pode ser vazio.");
		}

		$url = $this->geraUrl() ."/desativacao?chave={$this->chave}";
		if(count($listaIds)>0){
			$url.= "&listas=" . implode(";", $listaIds);
		}

		foreach($arrContatos as $numLine => $line){
			foreach($line as $key => $val) {
				$arrContatos[$numLine][$key] = utf8_encode($val);
			}
		}

		$contatosJson = json_encode($arrContatos);

		return $this->emktCore->enviaRequisicaoPut($url, $contatosJson);
	}

	private function geraUrl() {
		return "http://{$this->hostName}{$this->hostNameSufix}/admin/api/" .
				"{$this->login}/contatos";
	}
}
?>