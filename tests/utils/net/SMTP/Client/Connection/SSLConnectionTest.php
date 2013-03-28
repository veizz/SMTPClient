<?php

    use utils\net\SMTP\Client\Connection\SSLConnection;
    use tests\utils\net\SMTP\Client\Connection\AbstractConnection;

    class SSLConnectionTest extends AbstractConnection
    {

        public function getPort()
        {
            return 465;
        }

        public function getHostname()
        {
            return "smtp.gmail.com";
        }

        public function getInvalidConnection()
        {
            return new SSLConnection("test.abcde.net", 123456);
        }

        public function getValidConnection()
        {
            return new SSLConnection($this->getHostname(), $this->getPort());
        }

    }