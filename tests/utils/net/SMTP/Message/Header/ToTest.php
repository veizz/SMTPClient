<?php

    /**
     * @filesource tests\utils\net\SMTP\Message\Header\ToTest.php
     * @author Tiago de Souza Ribeiro <tiago.sr@msn.com>
     */
    use tests\utils\net\SMTP\Message\Header\AbstractHeader;
    use utils\net\SMTP\Message\Header\To;

    class ToTest extends AbstractHeader
    {

        public function getHeader()
        {
            return new To;
        }

        public function testGetNameIsEqualsToHeaderName()
        {
            $this->assertEquals("To", $this->getHeader()->getName());
        }

    }