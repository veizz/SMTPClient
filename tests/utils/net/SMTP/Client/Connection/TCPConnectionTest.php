<?php

    /**
     * Please use this project: https://github.com/Nilhcem/FakeSMTP to make tests.
     * Just listen a Fake SMTP in port 23000 and run these connection tests.
     */
    use utils\net\SMTP\Client\Connection\TCPConnection;
    use tests\utils\net\SMTP\Client\Connection\AbstractConnection;

    class TCPConnectionTest extends AbstractConnection
    {
        
        public function getPort()
        {
            return 23000;
        }

        public function getHostname()
        {
            return "localhost";
        }

        public function getInvalidConnection()
        {
            return new TCPConnection("test.abcde.net", 123456);
        }

        public function getValidConnection()
        {
            return new TCPConnection($this->getHostname(), $this->getPort());
        }

    }