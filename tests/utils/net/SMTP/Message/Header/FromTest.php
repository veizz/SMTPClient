<?php

    /**
     * @filesource \tests\utils\net\SMTP\Message\Header\FromTest.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    use utils\net\SMTP\Message\Header\From;
    use utils\net\SMTP\Message\Encoder\Base64;

    class FromTest extends PHPUnit_Framework_TestCase
    {

        public function testHeaderNameEqualsToFrom()
        {
            $from = new From("test@test.com");
            $this->assertEquals("From", $from->getName());
        }

        public function testFromHeaderInitialEncodingIsEqualsToAscii()
        {
            $from = new From("test@test.com");
            $this->assertEquals("ASCII", $from->getEncoding());
        }

        public function testFromHeaderToStringWithEmailAndName()
        {
            $from = new From("test@test.com", "Test");
            $this->assertEquals("From: Test <test@test.com>", (string) $from);
        }

        public function testFromHeaderToStringWithOnlyEmail()
        {
            $from = new From("test@test.com");
            $this->assertEquals("From: test@test.com", (string) $from);
        }

        public function testFromHeaderToStringWithUTF8EncodingAndQuotedPrintableAsDefaultEncoder()
        {
            $from = new From("test@test.com", "ééããââ", "UTF-8");
            $this->assertEquals("From: =?UTF-8?Q?=C3=A9=C3=A9=C3=A3=C3=A3=C3=A2=C3=A2?= <test@test.com>", (string) $from);
        }

        public function testFromHeaderToStringWithUTF8EncodingAndBase64Encoder()
        {
            $from = new From("test@test.com", "ééããââ", "UTF-8", new Base64());
            $this->assertEquals("From: =?UTF-8?B?w6nDqcOjw6PDosOi?= <test@test.com>", (string) $from);
        }
        
        /**
         * @expectedException \InvalidArgumentException
         */
        public function testFromHeaderWithInvalidAddress()
        {
            $from = new From("___t,@test.com;#com");
        }
        
    }