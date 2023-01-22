<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Repository\ProductsRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/admin/produits', name: 'admin_products')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductsRepository $productsRepository): Response
    {
        $products = $productsRepository->findAll();
        return $this->render('admin/products/index.html.twig',[
            'products' => $products
        ]);
    }


    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //on crée un nouveau produit
        $product = new Products();

        //on crée le formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        // on traite la requete du formulaire
        $productForm->handleRequest($request);

        //On vérifie si le formulaire est soumis et valide
        if ($productForm->isSubmitted() && $productForm->isValid()) {
            //on récupère les images
            $images = $productForm->get('images')->getData();


            if ($images) {
                // On crée le nom du fichier pour éviter doublon
                $originalFilename = pathinfo($images->getClientOriginalName(), PATHINFO_FILENAME);
                // On crée un slug associé à l'$originalFilename
                $safeFilename = $slugger->slug($originalFilename);
                // On reprend les 2étapes précédente, on ajoute un id unique et enfin l'extension du fichier
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $images->guessExtension();

                // Le fichier crée est déplacé vers le dossier où sont stockés les images
                try {
                    $images->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    "Le fichier de stockage des images n'est pas définit";
                }
                // Mis à jour de la propriété picture
                $product->setImage($newFilename);
            }
            $product->setSlug($slugger->slug($product->getName()));
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté avec succès');


            //On redirige
            return $this->redirectToRoute('admin_productsindex');
        }



        // return $this->render('admin/products/add.html.twig',[
        //     'productForm' => $productForm->createView()
        // ]);

        // on peut utiliser une autre moyen plus simple pour creer le formulaire 
        return $this->renderForm('admin/products/add.html.twig', compact('productForm'));
        //['productForm' => $productForm]
    }
    //--------------------------------------------------------------------/


    #[Route('/edit/{id}', name: 'edit')]

    public function edit(ProductsRepository $productsRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, $id): Response
    {
        $product = $productsRepository->find($id);

        // // on verifie si l'utilsateur peut éditer avec le Voter
        // $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //on crée le formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        // on traite la requete du formulaire
        $productForm->handleRequest($request);

        //On vérifie si le formulaire est soumis et valide
        if ($productForm->isSubmitted() && $productForm->isValid()) {
            //on génère le slug
            $slug = $slugger->slug($product->getName());

            $product->setSlug($slug);

            //on arrondit le prix en le multipliant par 100
            $prix = $product->getPrice() * 100;
            $product->setPrice($prix);

            //On stocke
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté avec succès');

            //On redirige
            return $this->redirectToRoute('admin_productsedit');
        }



        return $this->render('admin/products/edit.html.twig', [
            'productForm' => $productForm->createView(),
            'product' => $product
        ]);

        // on peut utiliser une autre moyen plus simple pour creer le formulaire 
        // return $this->renderForm('admin/products/edit.html.twig',[
        //     'productForm'=>$productForm,
        //     'product'=>$product
        // ]);
        // ['productForm' => $productForm,

        // ]





    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Products $product): Response
    {

        // on verifie si l'utilsateur peut supprimerer avec le Voter
        $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);
        return $this->render('admin/products/index.html.twig');
    }
}
