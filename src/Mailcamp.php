<?php

namespace Voicecode\Mailcamp;

use Voicecode\Mailcamp\Entities\Send;
use Voicecode\Mailcamp\Entities\Lists;
use Voicecode\Mailcamp\Entities\Stats;
use Voicecode\Mailcamp\Entities\Token;
use Voicecode\Mailcamp\Entities\Bounce;
use Voicecode\Mailcamp\Entities\Segment;
use Voicecode\Mailcamp\Entities\Settings;
use Voicecode\Mailcamp\Entities\Templates;
use Voicecode\Mailcamp\Entities\Newsletters;
use Voicecode\Mailcamp\Entities\Subscribers;
use Voicecode\Mailcamp\Entities\CustomFields;
use Voicecode\Mailcamp\Entities\Autoresponders;

class Mailcamp
{
    protected $config;
    protected $result;
    protected $xml;

    /**
     * Set connection data.
     */
    public function __construct()
    {
        // Validate config variables.
        $this->validateConfig();

        // Set default result object.
        $this->result = new \stdClass();
    }

    /**
     * Autoresponders.
     *
     * @param \Voicecode\Mailcamp\Entities\Autoresponders
     */
    public function autoresponders()
    {
        return new Autoresponders();
    }

    /**
     * Bounce.
     *
     * @param \Voicecode\Mailcamp\Entities\Bounce
     */
    public function bounce()
    {
        return new Bounce();
    }

    /**
     * Custom Fields.
     *
     * @param \Voicecode\Mailcamp\Entities\CustomFields
     */
    public function customFields()
    {
        return new CustomFields();
    }

    /**
     * Mailcamp lists.
     *
     * @param \Voicecode\Mailcamp\Entities\Lists
     */
    public function lists()
    {
        return new Lists();
    }

    /**
     * Newsletters.
     *
     * @param \Voicecode\Mailcamp\Entities\Newsletters
     */
    public function newsletters()
    {
        return new Newsletters();
    }

    /**
     * Segment.
     *
     * @param \Voicecode\Mailcamp\Entities\Segment
     */
    public function segment()
    {
        return new Segment();
    }

    /**
     * Send.
     *
     * @param \Voicecode\Mailcamp\Entities\Send
     */
    public function send()
    {
        return new Send();
    }

    /**
     * Settings.
     *
     * @param \Voicecode\Mailcamp\Entities\Settings
     */
    public function settings()
    {
        return new Settings();
    }

    /**
     * Stats.
     *
     * @param \Voicecode\Mailcamp\Entities\Stats
     */
    public function stats()
    {
        return new Stats();
    }

    /**
     * Subscribers.
     *
     * @param \Voicecode\Mailcamp\Entities\Subscribers
     */
    public function subscribers()
    {
        return new Subscribers();
    }

    /**
     * Templates.
     *
     * @param \Voicecode\Mailcamp\Entities\Templates
     */
    public function templates()
    {
        return new Templates();
    }

    /**
     * Token.
     *
     * @param \Voicecode\Mailcamp\Entities\Token
     */
    public function token()
    {
        return new Token();
    }
    
    /**
     * Make a request to the Mailcamp API.
     *
     * @param string $xml The generated XML body for the request.
     */
    public function request($type, $method, $details)
    {
        // Generate XML body.
        $this->xml = $this->generateXML($type, $method, $details);

        // Build the request.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        if (! ini_get('safe_mode') && ini_get('open_basedir') == '') {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'xml=' . $this->xml);

        // Execute the request.
        $response = curl_exec($ch);

        // Check for errors.
        if (curl_errno($ch) > 0) {
            $this->result->status = false;
            $this->result->data = curl_error($ch);
        } else {
            $result = @simplexml_load_string($response);
            
            if ($result) {
                if (isset($result->data)) {
                    $this->result->status = true;
                    $this->result->data = $result->data;
                }

                if (isset($result->errormessage)) {
                    $this->result->status = false;
                    $this->result->errormessage = $result->errormessage;
                }
            }
        }
        
        return $this->parseResponse($this->result);
    }

    /**
     * Generate the XML request body.
     *
     * @param string $type      The request type.
     * @param string $method    The request method.
     * @param string $details   The optional details for the request.
     */
    protected function generateXML($type, $method, $details)
    {
        return '
        <xmlrequest>
            <username>'.$this->config->username.'</username>
            <usertoken>'.$this->config->token.'</usertoken>
            <requesttype>'.$type.'</requesttype>
            <requestmethod>'.$method.'</requestmethod>
            <details>
            '.$details.'
            </details>
        </xmlrequest>';
    }

    protected function parseResponse($response)
    {
        // Convert array into object.
        $response = json_decode(json_encode((object) $response), false);

        // Throw an error when a request has failed.
        if ($response->status === false) {
            if (is_string($response->errormessage)) {
                throw new MailcampException($response->errormessage);
            }
            
            throw new MailcampException('Received an unknown or empty error response from Mailcamp.');
        }

        return $response;
    }

    /**
     * Check if all config variables are available.
     */
    private function validateConfig()
    {
        // Throw error when username is missing from the config files.
        if (!config('mailcamp.username')) {
            throw new MailcampException('Mailcamp API error: No username is specified for connecting with Mailcamp.');
        }

        // Throw error when token is missing from the config files.
        if (!config('mailcamp.token')) {
            throw new MailcampException('Mailcamp API error: No token is specified for connecting with Mailcamp.');
        }
        
        // Throw error when endpoint is missing from the config files.
        if (!config('mailcamp.endpoint')) {
            throw new MailcampException('Mailcamp API error: No endpoint is specified for connecting with Mailcamp.');
        }

        // Set connection details.
        $this->config = new \stdClass();
        $this->config->username = config('mailcamp.username');
        $this->config->token = config('mailcamp.token');
        $this->config->endpoint = config('mailcamp.endpoint');
    }
}
