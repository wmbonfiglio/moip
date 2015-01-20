<?php namespace SOSTheBlack\Moip\Controllers;

use Route;
use View;
use MoipApi;
use Moip;
use Input;
use BaseController;
use Request;

class MoipController extends BaseController
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/

	private $moip;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/

	private $data = [
		"Forma" 		=> "",
		"Instituicao" 	=> "",
	    "Parcelas"		=> "",
	    "CartaoCredito" => [
	        "Numero" 		 => "",
	        "Expiracao" 	 => "",
	        "Cofre"			 => "",
	        "CodigoSeguranca"=> "",
	        "Portador" 		 => [
	        	"Nome" 			=> "",
	            "DataNascimento"=> "",
	            "Telefone" 		=> "",
	            "Identidade" 	=> ""
	        ]
	    ]
	];

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $response;

	public function response()
	{
		if (Request::isMethod('post')) {
			$this->response = (object) Input::except('_token');
		}
		return $this->response;
	}

	/**
	 * initialize
	 * 
	 * @return void
	 */
	private function initialize(array $data, $token)
	{
		$this->moip = Moip::firstOrFail();
		$this->data = array_replace_recursive($this->data, $data);
		if (empty($this->data['CartaoCredito']['Cofre'])) {
			unset($this->data['CartaoCredito']['Cofre']);
		}
		$this->data['token'] 		= $this->token($token);
		$this->data['environment'] 	= $this->environment();
	}

	private function token($token)
	{
		return $token ? $token : MoipApi::response()->token;
	}

	/**
	 * transparent
	 * 
	 * @param array $data 
	 * @param string $token
	 * @return Illuminate\View\Factory
	 */
	public function transparent(array $data, $token = null)
	{
		$this->initialize($data, $token);
		if (Request::isMethod('post')) {
			$this->response = Input::except('_token');
			dd($this->response);
			return $this->response;
		} elseif (! empty($this->response)){
			return $this->response;
		} else {
			return View::make('sostheblack::moip')->withMoip($this->data);
		}
	}

	/**
	 * environment
	 * 
	 * @return string
	 */
	private function environment()
	{
		$environment = "";

		if ((boolean) $this->moip->environment === true) {
			$environment = "https://www.moip.com.br/transparente/MoipWidget-v2.js";
		} else {
			$environment = "https://desenvolvedor.moip.com.br/sandbox/transparente/MoipWidget-v2.js";
		}

		return $environment;
	}
}