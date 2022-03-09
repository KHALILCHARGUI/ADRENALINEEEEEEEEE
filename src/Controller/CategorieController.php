<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController


{




/**
     * @Route("/AllCategorie", name="AllCategorie")
     */
    public function AllCategorie(NormalizerInterface $Normalizer )
    {
    //Nous utilisons la Repository pour récupérer les objets que nous avons dans la base de données
    $repository =$this->getDoctrine()->getRepository(Categorie::class);
    $categories=$repository->FindAll();
    //Nous utilisons la fonction normalize qui transforme en format JSON nos donnée qui sont
    //en tableau d'objet Students
    $jsonContent=$Normalizer->normalize($categories,'json',['groups'=>'post:read']);
    
    
    
    return new Response(json_encode($jsonContent));
    dump($jsonContent);
    die;}
    















      
     /**
     * @Route("/addca", name="addca", methods={"GET"})
     */
    public function addca(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/addca.html.twig', [
           'categories' => $categorieRepository->findAll(),
   ]);

    }

    /**
     * @Route("/affca", name="affca", methods={"GET"})
     */
    public function affca(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/affca.html.twig', [
           'categories' => $categorieRepository->findAll(),
   ]);

    }







      /**
     * @Route("/affichagecategorie", name="affichagecaa", methods={"GET"})
     */
    public function affg(CategorieRepository $categorieRepository): Response
     {
         return $this->render('categorie/showbackc.html.twig', [
            'categories' => $categorieRepository->findAll(),
    ]);

     }
    /**
     * @Route("/affichage", name="affichageca", methods={"GET"})
     */
    public function aff(CategorieRepository $categorieRepository): Response
     {
         return $this->render('categorie/affichercategorie.html.twig', [
            'categories' => $categorieRepository->findAll(),
    ]);

     }
    /**
     * @Route("/", name="categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="categorie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categorie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index', [], Response::HTTP_SEE_OTHER);
    }
























      /**
     * @Route("/Categorie/{id}", name="Categorie/{id}")
     */
    public function Categorieid(Request $request,$id,NormalizerInterface $Normalizer )
    {
    //Nous utilisons la Repository pour récupérer les objets que nous avons dans la base de données
    $em=$this->getDoctrine()->getManager();
    $categories =$em->getRepository(Categorie::class)->find($id);
    
    //Nous utilisons la fonction normalize qui transforme en format JSON nos donnée qui sont
    //en tableau d'objet Students
    $jsonContent=$Normalizer->normalize($categories,'json',['groups'=>'post:read']);
    
 
return new Response(json_encode($jsonContent));

}
    /**
     * @Route("/AddCategorie/new", name="AddCategorie/new")
     */
    public function AddCategorie(Request $request, NormalizerInterface $Normalizer )
    {
    //Nous utilisons la Repository pour récupérer les objets que nous avons dans la base de données
   
    //Nous utilisons la fonction normalize qui transforme en format JSON nos donnée qui sont
    //en tableau d'objet Students
    $em=$this->getDoctrine()->getManager();
    $categories=new Categorie();
    $categories->setNomCa($request->get('nom_ca'));
    
    $em->persist($categories);
    $em->flush();
    $jsonContent=$Normalizer->normalize($categories,'json',['groups'=>'post:read']);
    
    return new Response(json_encode($jsonContent));
      
    
   

}















  
}
