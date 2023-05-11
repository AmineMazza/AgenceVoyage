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
use App\Service\UserApiService;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserApiService $userApiService): Response
    {
        
        $user = new User();
        $agent = new Agent();
        $form = $this->createForm(RegistrationType::class, [$user, $agent]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $user->setEmail($formData['email']);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )   
            );

            $agent->setAgence($formData['agence']);
            $agent->setNom($formData['nom']);
            $agent->setPrenom($formData['prenom']);
            $agent->setTelephoneMobile($formData['telephone_mobile']);
            $agent->setTelephoneFixe($formData['telephone_fixe']);
            $agent->setAbonnement($formData['type_abonnement']);
            $agent->setAdresse($formData['adresse']);
            // logo
            $logo=$request->files->get('registration')['logo'];
            //  dd($request->files->get('registration')['logo']);
            if ($logo){
                $originalFilename = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logo->guessExtension();
                
                
                $agent->setLogo('/assets/agentLogo/'.$newFilename);
                
            }
            $user->setAgent($agent);
            $bool = $userApiService->AddUser($user);
            if($bool){
                if($logo){
                    try {
                        $logo->move(
                            $this->getParameter('logo_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        throw new Exception($e);
                    }
                }
                return $this->redirectToRoute('app_login');
            }
            else{
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                    'error' => 'This email is already exist.' ,
                    'agent' => $agent,
                ]);
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
