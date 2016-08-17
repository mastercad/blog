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

namespace AppBundle\Newsletter;

use AppBundle\Mailer;

class NewsletterManager
{
    protected $_oMailer;
    
    public function __construct($oMailer)
    {
        $this->_oMailer = $oMailer;
    }
}