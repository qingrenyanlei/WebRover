<?php


namespace WebRover\Framework\Security;


use phpseclib\Crypt\AES;

/**
 * Class Encryption
 * @package WebRover\Framework\Security
 */
class Encryption
{
    private $key;

    private $aes;

    public function __construct($key, $cipher)
    {
        $aes = new AES();
        $aes->key = $key;
        $aes->cipher_name_openssl = $cipher;
        $this->aes = $aes;
    }

    /**
     * @param $value
     * @param bool $serialize
     * @return string
     * @throws \Exception
     */
    public function encrypt($value, $serialize = true)
    {

        $iv = random_bytes(16);

        $this->aes->setIV($iv);
        $value = $this->aes->encrypt($serialize ? serialize($value) : $value);

        if ($value === false) {
            throw new EncryptException('Could not encrypt the data.');
        }

        // Once we get the encrypted value we'll go ahead and base64_encode the input
        // vector and create the MAC for the encrypted value so we can then verify
        // its authenticity. Then, we'll JSON the data into the "payload" array.
        $mac = $this->hash($iv = base64_encode($iv), $value = base64_encode($value));


        $json = json_encode(compact('iv', 'value', 'mac'));

        if (!is_string($json)) {
            throw new EncryptException('Could not encrypt the data.');
        }

        return base64_encode($json);
    }

    /**
     * @param $value
     * @return string
     * @throws \Exception
     */
    public function encryptString($value)
    {
        return $this->encrypt($value, false);
    }

    /**
     * @param $payload
     * @param bool $unserialize
     * @return mixed|string
     * @throws \Exception
     */
    public function decrypt($payload, $unserialize = true)
    {
        $payload = $this->getJsonPayload($payload);

        $iv = base64_decode($payload['iv']);

        // Here we will decrypt the value. If we are able to successfully decrypt it
        // we will then unserialize it and return it out to the caller. If we are
        // unable to decrypt this value we will throw out an exception message.
        $this->aes->setIV($iv);

        $decrypted = $this->aes->decrypt(base64_decode($payload['value']));

        return $unserialize ? unserialize($decrypted) : $decrypted;
    }

    /**
     * @param $payload
     * @return mixed|string
     * @throws \Exception
     */
    public function decryptString($payload)
    {
        return $this->decrypt($payload, false);
    }

    /**
     * @param $iv
     * @param $value
     * @return string
     */
    private function hash($iv, $value)
    {
        return hash_hmac('sha256', $iv . $value, $this->key);
    }

    /**
     * @param $payload
     * @return mixed
     * @throws \Exception
     */
    protected function getJsonPayload($payload)
    {
        $payload = json_decode(base64_decode($payload), true);

        // If the payload is not valid JSON or does not have the proper keys set we will
        // assume it is invalid and bail out of the routine since we will not be able
        // to decrypt the given value. We'll also check the MAC for this encryption.
        if (!$this->validPayload($payload)) {
            throw new DecryptException('The payload is invalid.');
        }

        if (!$this->validMac($payload)) {
            throw new DecryptException('The MAC is invalid.');
        }

        return $payload;
    }

    /**
     * Verify that the encryption payload is valid.
     *
     * @param mixed $payload
     * @return bool
     */
    protected function validPayload($payload)
    {
        return is_array($payload) && isset(
                $payload['iv'], $payload['value'], $payload['mac']
            );
    }

    /**
     * Determine if the MAC for the given payload is valid.
     *
     * @param array $payload
     * @return bool
     * @throws \Exception
     */
    protected function validMac(array $payload)
    {
        $calculated = $this->calculateMac($payload, $bytes = random_bytes(16));

        return hash_equals(
            hash_hmac('sha256', $payload['mac'], $bytes, true), $calculated
        );
    }

    /**
     * Calculate the hash of the given payload.
     *
     * @param array $payload
     * @param string $bytes
     * @return string
     */
    protected function calculateMac($payload, $bytes)
    {
        return hash_hmac(
            'sha256', $this->hash($payload['iv'], $payload['value']), $bytes, true
        );
    }
}
