<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[IsGranted('ROLE_ADMIN')]
class PostController extends AbstractController
{


    #[Route('/administrateur/article/liste', name: 'admin.post.index')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        return $this->render('page/admin/post/index.html.twig', compact('posts'));
    }


    #[Route('/administrateur/article/creation', name: 'admin.post.create', methods: array('GET', 'POST'))]
    public function create(Request $request, PostRepository $postRepository, CategoryRepository $categoryRepository): Response
    {

        if ( ! $categoryRepository->findAll() ) 
        {
            $this->addFlash('warning', "Vous devez créer au moins une catégorie avant de rédiger des articles.");
            return $this->redirectToRoute("admin.category.index");
        }

        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) 
        {
            $post->setAuthor($this->getUser());
            $postRepository->add($post, true);
            $this->addFlash("success", "L'article a été créé et sauvegardé.");
            return $this->redirectToRoute('admin.post.index');
        }

        return $this->renderForm('page/admin/post/create.html.twig', compact('form'));
    }


    #[Route('/administrateur/article/{id<\d+>}/modifier', name: 'admin.post.show', methods: array('GET'))]
    public function show(Post $post)
    {
        return $this->render("page/admin/post/show.html.twig", compact('post'));
    }


    #[Route('/administrateur/article/{id<\d+>}/publier', name: 'admin.post.published', methods: array('POST'))]
    public function publish(Post $post, PostRepository $postRepository)
    {
        if ( $post->getIsPublished() == false ) 
        {
            $post->setIsPublished(true);
            $post->setPublishedAt(new \DateTimeImmutable('now'));
            $postRepository->add($post, true);
            $this->addFlash('success', "Votre article a été publié!");
        }
        else 
        {
            $post->setIsPublished(false);
            $post->setPublishedAt(null);
            $postRepository->add($post, true);
            $this->addFlash('success', "Votre article a été retiré de la liste de publication!");
        }

        return $this->redirectToRoute("admin.post.index");
    }


}
