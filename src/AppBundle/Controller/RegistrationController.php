<?php
/**
 * Kurze Beschreibung der Datei
 *
 * Lange Beschreibung der Datei (wenn vorhanden)...
 *
 * @package    UNKNOWN
 * @copyright  Copyright (c) 2016 Unister GmbH
 * @author     Unister GmbH <entwicklung@unister.de>
 * @author     Andreas Kempe <andreas.kempe@unister.de>
 * @version    $Id$
 */

// src/AppBundle/Controller/RegistrationController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Usergroup;
use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    public function indexAction(Request $request)
    {
        // 1) build the form
        $oUser = new User();
        
        $oForm = $this->createForm(UserType::class, $oUser);

        // 2) handle the submit (will only happen on POST)
        $oForm->handleRequest($request);
        if ($oForm->isSubmitted() && $oForm->isValid()) {

            $oDoctrineManager = $this->getDoctrine()->getManager();
            
            $oUserGroup = $oDoctrineManager->find('\AppBundle\Entity\Usergroup', 1);
            
            // 3) Encode the password (you could also do this via Doctrine listener)
            $sPassword = $this->get('security.password_encoder')
                ->encodePassword($oUser, $oUser->getPlainPassword());
            
            $oUser->setPassword($sPassword);
            $oUser->setCreated(new \DateTime());
            $oUser->setUsergroup($oUserGroup);

            // 4) save the User!
            $oDoctrineManager->persist($oUser);
            $oDoctrineManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('user_login');
        }

        return $this->render('AppBundle:Registration:index.html.twig', array(
            'form' => $oForm->createView(),
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
}
