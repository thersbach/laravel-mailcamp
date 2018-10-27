<?php

namespace Voicecode\Mailcamp\Entities;

use Voicecode\Mailcamp\Mailcamp;

class Lists extends Mailcamp
{
    /**
     * Set request type
     *
     * @var string
     */
    private $requestType = 'lists';

    /**
     * Get the details of a single list.
     *
     * @param int $listID The ID of the requested list.
     */
    public function getList($listID)
    {
        // Setup request details.
        $details = '<lists>'.$listID.'</lists>';

        // Generate XML request body.
        $this->xml = $this->generateXML($this->requestType, 'GetLists', $details);

        // Make request.
        return $this->request($this->xml);
    }
}
