<?php


namespace WebRover\Framework\Foundation\Session;


/**
 * Session Bag store.
 *
 * Interface SessionBagInterface
 * @package WebRover\Framework\Foundation\Session
 */
interface SessionBagInterface
{
    /**
     * Gets this bag's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Initializes the Bag.
     */
    public function initialize(array &$array);

    /**
     * Gets the storage key for this bag.
     *
     * @return string
     */
    public function getStorageKey();

    /**
     * Clears out data from bag.
     *
     * @return mixed Whatever data was contained
     */
    public function clear();
}