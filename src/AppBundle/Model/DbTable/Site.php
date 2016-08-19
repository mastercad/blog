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

namespace AppBundle\Model\DbTable;

use Symfony\Component\DependencyInjection\Container;

class Site extends AbstractTable
{
    public function __construct(Container $oContainer)
    {
        parent::__construct($oContainer);
        $this->setConnectionSection('bookingDatabase');
    }

    public function findAllSites()
    {
        return $this->query('SELECT * FROM site');
    }
}