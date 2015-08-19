<?php
/*
    Vern Six MVC Framework version 3.0

    Copyright (c) 2007-2015 by Vernon E. Six, Jr.
    Author's websites: http://www.iPinga.com and http://www.VernSix.com

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to use
    the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice, author's websites and this permission notice
    shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
    IN THE SOFTWARE.
*/
defined('__VERN') or die('Restricted access');


/**
 * Class v6_crypto
 * @package vernsix
 *
 * @property string $key
 * @property int $key_size
 * @property int $iv_size
 *
 */
class v6_crypto {

    public $key = 'bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3';
    public $key_size = 0;
    public $iv_size = 0;

    /**
     * @param string $encryption_key
     */
    function __construct($encryption_key = '')
    {
        global $vern_config;

        // key is specified using hexadecimal, so pack it accordingly
        if (empty($encryption_key)==false) {
            $this->key = pack('H*', $encryption_key );
        } else {
            $this->key = pack('H*', $vern_config['encryption']['key'] );
        }

        // use either 16, 24 or 32 byte keys for AES-128, 192 and 256 respectively
        $this->key_size = strlen($this->key);

        $this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    }


    /**
     * @param string $encrypt_this
     * @return string
     */
    function encrypt($encrypt_this='')
    {
        // I have to make sure it's a string.  That requires me to give it an array to encode.  Talk about a stupid kludge!
        $json = json_encode(array('kludge'=>$encrypt_this));

        //create a random IV to use with CBC encoding
        $iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);

        // creates a cipher text compatible with AES (Rijndael block size = 128) to keep the text confidential
        //  only suitable for encoded input that never ends with value 00h (because of default zero padding)
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $json, MCRYPT_MODE_CBC, $iv);

        // prepend the IV for it to be available for decryption
        $ciphertext = $iv . $ciphertext;

        // encode the resulting cipher text so it can be represented by a string
        $ciphertext_base64 = base64_encode($ciphertext);

        return $ciphertext_base64;
    }

    function decrypt($decrypt_this='')
    {
        // get it back out of base_64
        $ciphertext_dec = base64_decode($decrypt_this);

        // retrieve the IV
        $iv_dec = substr($ciphertext_dec, 0, $this->iv_size);

        // retrieves the cipher text (everything except the $iv_size in the front)
        $ciphertext_dec = substr($ciphertext_dec, $this->iv_size);

        // it isn't really in json yet, due to the zero padding to block size.  I hate kludges.
        $json = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

        // now lop off the zero padding and it will be in a json string format
        $json = rtrim($json, "\0");

        // put it back in whatever format it was
        $json_decode = json_decode($json, true);
        $kludge = $json_decode['kludge'];

        return $kludge;
    }

}