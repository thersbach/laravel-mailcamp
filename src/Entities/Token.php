<?php

namespace Voicecode\Mailcamp\Entities;

use Voicecode\Mailcamp\Mailcamp;

class Token extends Mailcamp
{
    /**
     * Set request type
     *
     * @var string
     */
    private $requestType = 'authentication';

    /**
     * Check the provided user token.
     */
    public function check()
    {
        // Setup request details.
        $details = '';

        // Make request.
        return $this->request($this->requestType, 'xmlapitest', $details);
    }
}
