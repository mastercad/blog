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

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * 
 * Kurze Beschreibung der Klasse
 *
 * Lange Beschreibung der Klasse (wenn vorhanden)...
 *
 * @package    UNKNOWN
 * @copyright  Copyright (c) ${YEAR} Unister GmbH
 */
class SecurityController extends Controller {

    /**
     * @param Request $oRequest
     *
     * @return Response
     */
    public function loginAction(Request $oRequest) {

//        $oAuthenticationUtils = $this->get('security.authentication_utils');
//
//        // get the login error if there is one
//        $oError = $oAuthenticationUtils->getLastAuthenticationError();
//
//        $sLastUserName = $oAuthenticationUtils->getLastUserName();

        $session = $oRequest->getSession();

        // get the login error if there is one
        if ($oRequest->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $oError = $oRequest->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $oError = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $oError = '';
        }
        
        return $this->render('AppBundle:Security:login.html.twig', array(
//            'last_userName' => $sLastUserName,
            'error' => $oError
        ));
    }
    
    public function loginCheckAction(Request $oRequest) {

        $this->getDoctrine();

        $oAuthenticationUtils = $this->get('security.authentication_utils');
//        $oAuthenticationUtils = $this->get('app.webservice_user_provider');
        
        // get the login error if there is one
        $oError = $oAuthenticationUtils->getLastAuthenticationError();

        $sLastUserName = $oAuthenticationUtils->getLastUserName();
        
        return $this->render('AppBundle:Security:login-check.html.twig', array(
        ));
    }
    
    /**
     * @param Request $oRequest
     *
     * @return Response
     */
    public function logoutAction(Request $oRequest) {

        return $this->render('AppBundle:Security:logout.html.twig', array(
        ));
    }
    
    /**
     * @param Request $oRequest
     *
     * @return Response
     */
    public function passwordResendAction(Request $oRequest) {

        $oAuthenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $oError = new \stdClass();

        $sLastUserName = "";

        return $this->render('AppBundle:Security:login.html.twig', array(
            'last_userName' => $sLastUserName,
            'error' => $oError
        ));
    }
}