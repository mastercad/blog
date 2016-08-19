<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SiteXSite;
use AppBundle\Form\SiteXSiteType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Site;
use AppBundle\Form\SiteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    public function indexAction()
    {
        
    }
    
    public function showAction()
    {
        
        return $this->render('AppBundle:Article:show.html.twig', array(
            'article_active' => true,
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
    
    public function newAction(Request $oRequest)
    {
        $oSiteEntity = new Site();
        $oSiteXSiteEntity = new SiteXSite();

        $oSiteEntity->setCreateuser($this->getUser());
        $oSiteEntity->setCreated(new \DateTime());

        $oSitesCollection = $this->getDoctrine()->getRepository('AppBundle:Site')->findAll();
        
        foreach ($oSitesCollection as $oCurrentSiteEntity) {
            $oSiteXSiteEntity->getMainsite()->add($oCurrentSiteEntity);
        }
        
        $oSiteForm = $this->createForm(SiteType::class, $oSiteEntity);
        $oSiteXSiteForm = $this->createForm(SiteXSiteType::class, $oSiteXSiteEntity);
        
        // 2) handle the submit (will only happen on POST)
        $oSiteForm->handleRequest($oRequest);
        
        if ($oSiteForm->isSubmitted() && $oSiteForm->isValid()) {

            $oDoctrineManager = $this->getDoctrine()->getManager();

            $oSiteEntity->setCreateuser($this->getUser());
            $oSiteEntity->setCreated(new \DateTime());
            
//            $oUserGroup = $oDoctrineManager->find('\AppBundle\Entity\Usergroup', 1);

            // 3) Encode the password (you could also do this via Doctrine listener)
//            $sPassword = $this->get('security.password_encoder')
//                ->encodePassword($oUser, $oUser->getPlainPassword());

//            $oUser->setPassword($sPassword);
//            $oUser->setCreated(new \DateTime());
//            $oUser->setUsergroup($oUserGroup);

            $oDoctrineManager->persist($oSiteEntity);
            $oDoctrineManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('article_show');
        }

        return $this->render('AppBundle:Article:new.html.twig', array(
            'article_active' => true,
            'formSite' => $oSiteForm->createView(),
            'formSiteXSite' => $oSiteXSiteForm->createView(),
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
    
    public function editAction()
    {
        
    }
    
    public function deleteAction()
    {
        
    }
    
    public function searchAction()
    {
        
    }
}
