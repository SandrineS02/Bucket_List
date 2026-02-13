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
    public function update(?Comment $comment, Request $request, EntityManagerInterface $manager): Response
    {
        if (!$comment){
            throw $this->createNotFoundException('This comment do not exists! Sorry!');
        }
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()){
            $comment->setDateUpdated(new \DateTimeImmutable());
            $manager->flush();
            $this->addFlash('success', 'Comment successfully updated!');
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
    public function delete(?Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        if (!$comment){
            throw $this->createNotFoundException('This comment does not exists!');
        }
        if (!($comment->getUser() === $this->getUser() || $this->isGranted('ROLE_ADMIN')) ) {
            throw $this->createAccessDeniedException('You don\'t have authorization to delete this comment!');
        }
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->query->get('token'),)) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'The comment has been deleted');
        }
        else {
            $this->addFlash('danger', 'This comment cannot be deleted');
        }
        return $this->redirectToRoute('wish_detail', [
            'id' => $comment->getWish()->getId()
        ]);
    }

}