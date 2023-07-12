<?php

namespace App\Controller\Newsletters;

use App\Entity\Newsletters\Categories;
use App\Form\Newsletters\CategoriesType;
use App\Repository\Newsletters\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Newsletters\UsersRepository;
use App\Repository\Newsletters\NewslettersRepository;
use Doctrine\Persistence\ManagerRegistry;


#[Route('dashboard/newsletters/categories')]
class CategoriesController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    #[Route('/', name: 'app_newsletters_categories_index', methods: ['GET'])]
    public function index(CategoriesRepository $categoriesRepository, NewslettersRepository $newsletters,  UsersRepository $users): Response
    {
        return $this->render('newsletters/categories/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'newsletters' => $newsletters->findAll(),
            'users' => $users->findAll()
        ]);
    }

    #[Route('/new', name: 'app_newsletters_categories_new', methods: ['GET', 'POST'])]

    public function new(Request $request): Response
    {
        $categories = new Categories();
        $form = $this->createForm(CategoriesType::class, $categories);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager("default");
            $em->persist($categories);
            $em->flush();

            return $this->redirectToRoute('app_newsletters_categories_index');
        }

        return $this->render('newsletters/categories/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/{id}', name: 'app_newsletters_categories_show', methods: ['GET'])]
    public function show(Categories $category): Response
    {
        return $this->render('newsletters/categories/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_newsletters_categories_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categories $category, CategoriesRepository $categoriesRepository, NewslettersRepository $newsletters,  UsersRepository $users): Response
    {
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriesRepository->save($category, true);

            return $this->redirectToRoute('app_newsletters_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('newsletters/categories/edit.html.twig', [
            'category' => $category,
            'form' => $form,
            // 'categories' => $categoriesRepository->findAll(),
            'newsletters' => $newsletters->findAll(),
            'users' => $users->findAll()
        ]);
    }

    #[Route('/{id}', name: 'app_newsletters_categories_delete', methods: ['POST'])]
    public function delete(Request $request, Categories $categories, CategoriesRepository $categoriesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categories->getId(), $request->request->get('_token'))) {
            $categoriesRepository->remove($categories, true);
        }

        return $this->redirectToRoute('app_newsletters_categories_index', [], Response::HTTP_SEE_OTHER);
    }
}
