<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Wish;
use App\Form\CommentType;
use App\Form\WishFormType;
use App\Repository\WishRepository;
use App\Util\Censurator;
use App\Util\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WishController extends AbstractController
{
    // récupére les wish qui sont publiés et les organise du plus récent au plus vieux
    #[Route('/souhait/liste', name: 'wish_list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findPublishedWishesWithCategories();

        return $this->render('wish/list.html.twig', [
            // passe les wishes à la vue twig
            'wishes' => $wishes,
        ]);
    }


    #[Route('/souhait/{id}', name: 'wish_detail', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function detail(int $id, WishRepository $wishRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // récupère un wish en fonction de son id passé dans l'url
        $wish = $wishRepository->find($id);

        // si le wish n'existe pas en bd => déclanche erreur 404
        if (!$wish) {
            throw $this->createNotFoundException('Souhait inexistant');
        }
        $parameters = ["wish" => $wish];
        if ($this->getUser() && $this->getUser() !== $wish->getUser()) {
            $comment = new Comment();
            $comment->setUser($this->getUser());
            $comment->setWish($wish);
            $commentForm = $this->createForm(CommentType::class, $comment);
            $commentForm->handleRequest($request);

            if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                $entityManager->persist($comment);
                $entityManager->flush();
                $this->addFlash('success', 'Le commentaire a bien été ajouté');
                return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
            }

            $parameters["commentForm"] = $commentForm->createView(); // ✅ important
        }

        return $this->render('wish/detail.html.twig', $parameters);

    }


    #[Route('/creer', name: 'wish_create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        Censurator $censurator,
        Uploader $uploader
    ): Response
    {
        $wish = new Wish();
        $wish->setUser($this->getUser());

        $wishForm = $this->createForm(WishFormType::class, $wish);
        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {

            // ✅ Censure
            $wish->setDescription(
                $censurator->purify($wish->getDescription())
            );

            // ✅ Upload via le service
            /** @var UploadedFile $imageFile */
            $imageFile = $wishForm->get('image')->getData();

            if ($imageFile) {
                $filename = $uploader->upload($imageFile);
                $wish->setFilename($filename);
            }

            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash("success", "Le souhait a bien été créé, bravo.");

            return $this->redirectToRoute('wish_detail', [
                'id' => $wish->getId()
            ]);
        }

        return $this->render('wish/create.html.twig', [
            "wishForm" => $wishForm,
            "isEdit" => false
        ]);
    }



    #[Route('/{id}/update', name: 'wish_update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(
        int $id,
        WishRepository $wishRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        Censurator $censurator,
        Uploader $uploader
    ): Response
    {
        $wish = $wishRepository->find($id);

        if (!$wish) {
            throw $this->createNotFoundException("Ce souhait n'existe pas");
        }

        if ($wish->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $wishForm = $this->createForm(WishFormType::class, $wish);
        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {

            // Censure
            $wish->setDescription(
                $censurator->purify($wish->getDescription())
            );

            // Upload via service
            /** @var UploadedFile $imageFile */
            $imageFile = $wishForm->get('image')->getData();

            if ($imageFile) {
                $filename = $uploader->upload($imageFile);
                $wish->setFilename($filename);
            }

            // Suppression image
            if ($wishForm->has('deleteImage') && $wishForm->get('deleteImage')->getData()) {

                $existingFilename = $wish->getFilename();

                if ($existingFilename) {
                    $imagePath = $this->getParameter('app.project_images_directory') . '/' . $existingFilename;

                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }

                    $wish->setFilename(null);
                }
            }

            $wish->setDateUpdated(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Votre souhait a bien été mis à jour');

            return $this->redirectToRoute('wish_detail', [
                'id' => $wish->getId()
            ]);
        }

        return $this->render('wish/create.html.twig', [
            'wishForm' => $wishForm,
            "isEdit" => true
        ]);
    }



    #[Route('/{id}/delete', name: 'wish_delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(int $id, WishRepository $wishRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // récupère le souhait depuis la bdd
        $wish = $wishRepository->find($id);
        if (!$wish) {
            throw $this->createNotFoundException("Ce souhait n'existe pas");
        }

        if (!($wish->getUser() === $this->getUser() || $this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }

        // Vérifie le token CSRF
        if ($this->isCsrfTokenValid('delete' . $id, $request->query->get('token'))) {

            // gère la suppression de l'image ---
            $filename = $wish->getFilename();
            if ($filename) {
                $imagePath = $this->getParameter('app.project_images_directory') . '/' . $filename;
                try {
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // supprime physiquement le fichier
                    }
                } catch (\Exception $e) {
                    // si la suppression échoue=> message d'erreur
                    $this->addFlash('warning', "L'image associée n'a pas pu être supprimée : " . $e->getMessage());
                }
            }

            // supprime le souhait de la bdd
            $entityManager->remove($wish);
            $entityManager->flush();

            $this->addFlash('success', 'Le souhait et son image ont bien été supprimés.');
        } else {
            $this->addFlash('danger', "Ce souhait n'a pas été supprimé : token CSRF invalide");
        }

        // redirige vers la liste des souhaits
        return $this->redirectToRoute('wish_list');
    }
}
