<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as Doc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use App\Service\JsonListPagination;

class ArticleController extends AbstractController
{
    /**
     * @Route("/api/articles", name="list_articles", methods={"GET"})
     * @Doc\Response(
     *     response=200,
     *     description="Get list of all our Articles."
     * )
     * @Doc\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     default="1",
     *     description="Number of the Page<br>Example: Add ?page=2 in the Url to get Page 2"
     * )
     * @Doc\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     default="5",
     *     description="Number of Articles per Page<br>Example: Add ?limit=50 in the Url to get 50 Articles"
     * )
     * @Doc\Tag(name="BileMo Articles")
     * @Security(name="Bearer")
     */
    public function apiListArticles(ArticleRepository $repo, SerializerInterface $seri, Request $request)
    {
        $user = $this->getUser();

        $jlp = new JsonListPagination($request, $repo);
        $PaginatedArticles = $jlp->getArticlePaginated();

        $data = $seri->serialize(
                    $PaginatedArticles, 
                    'json', 
                    SerializationContext::create()->setGroups(['Default', 'Article_Collection' => ['list']])
                );

        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route("/api/article/{id}", name="show_article", requirements={"id"="\d+"}, methods={"GET"})
     * @Doc\Response(
     *     response=200,
     *     description="Get Details of an Article.",
     *     @Model(type=Article::class)
     * )
     * @Doc\Response(
     *     response=404,
     *     description="This Article does not exist."
     * )
     * @Doc\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="Id of the Article"
     * )
     * @Doc\Tag(name="BileMo Articles")
     * @Security(name="Bearer")
     * @Cache(lastModified="article.getUpdatedDate()", public=true)
     */
    public function apiShowArticle(Article $article, SerializerInterface $seri)
    {
        $user = $this->getUser();
        $data = $seri->serialize($article, 'json', SerializationContext::create()->setGroups(array('detail'))->setSerializeNull('true'));
        return JsonResponse::fromJsonString($data);
    }
}
