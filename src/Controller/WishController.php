<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WishController extends AbstractController
{
    // récupére les wish qui sont publiés et les organise du plus récent au plus vieux
    #[Route('/souhait/liste', name: 'wish_list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy(['published' => true], ['dateCreated' => 'DESC']);

        return $this->render('wish/list.html.twig', [
            // passe les wishes à la vue twig
            'wishes' => $wishes,
        ]);
    }


    #[Route('/souhait/{id}', name: 'wish_detail', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function detail(int $id, WishRepository $wishRepository): Response
    {
        // récupère un wish en fonction de son id passé dans l'url
        $wish = $wishRepository->find($id);

        // si le wish n'existe pas en bd => déclanche erreur 404
        if(!$wish){
            throw $this->createNotFoundException('Wish not found');
        }
        return $this->render('wish/detail.html.twig', [
            "wish" => $wish,
        ]);
    }
}
