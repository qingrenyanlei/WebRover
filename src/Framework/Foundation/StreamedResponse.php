<?php


namespace WebRover\Framework\Foundation;


/**
 * StreamedResponse represents a streamed HTTP response.
 *
 * A StreamedResponse uses a callback for its content.
 *
 * The callback should use the standard PHP functions like echo
 * to stream the response back to the client. The flush() function
 * can also be used if needed.
 *
 * @see flush()
 *
 * Class StreamedResponse
 * @package WebRover\Framework\Foundation
 */
class StreamedResponse extends Response
{
    protected $callback;
    protected $streamed;
    private $headersSent;

    /**
     * @param callable|null $callback A valid PHP callback or null to set it later
     * @param int $status The response status code
     * @param array $headers An array of response headers
     */
    public function __construct(callable $callback = null, $status = 200, $headers = [])
    {
        parent::__construct(null, $status, $headers);

        if (null !== $callback) {
            $this->setCallback($callback);
        }
        $this->streamed = false;
        $this->headersSent = false;
    }

    /**
     * Factory method for chainability.
     *
     * @param callable|null $callback A valid PHP callback or null to set it later
     * @param int $status The response status code
     * @param array $headers An array of response headers
     *
     * @return static
     */
    public static function create($callback = null, $status = 200, $headers = [])
    {
        return new static($callback, $status, $headers);
    }

    /**
     * Sets the PHP callback associated with this Response.
     *
     * @param callable $callback A valid PHP callback
     *
     * @return $this
     */
    public function setCallback(callable $callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * This method only sends the headers once.
     *
     * @return $this
     */
    public function sendHeaders()
    {
        if ($this->headersSent) {
            return $this;
        }

        $this->headersSent = true;

        return parent::sendHeaders();
    }

    /**
     * {@inheritdoc}
     *
     * This method only sends the content once.
     *
     * @return $this
     */
    public function sendContent()
    {
        if ($this->streamed) {
            return $this;
        }

        $this->streamed = true;

        if (null === $this->callback) {
            throw new \LogicException('The Response callback must not be null.');
        }

        \call_user_func($this->callback);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return $this
     * @throws \LogicException when the content is not null
     *
     */
    public function setContent($content)
    {
        if (null !== $content) {
            throw new \LogicException('The content cannot be set on a StreamedResponse instance.');
        }

        $this->streamed = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return false;
    }
}