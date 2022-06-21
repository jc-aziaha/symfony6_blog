<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
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
    public function create(Request $request, PostRepository $postRepository): Response
    {
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
}
