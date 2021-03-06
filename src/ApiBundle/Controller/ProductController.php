<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Product;
use ApiBundle\Form\ProductType;
use ApiBundle\Traits\FormErrorValidator;
use JMS\Serializer\SerializationContext;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
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
	use FormErrorValidator;

	/**
	 * @Route("/", name="products_main")
	 * @Method("GET")
	 */
	public function index(Request $request)
	{
		$search = $request->get('search', '');

         $products = $this->getDoctrine()
                          ->getRepository('ApiBundle:Product')
	                      ->findAllProducts($search);

		 $data = $this->get('ApiBundle\Service\Pagination\PaginationFactory')
		              ->paginate($products, $request, 'products_main');

         $products = $this->get('jms_serializer')
                          ->serialize($data,
	                                  'json',
                                      SerializationContext::create()->setGroups(['prod_index'])
	                          );

         return new Response($products, 200);
	}

	/**
	 * @Route("/{id}", name="products_get")
	 * @Method("GET")
	 */
	public function show(Product $product)
	{
		$product = $this->get('jms_serializer')->serialize(
													$product,
													'json',
													SerializationContext::create()->setGroups(['prod_index', 'prod_single'])
			);

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

		if(!$form->isValid()) {
			$errors = $this->getErrors($form);

			$validation = [
				'type' => 'validation',
				'description' => 'Validação Dados',
				'errors' => $errors
			];
			return new JsonResponse($validation);
		}

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

		if(!$form->isValid()) {
			$errors = $this->getErrors($form);

			$validation = [
				'type' => 'validation',
				'description' => 'Validação Dados',
				'errors' => $errors
			];

			return new JsonResponse($validation);
		}

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
