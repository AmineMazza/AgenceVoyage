<?php

namespace App\Controller;

use Exception;
use App\Entity\Offre;
use DateTimeImmutable;
use App\Form\OffreType;
use FontLib\Table\Type\name;
use App\Service\HotelApiService;
use App\Service\OffreApiService;
use App\Repository\OffreRepository;
use App\Service\DestinationApiService;
use App\Repository\DestinationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/offre')]
class OffreController extends AbstractController
{

    #[Route('/{id}', name: 'app_offre_show', methods: ['GET'])]
    public function show(array $_route_params, OffreApiService $offreApiService): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offreApiService->getOffre($_route_params['id']),
        ]);
    }
    private function deleteOldImage($oldImagePath)
    {
        $oldImageFullPath = $this->getParameter('offres_directory') . '/' . $oldImagePath;

        if (file_exists($oldImageFullPath)) {
            unlink($oldImageFullPath);
        }
    }
    #[Route('/{id}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, OffreApiService $offreApiService, HotelApiService $hotelApiService, SluggerInterface $slugger): Response
    {
        // dd($offre->getHotels());
        if($this->getUser() && !$this->isGranted('ROLE_USER')){
            if ($offre->getIdUser()->getId() != $this->getUser()->getId() && !$this->isGranted("ROLE_ADMIN")) {
                return $this->redirectToRoute('app_offre_index', ['value' => 'all'], Response::HTTP_SEE_OTHER);
            }
        }
        else{
            return $this->redirectToRoute('app_offre_show', ['id' => $offre->getId()], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $this->deleteOldImage($imageFile);
                try {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                    $imageFile->move(
                        $this->getParameter('offres_directory'),
                        $newFilename
                    );
                    $offre->setImage('/assets/images/offres/'.$newFilename);
                } catch (FileException $e) {
                   echo "image ont saved " ;
                }
                }

            $offreApiService->updateOffre($offre);

            return $this->redirectToRoute('app_offre_index', ['value'=>'all'], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request,array $_route_params, OffreApiService $offreApiService, HotelApiService $hotelApiService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$_route_params['id'], $request->request->get('_token'))) {
            $offreApiService->DeleteOffre($_route_params['id']);
        }

        return $this->redirectToRoute('app_offre_index', ['value'=>'all'], Response::HTTP_SEE_OTHER);
    }
   
    #[Route('/type/{value}', name: 'app_offre_index', methods: ['GET'])]
    public function index(DestinationRepository $DR,array $_route_params,PaginatorInterface $paginator,Request $request,OffreRepository $OffreRepository): Response
    {    if(isset($_GET['btnClear'])){
        $pagination = $paginator->paginate(
            $OffreRepository->PaginationQuery($_route_params['value']),
            $request->query->get('page', 1), 
            8
        );
        }
        else if (isset($_GET['SearchOffreName']) || isset($_GET['SearchOffreMaxPrix'])) {
            if($_GET['SearchOffreDate']!=''){   
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $_GET['SearchOffreDate']);
            $pagination = $paginator->paginate(
                $OffreRepository->filterOffre($_GET['searchOffDestination'],$_GET['SearchOffreMaxPrix'],$date),
                $request->query->get('page', 1),
                8
            );
        }
        else{
            $pagination = $paginator->paginate(
                $OffreRepository->filterOffre($_GET['searchOffDestination'],$_GET['SearchOffreMaxPrix']),
                $request->query->get('page', 1),
                8
            );
        }
        }
        else{
      $pagination = $paginator->paginate(
            $OffreRepository->PaginationQuery($_route_params['value']),
            $request->query->get('page', 1),
            8
        );
        }
  
        return $this->render('offre/index.html.twig', [
            'destinations' => $DR->findAll(),
            'pagination' => $pagination,
        ]);
    }

    
}
