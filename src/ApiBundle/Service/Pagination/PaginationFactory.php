<?php
namespace ApiBundle\Service\Pagination;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

class PaginationFactory
{
	/**
	 * @var Router
	 */
	private $router;

	public function __construct(Router $router)
	{
		$this->router = $router;
	}

	public function paginate(QueryBuilder $qb, Request $request, $route, $routeParams = [])
	{
		$pageCurrent = $request->get('page', 1);

		$adapter = new DoctrineORMAdapter($qb);
		$pagerFanta = new Pagerfanta($adapter);
		$pagerFanta->setMaxPerPage(3);
		$pagerFanta->setCurrentPage($pageCurrent);

		$products = [];

		foreach ($pagerFanta->getCurrentPageResults() as $p) {
			$products[] = $p;
		}

		$data = [
			'data' => $products,
			'total' => $pagerFanta->getNbResults(),
			'count' => count($products)
		];

		$routeParams = array_merge($request->query->all(), $routeParams);

		$generateUrlPagination = function($page) use($route, $routeParams) {
			return $this->router->generate($route, array_merge(
				$routeParams,
				['page' => $page]
			));
		};

		$data['_links'] = [
			'self' => $generateUrlPagination($pageCurrent),
			'first' => $generateUrlPagination(1),
			'last'  => $generateUrlPagination($pagerFanta->getNbPages()),
		];

		if($pagerFanta->hasPreviousPage()) {
			$data['_links']['prev'] = $generateUrlPagination($pagerFanta->getPreviousPage());
		}

		if($pagerFanta->hasNextPage()) {
			$data['_links']['next'] = $generateUrlPagination($pagerFanta->getNextPage());
		}

		return $data;
	}
}