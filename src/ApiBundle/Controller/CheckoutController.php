<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\UserOrder;
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

		return new JsonResponse(['msg' => true]);
	}
}
