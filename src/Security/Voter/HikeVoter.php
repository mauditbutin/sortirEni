<?php

namespace App\Security\Voter;

use App\Entity\Hike;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class HikeVoter extends Voter
{
    public const EDIT = 'HIKE_EDIT';
    public const VIEW = 'HIKE_VIEW';
    public const CANCEL = 'HIKE_CANCEL';
    public const DELETE = 'HIKE_DELETE';
    public const SUBSCRIBE = 'HIKE_SUBSCRIBE';
    public const UNSUBSCRIBE = 'HIKE_UNSUBSCRIBE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::CANCEL, self::DELETE, self::SUBSCRIBE, self::UNSUBSCRIBE])
            && $subject instanceof Hike;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        //Définition de subject comme étant une Hike
        /** @var Hike $subject */
        $hike = $subject;
        $date = new \DateTime('now');

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            $vote?->addReason('The user must be logged in to access this resource.');
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {

            case self::EDIT:
                if ($user->getUserIdentifier() == $hike->getPlanner()->getUserIdentifier()) {
                    return true;
                } elseif (in_array('ROLE_ADMIN', $user->getRoles())) {
                    return true;
                }
                break;

            case self::VIEW:
                if ($user and (($hike->getStatus()->getLabel() !== 'Créée'))) {
                    return true;
                } elseif ($user->getUserIdentifier() == $hike->getPlanner()->getUserIdentifier() and $hike->getStatus()->getLabel() !== 'Créée') {
                    return true;
                } else if ((in_array('ROLE_ADMIN', $user->getRoles()))) {
                    return true;
                }

                break;

            case self::CANCEL:
                if (($user->getUserIdentifier() == $hike->getPlanner()->getUserIdentifier()) and (($hike->getStatus()->getLabel() == 'Ouverte') or ($hike->getStatus()->getLabel() == 'Clôturée') or ($hike->getStatus()->getLabel() == 'Activité en cours'))) {

                    return true;
                } else if ((in_array('ROLE_ADMIN', $user->getRoles()))) {
                    return true;
                }
                break;

            case self::DELETE:
                if (($user->getUserIdentifier() == $hike->getPlanner()->getUserIdentifier()) and ($hike->getStatus()->getLabel() == 'Créée')) {
                    return true;
                } else if ((in_array('ROLE_ADMIN', $user->getRoles()))) {
                    return true;
                }
                break;

            case self::SUBSCRIBE:
                if (($user->getUserIdentifier() !== $hike->getPlanner()->getUserIdentifier()) and (!$hike->getParticipant()->contains($user)) and ($date < $hike->getDateSubscription()) and ($hike->getStatus()->getLabel() == 'Ouverte')) {
                    return true;
                }
                break;

            case self::UNSUBSCRIBE:
                if (($user->getUserIdentifier() !== $hike->getPlanner()->getUserIdentifier()) and ($hike->getParticipant()->contains($user)) and ($date < $hike->getDateEvent())) {
                    return true;
                }
                break;
        }

        return false;
    }
}
