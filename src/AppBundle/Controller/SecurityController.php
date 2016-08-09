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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/login", name="use_login")
     * 
     * @param Request $oRequest
     * 
     * @return Response
     */
    public function loginAction(Request $oRequest) {
        
        $oAuthenticationUtils = $this->get('security.authentication_utils');
        
        // get the login error if there is one
        $oError = $oAuthenticationUtils->getLastAuthenticationError();
        
        $sLastUserName = $oAuthenticationUtils->getLastUserName();
        
        return $this->render(
            'security/login.html.twig',
            array(
                'last_userName' => $sLastUserName,
                'error' => $oError 
            )
        );
    }
}