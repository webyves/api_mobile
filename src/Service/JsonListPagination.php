<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\CollectionRepresentation;

class JsonListPagination
{
    private $page;
    private $limit;
    private $offset;
    private $repo;

    public function __construct(Request $request, $repo)
    {
        $this->setPage($request);
        $this->setLimit($request);
        $this->offset = ($this->page - 1) * $this->limit;
        $this->repo = $repo;
    }

    private function setPage(Request $request)
    {
        $page = 1;
        if (null !== $request->query->get('page') && $request->query->get('page') > 0) {
            $page = $request->query->get('page');
        }
        $this->page = $page;
    }

    private function setLimit(Request $request)
    {
        $limit = 5;
        if (null !== $request->query->get('limit') && $request->query->get('limit') > 0) {
            $limit = $request->query->get('limit');
        }
        $this->limit = $limit;
    }

    public function getPaginatedItem($collection, $nbItems, $route)
    {
        $paginatedCollection = new PaginatedRepresentation(
            $collection,
            $route, // route
            array(), // route parameters
            $this->page,       // page number
            $this->limit,      // limit
            ceil($nbItems / $this->limit),       // total pages
            'page',  // page route parameter name, optional, defaults to 'page'
            'limit', // limit route parameter name, optional, defaults to 'limit'
            true,   // generate relative URIs, optional, defaults to `false`
            $nbItems       // total collection size, optional, defaults to `null`
        );
        return $paginatedCollection;
    }

    public function getUserClientPaginated($user)
    {
        $clients = $this->repo->findBy(["user" => $user], null, $this->limit, $this->offset);
        $nbitems = $this->repo->count(["user" => $user]);
        $collection = new CollectionRepresentation($clients, 'Client_Collection');
        return $this->getPaginatedItem($collection, $nbitems, 'user_client_list');
    }

    public function getArticlePaginated()
    {
        $articles = $this->repo->findBy([], null, $this->limit, $this->offset);
        $nbitems = $this->repo->count([]);
        $collection = new CollectionRepresentation($articles, 'Article_Collection');
        return $this->getPaginatedItem($collection, $nbitems, 'list_articles');
    }
}
