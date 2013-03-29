<?php

    /**
     * @filesource tests\utils\net\SMTP\Message\Header\BccTest.php
     * @author Tiago de Souza Ribeiro <tiago.sr@msn.com>
     */
    use tests\utils\net\SMTP\Message\Header\AbstractHeader;
    use utils\net\SMTP\Message\Header\Bcc;

    class BccTest extends AbstractHeader
    {

        public function getHeader()
        {
            return new Bcc;
        }

        public function testGetNameIsEqualsToHeaderName()
        {
            $this->assertEquals("Bcc", $this->getHeader()->getName());
        }

    }