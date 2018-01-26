<?php

namespace AppBundle\Controller\Security;

use AppBundle\Database\Model\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends Controller
{

    public function indexAction(Request $request)
    {
        $form = $this->createForm(UserType::class);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                /** @var User $user */
                $user = $form->getData();
                $this->get('user_register')->registerUser($user);
                $this->get('user_login')->authenticateByUser($user, $request);
                return $this->redirectToRoute('app_admin');
            }
        }

        return $this->render('@App/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

}