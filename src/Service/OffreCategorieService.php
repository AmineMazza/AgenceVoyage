<?php

namespace App\Service;


use App\Repository\OffreCategoryRepository;


class OffreCategorieService  {

    public function all(EntityManagerInterfce $entityManager)
    {
        $repository = $entityManager->getRepository(OffreCategoryRepository::class);
        return $repository->findAll();
    }


    public function getMyVariable()
    {
        return $this->myVariable;
    }

}