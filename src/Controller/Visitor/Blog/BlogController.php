<?php

namespace App\Controller\Visitor\Blog;

use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/blog/liste-des-articles', name: 'visitor.blog.index')]
    public function index(
        CategoryRepository $categoryRepository, 
        TagRepository $tagRepository, 
        PostRepository $postRepository
    ): Response
    {
        $categories = $categoryRepository->findAll();
        $tags       = $tagRepository->findAll();
        $posts      = $postRepository->findBy(array('isPublished' => true));

        return $this->render('page/visitor/blog/index.html.twig', compact('categories', 'tags', 'posts'));
    }
}
