<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    public function indexAction()
    {
        // replace this example code with whatever you need
        return $this->render('AppBundle:Profile:index.html.twig', array(
            'profile_active' => true,
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
}
