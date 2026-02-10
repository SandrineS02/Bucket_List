<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WishController extends AbstractController
{
    #[Route('/souhait/liste', name: 'wish_list')]
    public function list(): Response
    {
        return $this->render('wish/list.html.twig', [

        ]);
    }


    #[Route('/souhait/{id}', name: 'wish_detail', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function detail(int $id): Response
    {
        return $this->render('wish/detail.html.twig', [

        ]);
    }
}
