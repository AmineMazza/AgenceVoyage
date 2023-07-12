<?php

namespace App\Controller;

use Twig\Environment;
use DateTimeImmutable;
use App\Service\CallApiService;
use App\Service\OffreApiService;
use App\Entity\Newsletters\Users;
use App\Form\NewslettersUsersType;
use App\Service\MessageApiService;
use App\Repository\OffreRepository;
use Symfony\Component\Mailer\Mailer;
use App\Service\DestinationApiService;
use ApiPlatform\Doctrine\Odm\Paginator;
use App\Entity\Newsletters\Newsletters;
use Symfony\Component\Mailer\Transport;
use App\Repository\DestinationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HomeController extends AbstractController
{

    protected $templating;
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine, Environment $templating,)
    {
        $this->doctrine = $doctrine;
        $this->templating = $templating;
    }

    #[Route('/', name: 'app_home')]
    public function index(OffreApiService $offreApiService,array $_route_params,OffreRepository $offreRepository ,DestinationRepository $destinationRepository, PaginatorInterface $paginator,Request $request): Response
    {
            $offreNOBcoupCoeur=$offreRepository->getOffresNoBcoupCoeur();
            $offreBcoupCoeur=$offreRepository->getOffresBcoupCoeur();
            $DR = $destinationRepository->getDestinations();
            if(isset($_GET['SearchHome']) && isset($_GET['searchOffDestination'])){
                $pagination = $paginator->paginate(
                    $offreRepository->PaginationQuery("all"),
                    $request->query->get('page', 1), 
                    8
                );
                return $this->redirectToRoute('app_offre_index',[
                    "value" => 'all',
                    'searchOffDestination' => $_GET['searchOffDestination'],
                    'SearchOffreMaxPrix' => $_GET['SearchOffreMaxPrix'],
                    'SearchOffreDate' => $_GET['SearchOffreDate'],
                    'pagination' => $pagination,
                ]);   
            }
             return $this->render('home/index.html.twig', [
            'destinations' => $DR,
            'controller_name' => 'HomeController',
            'offres' => $offreBcoupCoeur,
            'offresNoBcoupCoeur'=> $offreNOBcoupCoeur,
        ]);
    }

    #[Route('/newsletters', name: 'app_newsletters_home')]
    public function index2(Request $request, MailerInterface $mailer): Response
    {
        $user = new Users();
        $form = $this->createForm(NewslettersUsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $token = hash('sha256', uniqid());

            $user->setValidationToken($token);

            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();

            // Pour redirect vers le template :
            $html = $this->templating->render(
                'emails/inscription.html.twig',
                [
                    'user' => $user,
                    'token' => $token
                ]
            );

            // On crée le email :
            $email = (new TemplatedEmail())
                ->from('Newsletter@norsys.fr')
                ->to($user->getEmail())
                ->subject('Confirmation des Newsletters')
                ->html($html);

            // ->htmlTemplate('emails/inscription.html.twig')
            // ->context(compact('user', 'token'));

            // $dsn = 'smtp://13bb9004557159:3dc0c25687b200@sandbox.smtp.mailtrap.io:2525?encryption=tls&auth_mode=login';
            $dsn = $this->getParameter("mailtrap.param");
            $transport = Transport::fromDsn($dsn);
            $mailer = new Mailer($transport);

            // On evoie le email :
            $mailer->send($email);

            // On confirme et on redirige :
            $this->addFlash('message', 'Inscription en attente de validation');
            return $this->redirectToRoute('app_main_news');
        }

        return $this->render('newsletters/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/newsletters/confirm/{id}/{token}', name: 'confirm')]

    public function confirm(Users $user, $token): Response
    {
        if ($user->getValidationToken() != $token) {
            throw $this->createNotFoundException('Page non trouvée');
        }

        $user->setIsValid(true);

        $em = $this->doctrine->getManager("default");
        $em->persist($user);
        $em->flush();

        $this->addFlash('message', 'Newsletters activé');

        return $this->redirectToRoute('app_main_news');
    }




}
