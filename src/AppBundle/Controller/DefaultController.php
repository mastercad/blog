<?php

namespace AppBundle\Controller;

use AppBundle\Model\DbTable\Site;
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
//        $oRepository = $this->getDoctrine()->getRepository();
        
        $oSiteDbTable = new Site($this->container);
        $oSites = $oSiteDbTable->findAllSites();
        
        var_dump($oSites);
        
        // replace this example code with whatever you need
        return $this->render('AppBundle:Default:index.html.twig', array(
            'home_active' => true,
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
}
