<?php

namespace TestBlog\Controller\api;

use Doctrine\ORM\Tools\Pagination\Paginator;
use TestBlog\Kernel\App;
use TestBlog\DTO\BlogResultDTO;
use TestBlog\Entity\Blog;
use TestBlog\Kernel\Request;
use TestBlog\Kernel\RequestParams;

class BlogController extends ApiController
{

    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 5;
    public $requireAuth = true;

    public function getOptions(): array
    {
        return [
            'list' => [Request::METHOD_POST, Request::METHOD_OPTIONS],
        ];
    }

    /**
     * @param RequestParams $requestParams
     * @return array
     */
    public function list(RequestParams $requestParams)
    {
        $page = $requestParams->getParam('page', self::DEFAULT_PAGE, false);
        $limit = $requestParams->getParam('limit', self::DEFAULT_LIMIT, false);
        $em = App::getInstance()->db->entityManager;
        $blogRepository = $em->getRepository(Blog::class);
        $result = [];
        $totalCount = $blogRepository->count([]);
        $query = $blogRepository->createQueryBuilder('b')->setFirstResult(($page - 1) * self::DEFAULT_LIMIT)->setMaxResults(self::DEFAULT_LIMIT)->orderBy('b.id', 'ASC');
        $paginator = new Paginator($query, true);
        $c = count($paginator);
        /** @var Blog $blogRecord */
        foreach ($paginator as $blogRecord) {
            $blogResultDTO = new BlogResultDTO();
            $blogResultDTO->id = $blogRecord->getId();
            $blogResultDTO->title = $blogRecord->getTitle();
            $blogResultDTO->content = $blogRecord->getContent();
            $blogResultDTO->author = $blogRecord->getUser()->getName();
            $blogResultDTO->created_at = $blogRecord->getCreatedAt()->format('d.m.Y H:i:s');
            $result[] = $blogResultDTO->toArray();
        }
        return [
            'blog' => $result,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'totalPages' => (int) ceil($totalCount / $limit)
            ]
        ];
    }
}
