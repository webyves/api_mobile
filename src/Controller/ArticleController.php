<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Article;
use App\Repository\ArticleRepository;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

class ArticleController extends AbstractController
{
    /**
     * @Route("/api/articles", name="list_articles", methods={"GET"})
     */
    public function apiListArticles(ArticleRepository $repo, SerializerInterface $seri)
    {
        $user = $this->getUser();
        $articles = $repo->findAll();
        $data = $seri->serialize($articles, 'json', SerializationContext::create()->setGroups(array('list')));
        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route("/api/article/{id}", name="show_article", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function apiShowArticle(Article $article, SerializerInterface $seri)
    {
        $user = $this->getUser();
        $data = $seri->serialize($article, 'json', SerializationContext::create()->setGroups(array('detail'))->setSerializeNull('true'));
        return JsonResponse::fromJsonString($data);
    }
}
