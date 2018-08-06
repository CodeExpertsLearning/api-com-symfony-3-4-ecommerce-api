<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\UserOrder;
use ApiBundle\Service\Payment\Factory\BuildMethod;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("checkout")
 */
class CheckoutController extends Controller
{
	/**
	 * @Route("/", name="checkout_index")
	 * @Method("POST")
	 */
	public function index(Request $request)
	{
		$data = $request->request->all();
		$userOrder = new UserOrder();
		$userOrder->setItems(serialize($data['items']));
		$userOrder->setUser($this->getUser());

		$manager = $this->getDoctrine()->getManager();
		$manager->persist($userOrder);
		$manager->flush();

		$payment = BuildMethod::build($data['method'], \PagSeguro\Configuration\Configure::getAccountCredentials());

		if($data['method'] == 'CREDIT_CARD') {
			$payment->tokenCard = $data['token'];
			$payment->installments = $data['installments'];
		}

		$payment->hashUser = $data['hash'];
		$payment->order = $userOrder;
		$payment->proccess();

		return new JsonResponse(['msg' => true]);
	}

	/**
	 * @Route("/session", name="checkout_session")
	 * @Method("GET")
	 */
	public function session()
	{
		try {
			$sessionCode = \PagSeguro\Services\Session::create(
				\PagSeguro\Configuration\Configure::getAccountCredentials()
			);

			return new JsonResponse(['session_id' => $sessionCode->getResult()]);

		} catch (\Exception $e) {
			return new JsonResponse(['error' => $e->getMessage()], 401);
		}
	}

	/**
	 * @Route("/notification", name="checkout_notification")
	 * @Method("POST")
	 */
	public function notification()
	{
		try {
			if (\PagSeguro\Helpers\Xhr::hasPost()) {
				$response = \PagSeguro\Services\Transactions\Notification::check(
					\PagSeguro\Configuration\Configure::getAccountCredentials()
				);
			} else {
				throw new \InvalidArgumentException($_POST);
			}

			$orderId = str_replace('CEL-', '', $response->getReference());

			$doctrine = $this->getDoctrine();
			$userOrder = $doctrine
			                  ->getRepository('ApiBundle:UserOrder')
							  ->find($orderId);

			$userOrder->setPagSeguroCode($response->getCode());
			$userOrder->setPagSeguroStatus($response->getStatus());

			$manager = $doctrine->getManager();
			$manager->merge($userOrder);
			$manager->flush();

			if($response->getStatus() == 3) {
				//TO-DO
				//Liberar produtos digital ou separar produtos para embalagem e posterior
				//entrega!
			}

			return new JsonResponse(['status' => true ], 200);
		} catch (\Exception $e) {
			return new JsonResponse(['error' => $e->getMessage()], 401);
		}
	}
}
