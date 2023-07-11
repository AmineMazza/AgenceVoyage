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
    
    
   
    #[Route('/type/{value}', name: 'app_offre_index', methods: ['GET'])]
    public function index(DestinationRepository $DR,array $_route_params,PaginatorInterface $paginator,Request $request,OffreRepository $OffreRepository): Response
    {    if(isset($_GET['btnClear'])){
        $pagination = $paginator->paginate(
            $OffreRepository->PaginationQuery($_route_params['value']),
            $request->query->get('page', 1), 
            8
        );
        }
        else if (isset($_GET['SearchOffreName']) || isset($_GET['SearchOffreMinPrix'])) {
            if($_GET['SearchOffreDate']!=''){   
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $_GET['SearchOffreDate']);
            $pagination = $paginator->paginate(
                $OffreRepository->filterOffre($_GET['searchOffDestination'],$_GET['SearchOffreMinPrix'],$date),
                $request->query->get('page', 1),
                8
            );
        }
        else{
            $pagination = $paginator->paginate(
                $OffreRepository->filterOffre($_GET['searchOffDestination'],$_GET['SearchOffreMinPrix']),
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
