<?php

namespace App\Security\Voter;

use App\Entity\Wish;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class WishVoter extends Voter
{
    public const EDIT = 'WISH_EDIT';
    public const DELETE = 'WISH_DELETE';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {

        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Wish;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Wish $wish */
        $wish = $subject;


        return match ($attribute) {
            self::EDIT => $this->canEdit($wish, $user),
            self::DELETE => $this->canDelete($wish, $user),
            default => false,
        };
    }

    private function canEdit(Wish $wish, UserInterface $user): bool
    {
        return ($wish->getUser() === $user);
    }

    private function canDelete(Wish $wish, UserInterface $user): bool
    {
        return ($wish->getUser() === $user || $this->security->isGranted('ROLE_ADMIN'));
    }
}