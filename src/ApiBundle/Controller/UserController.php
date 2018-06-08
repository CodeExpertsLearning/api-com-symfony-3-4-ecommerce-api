<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\User;
use ApiBundle\Form\UserType;
use ApiBundle\Traits\FormErrorValidator;
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
	use FormErrorValidator;

	/**
	 * @Route("/", name="users_index")
	 * @Method("GET")
	 */
	public function index(Request $request)
	{
		$users = $this->getDoctrine()
		                   ->getRepository('ApiBundle:User')
		                   ->findAllUsers();

		$data = $this->get('ApiBundle\Service\Pagination\PaginationFactory')
		             ->paginate($users, $request, 'users_index');

		$users = $this->get('jms_serializer')->serialize($data, 'json');

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
		$data['password'] = $this->get('security.password_encoder')
		                         ->encodePassword($user, $data['password']);

		$form = $this->createForm(UserType::class, $user);
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
		unset($data['id']);

		if(!$user) {
			return $this->createNotFoundException('Usuário não encontrado!');
		}

		$data['password'] = $this->get('security.password_encoder')
		                         ->encodePassword($user, $data['password']);

		$form = $this->createForm(UserType::class, $user);
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
