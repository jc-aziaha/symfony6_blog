<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[IsGranted('ROLE_ADMIN')]
class PostController extends AbstractController
{


    #[Route('/administrateur/article/liste', name: 'admin.post.index')]
    public function index(): Response
    {
        return $this->render('page/admin/post/index.html.twig');
    }


    #[Route('/administrateur/article/creation', name: 'admin.post.create')]
    public function create(): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        return $this->renderForm('page/admin/post/create.html.twig', compact('form'));
    }
}
