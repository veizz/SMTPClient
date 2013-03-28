<?php

    use utils\net\SMTP\Client\Connection\TLSConnection;
    use tests\utils\net\SMTP\Client\Connection\AbstractConnection;

    class TLSConnectionTest extends AbstractConnection
    {

        public function getPort()
        {
            return 587;
        }

        public function getHostname()
        {
            return "smtp.gmail.com";
        }

        public function getInvalidConnection()
        {
            return new TLSConnection("test.abcde.net", 123456);
        }

        public function getValidConnection()
        {
            return new TLSConnection($this->getHostname(), $this->getPort());
        }
        
    }