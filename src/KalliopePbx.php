<?php

namespace fbarachino\kalliopepbx;

use Illuminate\Support\Facades\Http;

class KalliopePbx {

    private $address;
    private $port;
    private $protocol;
    private $username;
    private $password;

    public function __construct()
    {
        $this->address = config('kalliopepbx.address');
        $this->port = config('kalliopepbx.port');
        $this->protocol = config('kalliopepbx.protocol');
        $this->username = config('kalliopepbx.username');
        $this->password = config('kalliopepbx.password');
    }

    private function getSalt()
	{ 
		$response = Http::get($this->protocol.$this->address. $this->port .'/rest/salt/default',[
            'Accept' => 'application/json',
        ]);
        $xml_r=simplexml_load_string($response);
		$json_r=json_encode($xml_r);
		$arr_r=json_decode($json_r);
		return $arr_r->salt;
    }

	private function setHeader()
	{
		$salt = self::getSalt();
		$createdDT = now()->tz('UTC');
		$created= $createdDT->format('Y-m-d\TH:i:s\Z');
		$nonce = md5($created.uniqid(mt_rand(),true));
		$digestPassword = hash('sha256',$this->password.'{'.$salt.'}');
		$digest = base64_encode(hash('sha256',$nonce.$digestPassword.$this->username.'default'.$created,true));
		$header = 'RestApiUsernameToken Username="'.$this->username.'", Domain="default", Digest="'.$digest.'", Nonce="'.$nonce.'", Created="'.$created.'"';
		return $header;
	}

	public function sendRequest($url, $type = 'GET', $data = null)
	{
		$fullUrl = $this->protocol.$this->address. $this->port  .'/'.$url;
		$header = self::setHeader();

		// Mappa i metodi HTTP disponibili
		$httpMethods = [
			'GET' => 'get',
			'POST' => 'post',
			'PUT' => 'put',
			'DELETE' => 'delete',
		];

		// Verifica se il tipo di richiesta è valido
		if (!array_key_exists($type, $httpMethods)) {
			throw new \InvalidArgumentException("Invalid HTTP method: $type");
		}

		// Esegui la richiesta dinamicamente
		$request = Http::withHeaders([
			'X-Authenticate' => $header,
			'Accept' => 'application/json',
		])->{$httpMethods[$type]}($fullUrl, $data);

		return $request;
	}
}
