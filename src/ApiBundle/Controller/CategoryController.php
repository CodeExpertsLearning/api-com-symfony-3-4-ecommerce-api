<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Category;
use ApiBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/categories")
 */
class CategoryController extends Controller
{
	/**
	 * @Route("/", name="categories_index")
	 * @Method("GET")
	 */
	public function index()
	{
		$categories = $this->getDoctrine()
	                   ->getRepository('ApiBundle:Category')
	                   ->findAll();

		$categories = $this->get('jms_serializer')->serialize($categories, 'json');

		return new Response($categories, 200);
	}

	/**
	 * @Route("/{id}", name="categories_show")
	 * @Method("GET")
	 */
	public function show(Category $category)
	{
		$category = $this->get('jms_serializer')->serialize($category, 'json');

		return new Response($category, 200);
	}

	/**
	 * @Route("/", name="categories_save")
	 * @Method("POST")
	 */
	public function save(Request $request)
	{
		$data = $request->request->all();

		$category = new Category();
		$form = $this->createForm(CategoryType::class, $category);
		$form->submit($data);

		$doctrine = $this->getDoctrine()->getManager();
		$doctrine->persist($category);
		$doctrine->flush();

		return new JsonResponse(['msg' => 'Categoria Salva com Sucesso!'], 200);
	}

	/**
	 * @Route("/", name="categories_update")
	 * @Method("PUT")
	 */
	public function update(Request $request)
	{
		$data = $request->request->all();

		$category = $this->getDoctrine()
		                 ->getRepository('ApiBundle:Category')
					     ->find($data['id'])
		;

		if(!$category) {
			return $this->createNotFoundException('Categoria não encontrada!');
		}

		$form = $this->createForm(CategoryType::class, $category);
		$form->submit($data);

		$doctrine = $this->getDoctrine()->getManager();
		$doctrine->merge($category);
		$doctrine->flush();

		return new JsonResponse(['msg' => 'Categoria Atualizada com Sucesso!'], 200);
	}

	/**
	 * @Route("/{id}")
	 * @Method("DELETE")
	 */
	public function delete(Category $category)
	{
		$doctrine = $this->getDoctrine()->getManager();
		$doctrine->remove($category);
		$doctrine->flush();

		return new JsonResponse(['msg' => 'Categoria Removida com Sucesso!'], 200);
	}
}