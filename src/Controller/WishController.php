<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishFormType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
        $wishes = $wishRepository->findBy(['published' => true], ['dateCreated' => 'DESC']);

        return $this->render('wish/list.html.twig', [
            // passe les wishes à la vue twig
            'wishes' => $wishes,
        ]);
    }


    #[Route('/souhait/{id}', name: 'wish_detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, WishRepository $wishRepository): Response
    {
        // récupère un wish en fonction de son id passé dans l'url
        $wish = $wishRepository->find($id);

        // si le wish n'existe pas en bd => déclanche erreur 404
        if (!$wish) {
            throw $this->createNotFoundException('Wish not found');
        }
        return $this->render('wish/detail.html.twig', [
            "wish" => $wish,
        ]);
    }


    #[Route('/creer', name: 'wish_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // crée une nouvelle instance de l'entité Wish
        $wish = new Wish();
        // crée le formulaire en liant l'entité Wish
        $wishForm = $this->createForm(WishFormType::class, $wish);

        // traite le formulaire : récupère les données envoyées par l'utilisateur grace à POST et les applique à l'objet $wish
        $wishForm->handleRequest($request);

        //vérifie si le formulaire a été soumis et qu'il est valide en fonction des contraintes ajoutées
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            // récupère le fichier image uploadé depuis le formulaire, si ok fichier image uploadé alors $imageFile = null.
            $imageFile = $wishForm->get('image')->getData();
            // si il y a un fichier image => on le traite
            if ($imageFile) {
                // genere un nom de fichier uniquepour eviter les doublons et récupère l'extension du fichier
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                // déplace le fichier uploadé dans le répertoire défini
                try {
                    $imageFile->move(
                        $this->getParameter('app.project_images_directory'),
                        // nouveau nom du fichier
                        $newFilename
                    );
                    // enregistre le nom diu fichier
                    $wish->setFilename($newFilename);
                } catch (FileException $e) {
                    // si erreur, affiche un message d'erreur'
                    $this->addFlash('danger', 'Impossible de télécharger l’image.');
                }
            }
            // prepare l'enregistrement en bd
            $entityManager->persist($wish);
            //éxécute l'enregistrement
            $entityManager->flush();
            // affiche un message flash de réussite sur la prochaine page
            $this->addFlash("success", "Le souhait a bien été créé, bravo.");
            // redirige vers la page du souhait qui a été créé
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/create.html.twig', [
            "wishForm" => $wishForm, // affiche le formulaire
            "isEdit" => false // false indque que c'est une création t non une modification (pour changer dynamiquement le texte du boutton de validation du formulaire)
        ]);
    }


    #[Route('/{id}/update', name: 'wish_update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(int $id, WishRepository $wishRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // récupère le souhait depuis la bdd grace à son id.
        $wish = $wishRepository->find($id);
        // si souhait inexistant
        if (!$wish) {
            // message d'erreur
            throw $this->createNotFoundException("Ce souhait n'existe pas");
        }

        // creation du formulaire qui contient les données du souhait à modifié
        $wishForm = $this->createForm(WishFormType::class, $wish);
        // récupère les données saisies par l'utilisateur
        $wishForm->handleRequest($request);
        // vérifie si le formulaire est soumis et valide
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            // Gestion de l'image (mise à jour)
            $imageFile = $wishForm->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('app.project_images_directory'),
                        $newFilename
                    );
                    $wish->setFilename($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Impossible de télécharger l’image.');
                }
            }


            // gestion de la suppression de l'image
            //vérifie que le formulaire contient le champ 'deleteImage' et que l'utilisateur a bien coché la checkbox
            if ($wishForm->has('deleteImage') && $wishForm->get('deleteImage')->getData()) {
                // verifie que l'entité a bien un fileName
                $existingFilename = $wish->getFilename();
                if ($existingFilename) {
                    // correspond au chemin complett du fichier sur le serveur
                    $imagePath = $this->getParameter('app.project_images_directory') . '/' . $existingFilename;
                    // vérifie que le fichier existe bien
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // supprime  le fichier
                    }
                    $wish->setFilename(null); // supprime la référence en bdd
                }
            }

            $wish->setDateUpdated(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Votre souhait a bien été mis à jour');
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
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
