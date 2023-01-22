<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesFormType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted ('ROLE_ADMIN', message: "Vous n'avez pas le droit d'accéder à cette ressource")]
class CategoriesController extends AbstractController


{
    #[Route ('admin/categories/create', name: 'categories_create')]
    public function create() {
        return $this->render('admin/categories/create.html.twig');
    }



    #[Route ('admin/categories/{id}/edit', name: 'categories_edit')]
    
    public function edit($id, CategoriesRepository $categoriesRepository, Request $request,EntityManagerInterface $em, SluggerInterface $slugger): Response
     {
        $category = $categoriesRepository->find($id);

        // // on verifie si l'utilsateur peut éditer avec le Voter
        // $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);
        // $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'avez pas le droit d'accéder à cette ressource");

        //on crée le formulaire
        $categoryForm = $this->createForm(CategoriesFormType::class, $category);
           
        //on traite la requete du formulaire
        $categoryForm->handleRequest($request);

        //on verifie si le formulaire est soumis et validé
        if($categoryForm->isSubmitted()  && $categoryForm->isValid()) { 
            //on génère le slug
            $slug = $categoryForm->getSlug();

            $category->setSlug($slug);

            //on stocke 
            $em->persist($category);
            $em->flush();

            //on redirige
            return $this->redirectToRoute('categories_edit');
        }
        return $this->render('admin/categories/edit.html.twig', [
            'category' => $category,
            
        ]);
    }
}