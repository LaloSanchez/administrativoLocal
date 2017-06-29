<?php

/**
 * Clase de Zimbra 'Account'
 *
 */

namespace sigejupev2\webservice\cliente\zimbra\ZCS\Entity;

include_once(dirname(__FILE__) . "/../Entity.php");

class Account extends \sigejupev2\webservice\cliente\zimbra\ZCS\Entity
{

    static $statuses = array(
        'active' => 'Active',
        'closed' => 'Closed',
    );

    public function setAliases($aliases)
    {
        $this->set('aliases', implode("\n", $aliases));
    }

    public function getAliases()
    {
        return str_replace("\n", ', ', $this->aliases);
    }

    public function getStatus()
    {
        return self::$statuses[$this->get('zimbraAccountStatus')];
    }

    public static function getStatuses()
    {
        return self::$statuses;
    }

}
