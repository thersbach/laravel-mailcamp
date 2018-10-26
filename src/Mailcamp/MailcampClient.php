<?php

namespace Voicecode\Mailcamp;

use Guzzle\Http\Client;

class MailcampClient
{
    private $connection;
    private $username;
    private $token;

    /**
     * @param Client $connection
     * @param string $username
     * @param string $token
     */
    public function __construct(Client $connection, $username, $token)
    {
        $this->connection = $connection;
        $this->username = $username;
        $this->token = $token;
    }

    /**
     * Make a request to the Mailcamp API
     */
    public function request(CallInterface $message)
    {
        // Create xml request.
        $xml = $this->createXml($message);

        // Send request to Mailcamp and get response.
        $response = $this->connection->post(null, null, ['xml' => $xml])->send()->xml();
        
        // When response is unsuccessful.
        if (strval($response->status) !== 'SUCCESS') {
            throw new MailcampException('Mailcamp API call failed: '.$response->errormessage);
        }

        return $message->parseResponse($response->data);
    }

    /**
     * Create XML body.
     */
    private function createXml(CallInterface $message)
    {
        return '
            <xmlrequest>
                <username>'.$this->username.'</username>
                <usertoken>'.$this->token.'</usertoken>
                <requesttype>'.$message->getType().'</requesttype>
                <requestmethod>'.$message->getMethod().'</requestmethod>
                <details>
                    '.$message->getDetails().'
                </details>
            </xmlrequest>
        ';
    }
}
