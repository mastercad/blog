<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
//    public function indexAction(Request $request)
//    {
////        $navigation = $this->
//        // replace this example code with whatever you need
//        return $this->render('default/index.html.twig', array(
//            'navigation' => 'etrewfdsfsfds',
//            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
//        ));
//    }

    /**
     * @Route("/", name="homepage")
     * @Template()
     */
//    public function indexAction(Request $request)
//    {
//        return array();
//    }

    public function indexAction(Request $request)
    {
        $oMailer = $this->get('app.mailer');
        var_dump($oMailer);
        
//        $navigation = $this->
        // replace this example code with whatever you need
        return $this->render('AppBundle:Default:index.html.twig', array(
            'navigation' => 'etrewfdsfsfds',
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
}
