<?php

namespace App\Controller;

use App\Entity\Newsletters\Categories;
use App\Entity\Newsletters\Newsletters;
use App\Entity\Newsletters\Users;
use App\Form\NewslettersType;
use App\Form\NewslettersUsersType;
use App\Form\NewslettersCategoriesType;
use App\Message\SendNewsletterMessage;
use App\Repository\Newsletters\CategoriesRepository;
use App\Repository\Newsletters\NewslettersRepository;
use App\Repository\Newsletters\UsersRepository;
use App\Service\SendNewsletterService;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Twig\Environment;


#[Route('/newsletters')]

class NewslettersController extends AbstractController
{

    protected $templating;
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine, Environment $templating,)
    {
        $this->doctrine = $doctrine;
        $this->templating = $templating;
    }

    #[Route('/', name: 'app_newsletters_home')]
    public function index(Request $request, MailerInterface $mailer): Response
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


    #[Route('/confirm/{id}/{token}', name: 'confirm')]

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


    #[Route('/prepare', name: 'prepare')]

    public function prepare(Request $request): Response
    {
        $newsletter = new Newsletters();
        $form = $this->createForm(NewslettersType::class, $newsletter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager("default");
            $em->persist($newsletter);
            $em->flush();

            return $this->redirectToRoute('list');
        }

        return $this->render('newsletters/prepare.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/list', name: 'list')]

    public function list(NewslettersRepository $newsletters): Response
    {
        return $this->render('newsletters/list.html.twig', [
            'newsletters' => $newsletters->findAll()
        ]);
    }


    #[Route('/send/{id}', name: 'send')]

    public function send(Newsletters $newsletter, MessageBusInterface $messageBus,  MailerInterface $mailer): Response
    {
        $users = $newsletter->getCategories()->getUsers();

        $user = new Users();
        $token = hash('sha256', uniqid());
        
        // Pour redirect vers le template :
            $html = $this->templating->render(
                'emails/newsletter.html.twig',
                [
                    'user' => $user,
                    'token' => $token,
                    'newsletter' => $newsletter
                ]
            );
        foreach($users as $user){
            if($user->getIsValid()){
                $email = (new TemplatedEmail())
                ->from('Newsletter@norsys.fr')
                ->to($user->getEmail())
                ->subject('Newsletters Norsys')
                ->html($html);
                $dsn = $this->getParameter("mailtrap.param");
                $transport = Transport::fromDsn($dsn);
                $mailer = new Mailer($transport);
    
                $mailer->send($email);

                $messageBus->dispatch(new SendNewsletterMessage($user->getId(), $newsletter->getId()));
            }
        }

        // $newsletter->setIsSent(true);

        // $em = $this->doctrine->getManager("default");
        // $em->persist($newsletter);
        // $em->flush();

        return $this->redirectToRoute('list');
    }

    /**
     * @Route("/unsubscribe/{id}/{newsletter}/{token}", name="unsubscribe")
     */
    #[Route('/unsubscribe/{id}/{newsletter}/{token}', name: 'unsubscribe')]

    public function unsubscribe(Users $user, Newsletters $newsletter, $token): Response
    {
        if ($user->getValidationToken() != $token) {
            throw $this->createNotFoundException('Page non trouvée');
        }

        $em = $this->doctrine->getManager("default");

        if (count($user->getCategories()) > 1) {
            $user->removeCategory($newsletter->getCategories());
            $em->persist($user);
        } else {
            $em->remove($user);
        }
        $em->flush();

        $this->addFlash('message', 'Newsletter supprimée');

        return $this->redirectToRoute('app_main_news');
    }

    #[Route('/categories', name: 'listcategories')]

    public function listcategories(NewslettersRepository $newsletters, CategoriesRepository $categories, UsersRepository $users): Response
    {
        return $this->render('newsletters/categories.html.twig', [
            'newsletters' => $newsletters->findAll(),
            'categories' => $categories->findAll(),
            'users' => $users->findAll()
        ]);
    }

    #[Route('/AddCategories', name: 'app_categorie_new', methods: ['GET', 'POST'])]

    public function addCategory(Request $request): Response
        {
            $categories = new Categories();
            $form = $this->createForm(NewslettersCategoriesType::class, $categories);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->doctrine->getManager("default");
                $em->persist($categories);
                $em->flush();

                return $this->redirectToRoute('listcategories');
            }

            return $this->render('newsletters/newcategorie.html.twig', [
                'form' => $form->createView()
            ]);
        }

    
           

}