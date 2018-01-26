<?php

namespace AppBundle\Services\Security;

use AppBundle\Database\Model\User;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRegister
{
    const DEFAULT_PLAIN_PASSWORD = 'zaq12wsx';
    /** @var EncoderFactory */
    private $encoderFactory;

    /** @var Kernel */
    private $kernel;

    /**
     * @param EncoderFactory $encoderFactory
     * @param KernelInterface $kernel
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, KernelInterface $kernel)
    {
        $this->encoderFactory = $encoderFactory;
        $this->kernel = $kernel;
    }

    /**
     * @param User $user
     * @return User|UserRegister
     */
    public function registerUser(User $user)
    {
        return $this->registerByEmailAndPassword($user->getEmail(), $user->getPassword());
    }

    /**
     * @param $email
     * @param null $plainPassword
     * @return $this|User
     */
    public function registerByEmailAndPassword($email, $plainPassword = null)
    {
        $user = (new User)
            ->setEmail($email);

        $encodedPassword = $this->encodePassword($user, $plainPassword);
        $user
            ->setPassword($encodedPassword)
            ->save();
        return $user;
    }

    /**
     * @param UserInterface $user
     * @param $password
     *
     * @return bool
     */
    public function encodePassword(UserInterface $user, $password)
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        return $encoder->encodePassword($password, $user->getSalt());
    }
}