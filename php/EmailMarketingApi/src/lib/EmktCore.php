<?php
require_once dirname(__FILE__).'/EmktApiException.php';
/**
 * API de exemplo para uso dos web services do Email Marketing.
 *
 * @version 0.1
 * @see http://wiki.locaweb.com.br/pt-br/APIs_do_Email_Marketing
 */
class EmktCore {

	/**
	 * @param string url
	 */
	public function enviaRequisicaoGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$resultado_http = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		$this->validaCodigoHttp($http_code, $resultado_http);

		return $resultado_http;
	}

	/**
	 * @param string url
	 * @param string dadosPost
	 */
	public function enviaRequisicaoPost($url, $dadosPost) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($curl, CURLOPT_POST, 1 );
		curl_setopt($curl, CURLOPT_POSTFIELDS, $dadosPost);
		$resultado_http = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		$this->validaCodigoHttp($http_code, $resultado_http);

		return $resultado_http;
	}

	function validaCodigoHttp($http_code, $resultado_http='') {
		if(empty($http_code)) {
			throw new EmktApiException("Erro inesperado, falta algum " .
					"parametro na url ou algum problema na rede.");
		}
		if ($http_code != '200' && $http_code != '201') {
			throw new EmktApiException("Erro inesperado: " .
					"statusCode:$http_code, mensagem:$resultado_http");
		}
	}
}
?>
