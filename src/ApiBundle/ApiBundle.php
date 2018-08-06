<?php

namespace ApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApiBundle extends Bundle
{
	public function boot()
	{
		putenv('PAGSEGURO_ENV=sandbox');
		putenv('PAGSEGURO_EMAIL=nandokstro@gmail.com');
		putenv('PAGSEGURO_TOKEN_SANDBOX=74AC9F13254844E592C46F81A546A41B');

		\PagSeguro\Library::initialize();
		\PagSeguro\Library::cmsVersion()->setName("API")->setRelease("1.0.0");
		\PagSeguro\Library::moduleVersion()->setName("Symfony")->setRelease("1.0.0");
	}
}
