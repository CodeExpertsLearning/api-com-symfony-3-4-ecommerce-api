<?php
namespace ApiBundle\Service\Payment\PagSeguro\Method;


class Boleto extends Method
{
	private $creditials;

	public function __construct($creditials)
	{
		$this->creditials = $creditials;
	}

	public function proccess()
	{
		$boleto = new \PagSeguro\Domains\Requests\DirectPayment\Boleto();

		// Set the Payment Mode for this payment request
		$boleto->setMode('DEFAULT');

		$boleto->setReceiverEmail('nandokstro@gmail.com');

		// Set the currency
		$boleto->setCurrency("BRL");

		// Add an item for this payment request
		foreach (unserialize($this->order->getItems()) as $i) {
			$boleto->addItems()->withParameters(
				$i['id'],
				$i['name'],
				1,
				$i['price']
			);
		}

		// Set a reference code for this payment request. It is useful to identify this payment
		// in future notifications.
		$boleto->setReference("CEL-" . $this->order->getId());

		//set extra amount
		//$boleto->setExtraAmount(11.5);

		// Set your customer information.
		// If you using SANDBOX you must use an email @sandbox.pagseguro.com.br
		$userName = $this->order->getUser()->getFirstName() . ' ' . $this->order->getUser()->getLastName();
		$boleto->setSender()->setName($userName);
		$boleto->setSender()->setEmail('email@sandbox.pagseguro.com.br');
		$boleto->setSender()->setPhone()->withParameters(
			11,
			56273440
		);
		$boleto->setSender()->setDocument()->withParameters(
			'CPF',
			'13927261661'
		);
		$boleto->setSender()->setHash($this->hashUser);
		$boleto->setSender()->setIp('127.0.0.0');

		// Set shipping information for this payment request
		$boleto->setShipping()->setAddress()->withParameters(
			'Av. Brig. Faria Lima',
			'1384',
			'Jardim Paulistano',
			'01452002',
			'São Paulo',
			'SP',
			'BRA',
			'apto. 114'
		);

		// If your payment request don't need shipping information use:
//		$boleto->setShipping()->setAddressRequired()->withParameters('FALSE');

		$result = $boleto->register(
			$this->creditials
		);

		return $result;
	}
}