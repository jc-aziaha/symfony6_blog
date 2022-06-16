<?php

namespace App\Controller\Admin\Category;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[IsGranted('ROLE_ADMIN')]
class CategoryController extends AbstractController
{

    #[Route('/administrateur/categorie/liste', name: 'admin.category.index')]
    public function index(): Response
    {
        return $this->render('page/admin/category/index.html.twig');
    }

    #[Route('/administrateur/categorie/insertion', name: 'admin.category.create')]
    public function create(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) 
        {
            $categoryRepository->add($category, true);
            $this->addFlash('success', 'Votre catégorie a été créée!');
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->renderForm('page/admin/category/create.html.twig', compact('form'));

        // return $this->render('page/admin/category/create.html.twig', array(
        //     'form' => $form->createView()
        // ));
    }
}
