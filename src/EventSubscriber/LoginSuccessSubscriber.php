<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        // Vérifier si l'utilisateur est un professeur (champ teacher = true)
        if ($user->getTeacher()) {
            $targetUrl = $this->urlGenerator->generate('app_teacher_home');
        } else {
            // Par défaut, rediriger vers la page étudiant
            $targetUrl = $this->urlGenerator->generate('app_home');
        }

        $event->setResponse(new RedirectResponse($targetUrl));
    }
}
