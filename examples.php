<?php

// Setting up a new instance.
$mailcamp = new Voicecode\Mailcamp\Mailcamp();

/**
 * Get all available mailing lists.
 *
 * @param int $start    Start of the query results (default: 0)
 * @param int $perPage  The number of results (default: 500)
 */
$mailcamp->lists()->get();

/**
 * Get the details of a single mailing list.
 *
 * @param integer   $listID     The ID of the mailing list.
 */
$mailcamp->lists()->details($listID);

/**
 * Get statistics.
 *
 * @param int $statsID          The ID of the stats you want to get
 * @param string $statsType     The type of stats you want to get (n = Newsletter, a = Autoresponder, Default = n)
 */
$mailcamp->stats()->get($statsID, $statsType);

/**
 * Check if a user is already subscribed for a certain mailing list.
 *
 * @param string    $email      The email address of the subscriber.
 * @param integer   $listID     The ID of the mailing list.
 */
$mailcamp->subscribers()->isSubscribed($email, $listID);

/**
 * Add a user to a certain mailing list.
 *
 * @param string    $email      The email address of the subscriber.
 * @param integer   $listID     The ID of the mailing list.
 * @param boolean   $confimed   true/false - default: true
 * @param string    $format     html/text - default: html
 */
$mailcamp->subscribers()->subscribe($email, $listID);

/**
 * Update a contact of a mailing list.
 *
 * @param int       $id         The ID of the subscriber.
 * @param string    $email      The email address of the subscriber.
 *
 */
$mailcamp->subscribers()->update($id, $email);

/**
 * Remove a contact from a mailing list.
 *
 * @param string    $email      The email address of the subscriber.
 * @param int       $listID     The ID of the mailing list.
 *
 */
$mailcamp->subscribers()->delete($email, $listID);
    
/**
 * Check if the username and token are valid.
 */
$mailcamp->token()->check();
