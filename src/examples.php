<?php

// Setting up a new instance.
$mailcamp = new Voicecode\Mailcamp\Mailcamp();
    
/**
 * Check if the username and token are valid.
 */
$mailcamp->token()->check();

/**
 * Get the details of a single mailing list.
 *
 * @param integer   $listID     The ID of the mailing list.
 */
$mailcamp->lists()->getList($listID);

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
 * Remove a contact from a mailing list.
 *
 * @param string    $email      The email address of the subscriber.
 * @param int       $listID     The ID of the mailing list.
 *
 */
$mailcamp->subscribers()->delete($email, $listID);

/**
 * Remove a contact from a mailing list.
 *
 * @param int       $id         The ID of the subscriber.
 * @param string    $email      The email address of the subscriber.
 *
 */
$mailcamp->subscribers()->update($id, $email);
