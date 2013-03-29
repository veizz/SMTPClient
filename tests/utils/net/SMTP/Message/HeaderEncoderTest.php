<?php

    /**
     * @filesource tests\utils\net\SMTP\Message\HeaderEncoderTest.php
     * @author Tiago de Souza Ribeiro <tiago.sr@msn.com>
     */
    use utils\net\SMTP\Message\HeaderEncoder;
    use utils\net\SMTP\Message\Encoder\Base64;

    class HeaderEncoderTest extends PHPUnit_Framework_TestCase
    {

        public function testEncodeHeaderValueWithAsciiEncoding()
        {
            $encoded = HeaderEncoder::encode("test", "ASCII");
            $this->assertEquals("test", $encoded);
        }

        public function testEncodeHeaderValueWithUTF8Encoding()
        {
            $encoded = HeaderEncoder::encode("test", "UTF-8");
            $this->assertEquals("=?UTF-8?Q?test?=", $encoded);
        }

        public function testEncodeHeaderValueWithUTF8EncodingAndBase64Encoder()
        {
            $encoded = HeaderEncoder::encode("test", "UTF-8", new Base64());
            $this->assertEquals("=?UTF-8?B?dGVzdA==?=", $encoded);
        }

    }