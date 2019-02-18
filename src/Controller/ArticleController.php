<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;
use App\Repository\ArticleRepository;

use JMS\Serializer\SerializerInterface;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="list_articles")
     */
    public function listArticles(ArticleRepository $repo, SerializerInterface $seri)
    {
    	$articles = $repo->findAll();
        $data = $seri->serialize($articles, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/article/{id}", name="show_article", requirements={"id"="\d+"})
     */
    public function showArticle(Article $article, SerializerInterface $seri)
    {
        $data = $seri->serialize($article, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
