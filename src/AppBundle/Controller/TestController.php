<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppBundle:Test:index.html.twig', array(
            // ...
        ));
    }

}
