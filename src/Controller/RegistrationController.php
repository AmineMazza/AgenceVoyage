<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Entity\User;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Form\RegistrationType;
class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        
        $user = new User();
        $agent = new Agent();
        $form = $this->createForm(RegistrationType::class, [$user, $agent]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $user->setEmail($formData['email']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )   );
    
            //

            $agent->setAgence($formData['agence']);
            $agent->setNom($formData['nom']);
            $agent->setPrenom($formData['prenom']);
            $agent->setTelephoneMobile($formData['telephone_mobile']);
            $agent->setTelephoneFixe($formData['telephone_fixe']);
            $agent->setAbonnememt($formData['type_abonnement']);
            $agent->setAdresse($formData['adresse']);
                 // logo
            $logo=$request->files->get('registration')['logo'];
        //  dd($request->files->get('registration')['logo']);
         if ($logo) {
            $newFileName = uniqid() . '.' . $logo->guessExtension();

            try {
                $logo->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/AgenceLogo',
                    $newFileName
                );
            } catch (FileException $e) {
                return new Response($e->getMessage());
            }
         
            $agent->setLogo('/public/uploads/AgenceLogo/' . $newFileName);
        }

                  
    //
            $entityManager->persist($user);
            $entityManager->flush();
            $agent->setIdUser($user);
            $entityManager->persist($agent);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
