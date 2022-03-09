<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/evenement")
 */
class EvenementController extends AbstractController
{
    
    /**
     * @Route("/affichage", name="affichage", methods={"GET"})
     */
    public function aff(EvenementRepository $evenementRepository): Response
     {
         return $this->render('evenement/afficherevenement.html.twig', [
            'evenements' => $evenementRepository->findAll(),
    ]);
     }
    /**
     * @Route("/aff_back", name="aff_back", methods={"GET", "POST"})
     */
    public function aff_back(Request $request , EvenementRepository $evenementRepository): Response
     {

        if ( $request->isMethod('POST')){
            if ( $request->request->get('optionsRadios')){
                $trier_cle = $request->request->get('optionsRadios');
                switch($trier_cle){
                    case 'nom':
                        $evennements_triee = $evenementRepository->TrierParNomEvennement();
                        break;

                    case 'date':
                        $evennements_triee = $evenementRepository->TrierParDateEvennement();
                        break;
                }
                return $this->render('evenement/affichercrudevenement.html.twig', [
                    'evenements' => $evennements_triee,
                ]);

            }
        }
        return $this->render('evenement/affichercrudevenement.html.twig', [
            'evenements' => $evenementRepository->findAll(),
    ]);


     }
     /**
     * @Route("/aff_plan", name="aff_plan", methods={"GET"})
     */
    public function aff_plan(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/afficherplanevenement.html.twig', [
           'evenements' => $evenementRepository->findAll(),
   ]);


    }


    /**
     * @Route("/listev", name="evenement_list", methods={"GET"})
     */
    
    public function listev(EvenementRepository $EvenementRepository): Response
     {
        
        
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        $Evenements = $EvenementRepository->findAll();

        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('evenement/listev.html.twig',[
            'evenements'=> $Evenements,
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
     * @Route("/", name="evenement_index", methods={"GET"})
     */
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="evenement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="evenement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evenement_delete", methods={"POST"})
     */
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
