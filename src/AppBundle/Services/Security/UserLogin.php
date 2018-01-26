<?php

namespace AppBundle\Services\Security;

use AppBundle\Database\Model\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class UserLogin
{
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** @var Session */
    private $session;
    /** @var EventDispatcherInterface */
    private $dispatcher;

    /**
     * @param TokenStorageInterface $securityContext
     * @param SessionInterface $session
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        TokenStorageInterface $securityContext,
        SessionInterface $session,
        EventDispatcherInterface $dispatcher
    ) {
        $this->tokenStorage = $securityContext;
        $this->session = $session;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param User $user
     * @param Request $request
     */
    public function authenticateByUser(User $user, Request $request)
    {
        $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
        $this->tokenStorage->setToken($token);
        // Fire the login event
        // Logging the user in above the way we do it doesn't do this automatically
        $event = new InteractiveLoginEvent($request, $token);
        $this->dispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $event);

        return;
    }

    /**
     *
     */
    public function logout()
    {
        $this->session->remove('_security_secured_area');
    }
}