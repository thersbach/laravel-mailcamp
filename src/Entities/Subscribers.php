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
        // Update the subscribers email address.
        $this->updateEmailAddress($user);

        // Generate gender based on user details.
        $salutation = (($user->gender == 'o') ? 'heer/mevrouw' : (($user->gender == 'm') ? 'heer' : 'mevrouw'));
        $newsletter = (($user->newsletter) ? 'true' : 'false');
        $internalOffers = (($user->internal_offers) ? 'true' : 'false');
        $externalOffers = (($user->external_offers) ? 'true' : 'false');
        $panelMember = (($user->isPanelMember()) ? 'true' : 'false');

        // Setup request details.
        $details = '
            <emailaddress>'.$user->email.'</emailaddress>
            <mailinglist>'.$mailingListID.'</mailinglist>
            <customfields>
                <item>
                    <fieldid>'.config('mailcamp.phoneFieldID').'</fieldid>
                    <value>'.$salutation.'</value>
                </item>
                <item>
                    <fieldid>'.config('mailcamp.firstnameFieldID').'</fieldid>
                    <value>'.$user->firstname.'</value>
                </item>
                <item>
                    <fieldid>'.config('mailcamp.lastnameFieldID').'</fieldid>
                    <value>'.$user->lastname.'</value>
                </item>
                <item>
                    <fieldid>'.config('mailcamp.dateOfBirthFieldID').'</fieldid>
                    <value>'.$user->date_of_birth->format('d/m/Y').'</value>
                </item>
                <item>
                    <fieldid>'.config('mailcamp.phoneFieldID').'</fieldid>
                    <value>'.$user->phone.'</value>
                </item>
                <item>
                    <fieldid>'.config('mailcamp.newsletterFieldID').'</fieldid>
                    <value>'.$newsletter.'</value>
                </item>
                <item>
                    <fieldid>'.config('mailcamp.internalOffersFieldID').'</fieldid>
                    <value>'.$internalOffers.'</value>
                </item>
                <item>
                    <fieldid>'.config('mailcamp.externalOffersFieldID').'</fieldid>
                    <value>'.$externalOffers.'</value>
                </item>                
                <item>
                    <fieldid>'.config('mailcamp.countryIsoFieldID').'</fieldid>
                    <value>'.$user->country->iso.'</value>
                </item>
                <item>
                    <fieldid>'.config('mailcamp.genderFieldID').'</fieldid>
                    <value>'.$user->gender.'</value>
                </item>
                <item>
                    <fieldid>'.config('mailcamp.localeFieldID').'</fieldid>
                    <value>'.$user->locale.'</value>
                </item>
                <item>
                    <fieldid>'.config('mailcamp.panelMemberFieldID').'</fieldid>
                    <value>'.$panelMember.'</value>
                </item>
            </customfields>
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
