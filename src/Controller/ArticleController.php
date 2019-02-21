<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\ClientRepository;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="list_articles")
     */
    public function listArticles(ArticleRepository $repo, SerializerInterface $seri, Request $request, ClientRepository $clientRepo)
    {
        $authorization = $request->headers->get('authorization');
        if ($authorization) {
            $token = explode("Bearer ", $authorization);
            $client = $clientRepo->findOneBy(["fbToken" => $token[1]]);
            if($client) {
                // BASIC CODE SERIALIZATION
                $articles = $repo->findAll();
                $data = $seri->serialize($articles, 'json', SerializationContext::create()->setGroups(array('list')));
                return JsonResponse::fromJsonString($data);
            }
            $response = new Response();
            $response->setContent('ERREUR UTILISATEUR NON TROUVE');
            $response->headers->set('Content-Type', 'text/plain');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
        $response = new Response();
        $response->setContent('ERREUR AUTHORIZATION');
        $response->headers->set('Content-Type', 'text/plain');
        $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
        return $response;
    }

    /**
     * @Route("/article/{id}", name="show_article", requirements={"id"="\d+"})
     */
    public function showArticle(Article $article, SerializerInterface $seri)
    {
        $data = $seri->serialize($article, 'json', SerializationContext::create()->setGroups(array('detail')));

        return JsonResponse::fromJsonString($data);
    }
}
