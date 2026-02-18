<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/comments')]
class CommentController extends AbstractController
{
    #[Route('/{id}', name: 'comment_update', methods: ['GET','POST'])]
    #[IsGranted("ROLE_USER")]
    #[IsGranted('COMMENT_EDIT', 'comment')]
    public function update(?Comment $comment, Request $request, EntityManagerInterface $manager): Response
    {
        if (!$comment){
            throw $this->createNotFoundException("Ce commentaire n'existe pas ! Désolé !");
        }
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        // if (!($comment->getUser() === $this->getUser() || $this->isGranted('ROLE_ADMIN')) ) {
        //    throw $this->createAccessDeniedException('Vous n\'avez pas le droit de supprimer ce commentaire !');
        // }
        if ($commentForm->isSubmitted() && $commentForm->isValid()){
            $comment->setDateUpdated(new \DateTimeImmutable());
            $manager->flush();
            $this->addFlash('success', 'Commentaire modifié !');
            return $this->redirectToRoute('wish_detail', [
                'id' => $comment->getWish()->getId()
            ]);
        }
        return $this->render('comment/update.html.twig', [
            'commentForm' => $commentForm
        ]);
    }


    #[Route('/{id}/delete', name: 'comment_delete', requirements: ['id'=>'\d+'], methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    #[IsGranted('COMMENT_DELETE', 'comment')]
    public function delete(?Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        if (!$comment){
            throw $this->createNotFoundException("Ce commentaire n'existe pas ! Désolé !");
        }
        /*if (!($comment->getUser() === $this->getUser() || $this->isGranted('ROLE_ADMIN')) ) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de supprimer ce commentaire !');
        }*/
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->query->get('token'),)) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire supprimé !');
        }
        else {
            $this->addFlash('danger', 'Ce commentaire ne peux pas être supprimé');
        }
        return $this->redirectToRoute('wish_detail', [
            'id' => $comment->getWish()->getId()
        ]);
    }

}