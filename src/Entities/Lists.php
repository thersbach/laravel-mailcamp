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
     * Get all available mailing lists.
     * 
     * @param int $start    Start of the query results (default: 0)
     * @param int $perPage  The number of results (default: 500)
     */
	public function get($start = 0, $perPage = 500) {
        
        // Setup request details.
        $details = '
            <lists/>
            <sortinfo/>
            <countonly/>
            <start>'.$start.'</start>
            <perpage>'.$perPage.'</perpage>
        ';

		// Make request.
        return $this->request('user', 'Getlists', $details);  // The "user" requestType looks like a flaw in the API.
	}

    /**
     * Get the details of a single list.
     *
     * @param int $listID The ID of the requested list.
     */
    public function details($listID)
    {
        // Setup request details.
        $details = '
            <lists>'.$listID.'</lists>
        ';

        // Make request.
        return $this->request($this->requestType, 'GetLists', $details);
    }
}
