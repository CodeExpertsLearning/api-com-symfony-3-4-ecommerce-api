<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\User;
use ApiBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/users")
 */
class UserController extends Controller
{
	/**
	 * @Route("/", name="users_index")
	 * @Method("GET")
	 */
	public function index()
	{
		$users = $this->getDoctrine()
		                   ->getRepository('ApiBundle:User')
		                   ->findAll();

		$users = $this->get('jms_serializer')->serialize($users, 'json');

		return new Response($users, 200);
	}

	/**
	 * @Route("/{id}", name="users_show")
	 * @Method("GET")
	 */
	public function show(User $user)
	{
		$user = $this->get('jms_serializer')->serialize($user, 'json');

		return new Response($user, 200);
	}

	/**
	 * @Route("/", name="users_save")
	 * @Method("POST")
	 */
	public function save(Request $request)
	{
		$data = $request->request->all();

		$user = new User();
		$form = $this->createForm(UserType::class, $user);
		$form->submit($data);

		$doctrine = $this->getDoctrine()->getManager();
		$doctrine->persist($user);
		$doctrine->flush();

		return new JsonResponse(['msg' => 'Usuário Salvo com Sucesso!'], 200);
	}

	/**
	 * @Route("/", name="users_update")
	 * @Method("PUT")
	 */
	public function update(Request $request)
	{
		$data = $request->request->all();

		$user = $this->getDoctrine()
		                 ->getRepository('ApiBundle:User')
		                 ->find($data['id'])
		;

		if(!$user) {
			return $this->createNotFoundException('Usuário não encontrado!');
		}

		$form = $this->createForm(UserType::class, $user);
		$form->submit($data);

		$doctrine = $this->getDoctrine()->getManager();
		$doctrine->merge($user);
		$doctrine->flush();

		return new JsonResponse(['msg' => 'Usuário Atualizado com Sucesso!'], 200);
	}

	/**
	 * @Route("/{id}")
	 * @Method("DELETE")
	 */
	public function delete(User $user)
	{
		$doctrine = $this->getDoctrine()->getManager();
		$doctrine->remove($user);
		$doctrine->flush();

		return new JsonResponse(['msg' => 'Usuário Removido com Sucesso!'], 200);
	}
}
