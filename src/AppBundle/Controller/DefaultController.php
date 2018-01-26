<?php

namespace AppBundle\Controller;

use AppBundle\Database\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need


        $user = 'unlogged';
        if ($this->getUser() instanceof User) {
            $user = 'logged';
        }

        return $this->render('default/index.html.twig', [
            'base_dir' => $user,
        ]);
    }
}
