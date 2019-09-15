<?php


namespace WebRover\Framework\Foundation;


/**
 * RequestMatcherInterface is an interface for strategies to match a Request.
 *
 * Interface RequestMatcherInterface
 * @package WebRover\Framework\Foundation
 */
interface RequestMatcherInterface
{
    /**
     * Decides whether the rule(s) implemented by the strategy matches the supplied request.
     *
     * @return bool true if the request matches, false otherwise
     */
    public function matches(Request $request);
}