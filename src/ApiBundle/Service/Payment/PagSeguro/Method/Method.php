<?php
namespace ApiBundle\Service\Payment\PagSeguro\Method;


abstract class Method
{
	public $tokenCard;

	public $installments;

	public $hashUser;

	public $order;

	abstract public function proccess();
}