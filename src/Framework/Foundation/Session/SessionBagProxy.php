<?php


namespace WebRover\Framework\Foundation\Session;


/**
 * Class SessionBagProxy
 * @package WebRover\Framework\Foundation\Session
 */
final class SessionBagProxy implements SessionBagInterface
{
    private $bag;
    private $data;
    private $usageIndex;

    public function __construct(SessionBagInterface $bag, array &$data, &$usageIndex)
    {
        $this->bag = $bag;
        $this->data = &$data;
        $this->usageIndex = &$usageIndex;
    }

    /**
     * @return SessionBagInterface
     */
    public function getBag()
    {
        ++$this->usageIndex;

        return $this->bag;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        if (!isset($this->data[$this->bag->getStorageKey()])) {
            return true;
        }
        ++$this->usageIndex;

        return empty($this->data[$this->bag->getStorageKey()]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->bag->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(array &$array)
    {
        ++$this->usageIndex;
        $this->data[$this->bag->getStorageKey()] = &$array;

        $this->bag->initialize($array);
    }

    /**
     * {@inheritdoc}
     */
    public function getStorageKey()
    {
        return $this->bag->getStorageKey();
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->bag->clear();
    }
}