<?php

namespace App\Controller;

use App\Entity\Equipement;
use App\Entity\Images;

use App\Form\EquipementType;
use App\Repository\EquipementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/equipement")
 */
class EquipementController extends AbstractController
{
    
    /**
     * @Route("/affichageaj", name="affichageeqaj", methods={"GET"})
     */
    public function affaj(EquipementRepository $equipementRepository): Response
     {
         return $this->render('equipement/showbackaj.html.twig', [
            'equipements' => $equipementRepository->findAll(),
    ]);

     }

     /**
     * @Route("/liste", name="Equipement_list", methods={"GET"})
     */
    
    public function liste(EquipementRepository $EquipementRepository): Response
    {
       
       
       $pdfOptions = new Options();
       $pdfOptions->set('defaultFont', 'Arial');
       
       // Instantiate Dompdf with our options
       $dompdf = new Dompdf($pdfOptions);
       
       $Equipements = $EquipementRepository->findAll();

       
       // Retrieve the HTML generated in our twig file
       $html = $this->renderView('equipement/liste.html.twig',[
           'equipements'=> $Equipements,
       ]);
       
       // Load HTML to Dompdf
       $dompdf->loadHtml($html);
       
       // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
       $dompdf->setPaper('A4', 'portrait');

       // Render the HTML as PDF
       $dompdf->render();

       // Output the generated PDF to Browser (force download)
       $dompdf->stream("mypdf.pdf", [
           "Attachment" => false
       ]);
   }



     /**
     * @Route("/addeq", name="addeq", methods={"GET"})
     */
    public function affaddeq(EquipementRepository $equipementRepository): Response
    {
        return $this->render('equipement/addeq.html.twig', [
           'equipements' => $equipementRepository->findAll(),
   ]);

    }










    /**
     * @Route("/affichage", name="affichageeq")
     */
    public function aff( Request $request, PaginatorInterface $paginator)
     {

        $donnees = $this->getDoctrine()->getRepository(Equipement::class)->findBy([]);

        $equipements = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            4
        );






         return $this->render('equipement/afficherequipement.html.twig', [
            'equipements' => $equipements,

    ]);

     }







    /**
     * @Route("/", name="equipement_index", methods={"GET"})
     */
    public function index(EquipementRepository $equipementRepository): Response
    {
        return $this->render('equipement/index.html.twig', [
            'equipements' => $equipementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="equipement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $equipement = new Equipement();
        $form = $this->createForm(EquipementType::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            


            $images = $form->get('images')->getData();
            // Boucle sur les images
            foreach ($images as $image) {
                // on genere un nouveau nom de fichier unique avec md5. guessExtension recupere l'extension du fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                //On copie le fichier dans le dossier upload
                $image->move(
                    $this->getParameter('upload_directory'),
                    $fichier
                );

                // on stocke l'image dans la bdd (son nom)
                $img = new Images();
                $img->setName($fichier);
                $equipement->addImage($img);
            }
            $entityManager = $this->getDoctrine()->getManager();





            $entityManager->persist($equipement);
            $entityManager->flush();

            return $this->redirectToRoute('equipement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('equipement/new.html.twig', [
            'equipement' => $equipement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="equipement_show", methods={"GET"})
     */
    public function show(Equipement $equipement): Response
    {
        return $this->render('equipement/show.html.twig', [
            'equipement' => $equipement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="equipement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Equipement $equipement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EquipementType::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('equipement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('equipement/edit.html.twig', [
            'equipement' => $equipement,
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * @Route("/{id}", name="equipement_delete", methods={"POST"})
     */
    public function delete(Request $request, Equipement $equipement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($equipement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('equipement_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("affeq", name="affeq", methods={"GET"})
     */
    public function afff(EquipementRepository $equipementRepository): Response
    {
        return $this->render('equipement/affeq.html.twig', [
            'equipements' => $equipementRepository->findAll(),
        ]);
    
    }
}
