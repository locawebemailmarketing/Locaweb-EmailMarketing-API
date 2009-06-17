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
		 $hostNameSufix='locaweb.com.br', EmktCore $emktCore = null) {
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

	private function geraUrl() {
		return "http://{$this->hostName}.{$this->hostNameSufix}/admin/api/" .
				"{$this->login}/contatos/mensagem";
	}

	/**
	 * @param array $atributosMensagem Os seguintes atributos
	 * devem necessariamente, mesmo que em branco estarem
	 * contidos no array:
	 *
	 * identificador: Um nome que identifique a mensagem. Ex: "Mensagem 001"
	 * assunto: Titulo da mensagem. Ex: "Promoção queima de estoque"
	 * nome_remetente: O nome do remetente da mensagem. Ex: "Loja Certa"
	 * email_remetente: Email do remetente. Ex: duvida??
	 * dominio_dos_links:
	 * id_campanha: Numero de identificacao da campanha. Ex: 326545
	 * formato: A mensagem pode ser enviada no formato 'Texto' ou 'HTML ou
	 * 			em ambos os formato 'Texto ou Html'.
	 * url_mensagem_html: O link para o conteudo da mensagem HTML. Ex:
	 * 					  http//newnsLetter.meu.dominio.com.br
	 * mensagem_texto: Mensagem texto. Ex: 'Minha mensagem de texto.'
	 * incluir_link_visualizacao: Opcao de incluir o link de visualizacao, valores
	 * 							  possiveis 'true' ou 'false'
	 * texto_link_visualizacao: Texto para descadastramento de usuario,
	 *                          eh importante que o texto contenha alguma
	 *                          frase entre '['. Ex:  Caso nao visualize
	 *                          esse email adequadamente [acesse este link]
	 */
	public function adicionarMensagem($atributosMensagem) {
		if(empty($atributosMensagem)) {
			throw new EmktApiException('Atributos da mensagem nao devem estar vazios.');
		}
		// Faz o UTF8 Encode dos Atributos
		$atributosMensagem = $this->encodeUtf8($atributosMensagem);
		$atributosMensagemJson = json_encode($atributosMensagem);
		if($atributosMensagemJson==null){
			throw new EmktApiException('Atributos da mensagem invalidos.');
		}
		$url = $this->geraUrl() .  "?chave={$this->chave}";

		return $this->emktCore->enviaRequisicaoPost($url, $atributosMensagemJson);
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
