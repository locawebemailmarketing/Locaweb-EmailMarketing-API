<?php
require_once dirname(__FILE__).'/EmktCore.php';

/**
 *  Locaweb LTDA.
 *
 *  Está é uma API exemplo que facilita a utilização dos web services do Email Marketing.
 *
 * @version 0.1
 * @see http://wiki.locaweb.com.br/pt-br/APIs_do_Email_Marketing
 */
class RepositorioMensagens {

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
	public function RepositorioMensagens($hostName, $login, $chave,
		 $hostNameSufix='.locaweb.com.br', EmktCore $emktCore = null) {
		$this->hostName = $hostName;
		$this->login = $login;
		$this->chave = $chave;
		$this->hostNameSufix = $hostNameSufix;
		if($emktCore==null){
			$emktCore = new EmktCore();
		}
		$this->emktCore = $emktCore;
	}

	private function geraUrl() {
		return "http://{$this->hostName}{$this->hostNameSufix}/admin/api/" .
				"{$this->login}/mensagem";
	}

	/**
	 * @param array $atributosMensagem Os seguintes atributos
	 * devem necessariamente, mesmo que em branco estarem
	 * contidos no array:
	 *
	 * @see http://wiki.locaweb.com.br/pt-br/APIs_do_Email_Marketing#criacao
	 *
	 */
	public function adicionarMensagem($atributosMensagem) {
		if(empty($atributosMensagem)) {
			throw new EmktApiException('Atributos da mensagem nao podem estar vazios.');
		}
		// Faz o UTF8 Encode dos Atributos
		$atributosMensagem = $this->encodeUtf8($atributosMensagem);
		$atributosMensagemJson = json_encode($atributosMensagem);
		if($atributosMensagemJson==null){
			throw new EmktApiException('Atributos da mensagem invalidos.');
		}
		$url = $this->geraUrl() . "?chave={$this->chave}";

		$resultadoJson = $this->emktCore->enviaRequisicaoPost($url, $atributosMensagemJson);
		$resultado = json_decode($resultadoJson, true);

		return $resultado['id_mensagem'];
	}

	/**
	 * @param array $atributosAgendamento São os atributos para o agendamento
	 * @param string $mensagemId Id da mensagem.
	 *
	 * @see http://wiki.locaweb.com.br/pt-br/APIs_do_Email_Marketing#agendamento
	 *
	 */
	public function agendarMensagem($atributosAgendamento, $mensagemId) {
		if(empty($atributosAgendamento)) {
			throw new EmktApiException('Atributos da mensagem nao devem estar vazios.');
		}
		if(empty($mensagemId)) {
			throw new EmktApiException('Id da mensagem nao deve estar vazios.');
		}
		$atributosAgendamento = $this->encodeUtf8($atributosAgendamento);
		$atributosMensagemJson = json_encode($atributosAgendamento);
		$url = $this->geraUrl() . "/$mensagemId" . "?chave={$this->chave}";
		$resultadoJson = $this->emktCore->enviaRequisicaoPut($url, $atributosMensagemJson);
	}

	private function encodeUtf8($atributosMensagem) {
		$atributosMensagemUtf8 = array();
		foreach($atributosMensagem as $key => $value) {
			$atributosMensagemUtf8[$key] = utf8_encode($value);
		}

		return $atributosMensagemUtf8;
	}
}
?>
