<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserVoter extends Voter
{
    public const EDIT = 'USER_EDIT';
    public const VIEW = 'USER_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $userConnected = $token->getUser();

        //Définition de subject comme étant un User
        /** @var User $subject */
        $user = $subject;

        // if the user is anonymous, do not grant access
        if (!$userConnected instanceof UserInterface) {
            $vote?->addReason('The user must be logged in to access this resource.');

            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                if ($user === $userConnected) {
                    return true;
                } elseif (in_array('ROLE_ADMIN', $userConnected->getRoles())) {
                    return true;
                }
                break;

            case self::VIEW:
                if ($userConnected) {
                    return true;
                }
                break;
        }

        return false;
    }
}
