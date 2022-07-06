<?php

namespace app\utils\encrypt;

class Encryptor
{
    /**
     * Fetch the encryption key
     *
     * Returns it as MD5 in order to have an exact-length 128 bit key.
     * Mcrypt is sensitive to keys that are not the correct length
     *
     * @param	string
     * @return	string
     */
    public function get_key($key = '')
    {
        $key = "sekolah.Zooo000oooZ.alhuda"; // makin alay makin unik enkripsinya
        return md5($key);
    }

    // --------------------------------------------------------------------

    /**
     * Encode
     *
     * Encodes the message string using bitwise XOR encoding.
     * The key is combined with a random hash, and then it
     * too gets converted using XOR. The whole thing is then run
     * through mcrypt using the randomized key. The end result
     * is a double-encrypted message string that is randomized
     * with each call to this function, even if the supplied
     * message and key are the same.
     *
     * @param	string	the string to encode
     * @param	string	the key
     * @return	string
     */
    public function encode($string, $key = '')
    {
        return base64_encode($this->mcrypt_encode($string, $this->get_key($key)));
    }

    // --------------------------------------------------------------------

    /**
     * Decode
     *
     * Reverses the above process
     *
     * @param	string
     * @param	string
     * @return	string
     */
    public function decode($string, $key = '')
    {
        if (preg_match('/[^a-zA-Z0-9\/\+=]/', $string) OR base64_encode(base64_decode($string)) !== $string)
        {
            return FALSE;
        }

        return $this->mcrypt_decode(base64_decode($string), $this->get_key($key));
    }
    // --------------------------------------------------------------------

    /**
     * Encrypt using Mcrypt
     *
     * @param	string
     * @param	string
     * @return	string
     */
    public function mcrypt_encode($data, $key)
    {
        $init_size = openssl_cipher_iv_length($this->_get_cipher());
        $init_vect = openssl_random_pseudo_bytes($init_size);
        $ciphertext_raw = openssl_encrypt($data, $this->_get_cipher(), $key, $options=OPENSSL_RAW_DATA, $init_vect);
        return $this->_add_cipher_noise($init_vect.$ciphertext_raw, $key);
    }

    // --------------------------------------------------------------------

    /**
     * Decrypt using Mcrypt
     *
     * @param	string
     * @param	string
     * @return	string
     */
    public function mcrypt_decode($data, $key)
    {
        $data = $this->_remove_cipher_noise($data, $key);
        $init_size = openssl_cipher_iv_length($this->_get_cipher());

        if ($init_size > strlen($data))
        {
            return FALSE;
        }

        $init_vect = substr($data, 0, $init_size);
        $data = substr($data, $init_size);
        $ciphertext_raw = openssl_decrypt($data, $this->_get_cipher(), $key, $options=OPENSSL_RAW_DATA, $init_vect);
        return rtrim($ciphertext_raw, "\0");
    }

    // --------------------------------------------------------------------

    /**
     * Adds permuted noise to the IV + encrypted data to protect
     * against Man-in-the-middle attacks on CBC mode ciphers
     * http://www.ciphersbyritter.com/GLOSSARY.HTM#IV
     *
     * @param	string
     * @param	string
     * @return	string
     */
    protected function _add_cipher_noise($data, $key)
    {
        $key = $this->hash($key);
        $str = '';

        for ($i = 0, $j = 0, $ld = strlen($data), $lk = strlen($key); $i < $ld; ++$i, ++$j)
        {
            if ($j >= $lk)
            {
                $j = 0;
            }

            $str .= chr((ord($data[$i]) + ord($key[$j])) % 256);
        }

        return $str;
    }

    // --------------------------------------------------------------------

    /**
     * Removes permuted noise from the IV + encrypted data, reversing
     * _add_cipher_noise()
     *
     * Function description
     *
     * @param	string	$data
     * @param	string	$key
     * @return	string
     */
    protected function _remove_cipher_noise($data, $key)
    {
        $key = $this->hash($key);
        $str = '';

        for ($i = 0, $j = 0, $ld = strlen($data), $lk = strlen($key); $i < $ld; ++$i, ++$j)
        {
            if ($j >= $lk)
            {
                $j = 0;
            }

            $temp = ord($data[$i]) - ord($key[$j]);

            if ($temp < 0)
            {
                $temp += 256;
            }

            $str .= chr($temp);
        }

        return $str;
    }
    // --------------------------------------------------------------------

    /**
     * Get Mcrypt cipher Value
     *
     * @return	string
     */
    protected function _get_cipher()
    {
        return "AES-256-CBC";
    }

    // --------------------------------------------------------------------

    /**
     * Get Mcrypt Mode Value
     *
     * @return	int
     */
    protected function _get_mode()
    {
        return OPENSSL_RAW_DATA;
    }


    // --------------------------------------------------------------------

    /**
     * Hash encode a string
     *
     * @param	string
     * @return	string
     */
    public function hash($str)
    {
        return hash("sha1", $str);
    }

    public function encodeStr($string="") {
        $string = is_array($string) ? json_encode($string):$string;
        $param = str_replace("/", "0_0", $this->encode($string));
        return $this->countChar2String($param, "=")."A3.".str_replace("=", "", $param);
    }
    public function encodeUrl($string="") {
        $string = is_array($string) ? json_encode($string):$string;
        $param = str_replace("/", "0_0", $this->encode($string));
        return rawurlencode($this->countChar2String($param, "=")."A3.".str_replace("=", "", $param));
    }

    public function decodeStr($string="") {
        $temp  = explode("A3.", $string);
        $param = isset($temp[1]) ? $temp[1].str_repeat("=", $temp[0]):$temp[0];
        $param = str_replace("0_0", "/", $param);
        $param = (string)$this->decode($param);
        $temp  = $this->is_json($param);
        $temp  = is_null($temp) ? ((string)$param):$temp;
        return ((string)$param);
    }
    public function decodeUrl($string="") {
        $string = rawurldecode($string);
        $temp  = explode("A3.", $string);
        $param = isset($temp[1]) ? $temp[1].str_repeat("=", $temp[0]):$temp[0];
        $param = str_replace("0_0", "/", $param);
        $param = (string)$this->decode($param);
        $temp  = $this->is_json($param);
        $temp  = is_null($temp) ? ((string)$param):$temp;
        return ((string)$param);
    }


    function is_json($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    function countChar2String($string="", $char=FALSE) {
        $D = array_count_values(str_split($string));
        if(!$char) return $D;
        return isset($D[$char]) ? $D[$char]:0;
    }
}