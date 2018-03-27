<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Product;
use ApiBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/products")
 */
class ProductController extends Controller
{
	/**
	 * @Route("/", name="products_main")
	 * @Method("GET")
	 */
	public function index()
	{
         $products = $this->getDoctrine()
                          ->getRepository('ApiBundle:Product')
	                      ->findAll();

         $products = $this->get('jms_serializer')->serialize($products, 'json');

         return new Response($products, 200);
	}

	/**
	 * @Route("/{id}", name="products_get")
	 * @Method("GET")
	 */
	public function show(Product $product)
	{
		$product = $this->get('jms_serializer')->serialize($product, 'json');

		return new Response($product, 200);
	}

	/**
	 * @Route("/", name="products_save")
	 * @Method("POST")
	 */
	public function save(Request $request)
	{
		$data = $request->request->all();

		$doctrine = $this->getDoctrine()->getManager();

		$product = new Product();
		$form = $this->createForm(ProductType::class, $product);
		$form->submit($data);

		$doctrine->persist($product);
		$doctrine->flush();

		return new JsonResponse(['msg' => 'Produto Inserido com Sucesso!'], 200);
	}

	/**
	 * @Route("/", name="products_update")
	 * @Method("PUT")
	 */
	public function update(Request $request)
	{
		$data = $request->request->all();

		$doctrine = $this->getDoctrine();
		$manager = $doctrine->getManager();

		$product = $doctrine->getRepository('ApiBundle:Product')
						    ->find($data['id']);

		if(!$product) {
			return $this->createNotFoundException('Produto não encontrado!');
		}

		$form = $this->createForm(ProductType::class, $product);
		$form->submit($data);

		$manager->merge($product);
		$manager->flush();

		return new JsonResponse(['msg' => 'Produto Atualizado com Sucesso!'], 200);
	}

	/**
	 * @Route("/{id}", name="products_delete")
	 * @Method("DELETE")
	 */
	public function delete(Product $product)
	{
		$doctrine = $this->getDoctrine()->getManager();

		$doctrine->remove($product);
		$doctrine->flush();

		return new JsonResponse(['msg' => 'Produto Removido com Sucesso!'], 200);
	}

}
