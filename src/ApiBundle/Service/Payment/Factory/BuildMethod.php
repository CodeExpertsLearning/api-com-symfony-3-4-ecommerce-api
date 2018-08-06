<?php
namespace ApiBundle\Service\Payment\Factory;


use ApiBundle\Service\Payment\PagSeguro\Method\Boleto;
use ApiBundle\Service\Payment\PagSeguro\Method\CreditCard;

class BuildMethod
{
	private function __construct() {}

	public static function build($method, $credentials)
	{
		switch ($method) {
			case 'BOLETO':
				return new Boleto($credentials);
			break;

			case 'CREDIT_CARD':
				return new CreditCard($credentials);
			break;
		}
	}
}