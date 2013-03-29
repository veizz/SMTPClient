<?php

    /**
     * @filesource tests\utils\net\SMTP\Message\Header\CcTest.php
     * @author Tiago de Souza Ribeiro <tiago.sr@msn.com>
     */
    use tests\utils\net\SMTP\Message\Header\AbstractHeader;
    use utils\net\SMTP\Message\Header\Cc;

    class CcTest extends AbstractHeader
    {

        public function getHeader()
        {
            return new Cc;
        }

        public function testGetNameIsEqualsToHeaderName()
        {
            $this->assertEquals("Cc", $this->getHeader()->getName());
        }

    }