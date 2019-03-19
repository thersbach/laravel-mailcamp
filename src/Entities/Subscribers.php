<?php

namespace Voicecode\Mailcamp\Entities;

use App\Models\User;
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
        return $this->request($this->requestType, 'IsSubscriberOnList', $details);
    }

    /**
     * Add a contact to a mailing list.
     *
     * @param string    $email      The email address of the subscriber.
     * @param int       $listID     The ID of the mailing list.
     */
    public function subscribe($email, $listID, $confirmed = true, $format = 'html')
    {
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

    /**
     * Unsubscribe a contact from a mailing list.
     *
     * @param string    $email      The email address of the subscriber
     * @param int       $listID     The ID of the mailing list.
     *
     */
    public function unsubscribe($email, $listID)
    {
        // Setup request details.
        $details = '
            <emailaddress>'.$email.'</emailaddress>
            <listid>'.$listID.'</listid>
        ';

        // Make request.
        return $this->request('subscribers', 'UnsubscribeSubscriber', $details);
    }

    /**
     * Delete a contact from a mailing list.
     *
     * @param string    $email      The email address of the subscriber
     * @param int       $listID     The ID of the mailing list.
     *
     */
    public function delete($email, $listID)
    {
        // Setup request details.
        $details = '
            <emailaddress>'.$email.'</emailaddress>
            <listid>'.$listID.'</listid>
            <subscriberid />
        ';

        // Make request.
        return $this->request($this->requestType, 'DeleteSubscriber', $details);
    }

    /**
     * Update an email address of an existing subscriber.
     *
     * @param App\Models\User   $user   The current user.
     */
    public function updateEmailAddress(User $user)
    {
        // Setup request details.
        $details = '
            <subscriberids>'.$user->mailcamp_id.'</subscriberids>
            <emailaddress>'.$user->email.'</emailaddress>
        ';

        // Make request.
        return $this->request($this->requestType, 'UpdateEmailAddress', $details);
    }

    /**
     * Update subscriber data.
     *
     * @param App\Models\User   $user           The current user.
     * @param int               $mailingListID  The ID of the maillinglist.
     */
    public function update(User $user, $mailingListID)
    {
        // Setup request details.
        $details = '
            <emailaddress>'.$user->email.'</emailaddress>
            <mailinglist>'.$mailingListID.'</mailinglist>
            <customfields></customfields>
        ';

        // Make request.
        return $this->request($this->requestType, 'EditSubscriberCustomFields', $details);
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
