<?php

namespace Voicecode\Mailcamp\Entities;

use Voicecode\Mailcamp\Mailcamp;

class Subscribers extends Mailcamp
{
    /**
     * Set request type
     *
     * @var string
     */
    private $requestType = 'subscribers';

    /**
     * Check if an email address is already a subscriber on a certain list.
     *
     * @param string    $email      The email address of the subscriber.
     * @param int       $listID     The ID of the mailing list.
     *
     * @return boolean
     */
    public function isSubscribed($email, $listID)
    {
        // Setup request details.
        $details = '
            <emailaddress>'.$email.'</emailaddress>
            <listids>'.$listID.'</listids>
            <subscriberid />
            <activeonly />
            <not_bounced />
            <return_listid />
        ';

        // Make request.
        return $this->request($this->requestType, 'IsSubscriberOnList', $details, false);
    }

    /**
     * Add a contact to a mailing list.
     *
     * @param string    $email      The email address of the subscriber.
     * @param int       $listID     The ID of the mailing list.
     */
    public function subscribe($email, $listID, $confirmed = true, $format = 'html')
    {
        // When the user is not subscribed yet.
        if (!$this->isSubscribed($email, $listID)) {
                        
            // Setup request details.
            $details = '
                <emailaddress>'.$email.'</emailaddress>
                <mailinglist>'.$listID.'</mailinglist>
                <confirmed>'.$confirmed.'</confirmed>
                <format>'.$format.'</format>
            ';

            // Make request.
            return $this->request($this->requestType, 'AddSubscriberToList', $details);
        }
    }

    /**
     * Remove a contact from a mailing list.
     *
     * @param string    $email      The email address of the subscriber
     * @param int       $listID     The ID of the mailing list.
     *
     */
    public function unsubscribe($email, $listID)
    {
        // When the user is subscribed.
        if ($this->isSubscribed($email, $listID)) {

            // Setup request details.
            $details = '
                <emailaddress>'.$email.'</emailaddress>
                <listid>'.$listID.'</listid>
                <subscriberid />
            ';

            // Make request.
            return $this->request($this->requestType, 'DeleteSubscriber', $details);
        }
    }

    /**
     * Add a contact to a mailing list.
     *
     * @param int       $id     The ID of the subscriber.
     * @param string    $email  The email address of the subscriber
     */
    public function update($id, $email)
    {
        // Setup request details.
        $details = '
            <subscriberids>'.$id.'</subscriberids>
            <emailaddress>'.$email.'</emailaddress>
        ';

        // Make request.
        return $this->request($this->requestType, 'UpdateEmailAddress', $details);
    }

    /**
     * Add a subscriber to the suppression list.
     *
     * @param string    $email      The email address of the subscriber
     * @param int       $listID     The ID of the mailing list.
     */
    public function suppress($email, $listID)
    {
        // Setup request details.
        $details = '
            <emailaddress>'.$email.'</emailaddress>
            <listid>'.$listID.'</listid>
        ';

        // Make request.
        return $this->request($this->requestType, 'AddBannedSubscriber', $details);
    }
}
