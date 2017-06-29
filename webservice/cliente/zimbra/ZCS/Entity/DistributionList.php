<?php

/**
 * Clase de Zimbra 'Distribution List'
 *
 */

namespace sigejupev2\webservice\cliente\zimbra\ZCS\Entity;

class DistributionList extends \sigejupev2\webservice\cliente\zimbra\ZCS\Entity
{

    private $members = array();

    public function __construct($list)
    {
        parent::__construct($list);

        foreach ($list->children()->dlm as $data) {
            $this->members[] = (string) $data;
        }
        $this->set('members', implode("\r\n", $this->members));
    }

    public function getMembers()
    {
        return implode(', ', $this->members);
    }

}
