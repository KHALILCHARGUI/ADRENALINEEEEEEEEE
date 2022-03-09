<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Form\SponsorType;
use App\Repository\SponsorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @Route("/sponsor")
 */
class SponsorController extends AbstractController
{
    
     /**
     * @Route("/", name="sponsor_affichage", methods={"GET"})
     */
    public function affichagee(SponsorRepository $sponsorRepository): Response
    {
        return $this->render('sponsor/affichersponsor.html.twig', [
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }
      /**
     * @Route("addspon", name="addspon", methods={"GET"})
     */
    public function affichagadspon(SponsorRepository $sponsorRepository): Response
    {
        return $this->render('sponsor/affaddspon.html.twig', [
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }


      /**
     * @Route("spon", name="spon", methods={"GET"})
     */
    public function aff_spon(SponsorRepository $sponsorRepository): Response
    {
        return $this->render('sponsor/afficherspon.html.twig', [
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }





    /**
     * @Route("/AllSponsor", name="AllSponsor")
     */
    public function AllSponsor(NormalizerInterface $Normalizer )
    {
    //Nous utilisons la Repository pour récupérer les objets que nous avons dans la base de données
    $repository =$this->getDoctrine()->getRepository(Sponsor::class);
    $sponsors=$repository->FindAll();
    //Nous utilisons la fonction normalize qui transforme en format JSON nos donnée qui sont
    //en tableau d'objet Students
    $jsonContent=$Normalizer->normalize($sponsors,'json',['groups'=>'post:read']);
    
    
    
    return new Response(json_encode($jsonContent));
    dump($jsonContent);
    die;}



















    /**
     * @Route("/", name="sponsor_index", methods={"GET"})
     */
    public function index(SponsorRepository $sponsorRepository): Response
    {
        return $this->render('sponsor/index.html.twig', [
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sponsor_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sponsor = new Sponsor();
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sponsor);
            $entityManager->flush();

            return $this->redirectToRoute('sponsor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sponsor/new.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sponsor_show", methods={"GET"})
     */
    public function show(Sponsor $sponsor): Response
    {
        return $this->render('sponsor/show.html.twig', [
            'sponsor' => $sponsor,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sponsor_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sponsor $sponsor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('sponsor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sponsor/edit.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sponsor_delete", methods={"POST"})
     */
    public function delete(Request $request, Sponsor $sponsor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sponsor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sponsor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sponsor_index', [], Response::HTTP_SEE_OTHER);
    }






    
      /**
     * @Route("/Sponsor/{id}", name="Sponsor/{id}")
     */
    public function Sponsorid(Request $request,$id,NormalizerInterface $Normalizer )
    {
    //Nous utilisons la Repository pour récupérer les objets que nous avons dans la base de données
    $em=$this->getDoctrine()->getManager();
    $sponsors =$em->getRepository(Sponsor::class)->find($id);
    
    //Nous utilisons la fonction normalize qui transforme en format JSON nos donnée qui sont
    //en tableau d'objet Students
    $jsonContent=$Normalizer->normalize($sponsors,'json',['groups'=>'post:read']);
    
 
return new Response(json_encode($jsonContent));

}
    /**
     * @Route("/AddSponsor/new", name="AddSponsor/new")
     */
    public function AddSponsor(Request $request, NormalizerInterface $Normalizer )
    {
    //Nous utilisons la Repository pour récupérer les objets que nous avons dans la base de données
   
    //Nous utilisons la fonction normalize qui transforme en format JSON nos donnée qui sont
    //en tableau d'objet Students
    $em=$this->getDoctrine()->getManager();
    $sponsors=new Sponsor();
    $sponsors-> setNomSp($request->get('nom_sp'));
    
    $sponsors->setNumSp($request->get('num_sp'));
    $sponsors->setEmailSp($request->get('email_sp'));
    $em->persist($sponsors);
    $em->flush();
    $jsonContent=$Normalizer->normalize($sponsors,'json',['groups'=>'post:read']);
    
    return new Response(json_encode($jsonContent));
      
    
   

}





}
