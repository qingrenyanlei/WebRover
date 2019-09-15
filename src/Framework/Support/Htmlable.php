<?php


namespace WebRover\Framework\Support;


/**
 * Interface Htmlable
 * @package WebRover\Framework\Support
 */
interface Htmlable
{
    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml();
}