<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SideBarController extends Controller
{
    public function indexAction()
    {
        $oSitedDbModel = new Site($this->container);
        $oSitesCollection = $this->getDoctrine()->getRepository('AppBundle:Site')->findAll();
        
//        var_dump($oSitesCollection);
        
        return $this->render('AppBundle:SideBar:index.html.twig', array(
            // ...
        ));
    }

}
