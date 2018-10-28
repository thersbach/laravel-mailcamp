<?php

namespace Voicecode\Mailcamp\Entities;

class Stats
{
    /**
     * Set request type
     *
     * @var string
     */
    private $requestType = 'stats';

    /**
     * Get statistics.
     * 
     * @param int $statsID          The ID of the stats you want to get
     * @param string $statsType     The type of stats you want to get (n = Newsletter, a = Autoresponder, Default = n)
     */
    public function get($statsID, $statsType = 'n')
    {
        $details = '
            <statid>'.$statsID.'</statid>
            <statstype>'.$statsType.'</statstype>
        ';

        // Make request.
        return $this->request($this->requestType, 'FetchStats', $details);
    }
}
