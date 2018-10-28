<?php

namespace Voicecode\Mailcamp\Entities;

use Voicecode\Mailcamp\Mailcamp;

class Token extends Mailcamp
{
    /**
     * Check the provided user token.
     */
    public function check()
    {
        // Generate XML request body.
        $this->xml = $this->generateXML('authentication', 'xmlapitest');

        // Make request.
        return $this->request($this->xml);
    }
}
