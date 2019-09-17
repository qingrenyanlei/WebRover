<?php


namespace WebRover\Framework\Cache\Proxy;


use WebRover\Framework\Security\Encryption;

/**
 * Class EncryptProxy
 * @package WebRover\Framework\Cache\Proxy
 */
class EncryptProxy extends AbstractProxy
{
    private $encryption;

    public function __construct(Encryption $encryption)
    {
        $this->encryption = $encryption;
    }

    public function set($key, $value, $ttl = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => &$v) {
                $v = $this->encryption->encrypt($v);
            }
        } else {
            $value = $this->encryption->encrypt($value);
        }

        return parent::set($key, $value, $ttl);
    }

    public function get($key, $default = null)
    {
        $data = parent::get($key, $default);

        if (!$data) return $data;

        if (!is_array($key)) {
            $data = [$data];
        }

        $result = [];

        foreach ($data as $k => $v) {
            if (!$data[$k] || $data[$k] === $default) {
                $result[$k] = $data[$k];
                continue;
            }

            try {
                $decrypt = $this->encryption->decrypt($data[$k]);

                if ($decrypt) $data[$k] = $decrypt;
            } catch (\Exception $exception) {
            }

            $result[$k] = $data[$k];
        }

        if (!is_array($key)) {
            return $result[0];
        }

        return $result;
    }
}