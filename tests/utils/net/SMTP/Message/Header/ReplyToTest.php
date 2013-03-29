<?php

    /**
     * @filesource tests\utils\net\SMTP\Message\Header\ReplyToTest.php
     * @author Tiago de Souza Ribeiro <tiago.sr@msn.com>
     */
    use tests\utils\net\SMTP\Message\Header\AbstractHeader;
    use utils\net\SMTP\Message\Header\ReplyTo;

    class ReplyToTest extends AbstractHeader
    {

        public function getHeader()
        {
            return new ReplyTo;
        }

        public function testGetNameIsEqualsToHeaderName()
        {
            $this->assertEquals("Reply-To", $this->getHeader()->getName());
        }

    }