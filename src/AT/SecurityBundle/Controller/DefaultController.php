<?php

namespace AT\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ATSecurityBundle:Default:index.html.twig');
    }
}
