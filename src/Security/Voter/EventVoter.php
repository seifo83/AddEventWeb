<?php

namespace App\Security\Voter;

use App\Entity\Event;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EventVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return $attribute === 'DELETE_EVENT'
            && $subject instanceof Event;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Administrateur = autorisé à supprimer toutes les notes
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        // Utilisateur auteur de la note = autorisé à supprimer sa propre note
        if ($user === $subject->getUser()) {
            return true;
        }


        // Aucun des cas précédents = pas le droit de supprimer
        return false;
    }
}
