<?php
namespace ApiBundle\Traits;

use Symfony\Component\Form\FormInterface;

trait FormErrorValidator
{
	public function getErrors(FormInterface $form)
	{
		$errors = [];
		foreach($form->getErrors() as $e) {
			$errors[] = $e->getMessage();
		}

		foreach($form->all() as $childForm) {
			if($childForm instanceof  FormInterface) {
				if($e = $this->getErrors($childForm)) {
					$errors[$childForm->getName()] = $e;
				}
			}
		}

		return $errors;
	}
}