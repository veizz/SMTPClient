<?php

    /**
     * @package tests.utils.net.SMTP.Client.Connection
     * @filesource utils\net\SMTP\Client\Connection\State\TCPConnectionTest.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    namespace utils\net\SMTP\Client\Connection\State {

        use utils\net\SMTP\Client\Message;
        use utils\net\SMTP\Client\Connection\TCPConnection;
        use utils\net\SMTP\Client\Authentication\Login;
        use utils\net\SMTP\Client\Authentication\Plain;
        use tests\utils\net\SMTP\Client\Connection\StreamWrapper;
        use tests\utils\net\SMTP\Client\Connection\AbstractConnection;
        use \PHPUnit_Framework_Assert;
        
        function stream_socket_client($remote)
        {
            $stream = fopen($remote, "w+");
            return $stream;
        }

        class TCPConnectionTest extends AbstractConnection
        {
            
            const PORT = 123;
            const HOSTNAME = "localhost";
            const PROTOCOL = "tcp";

            private function getWrapper()
            {
                $streamWrapper = $this->getStreamWrapper(static::PROTOCOL, gethostbyname(static::HOSTNAME), static::PORT);
                return $streamWrapper;
            }

            private function getConnection($streamWrapper) {
                StreamWrapper::register($streamWrapper, static::PROTOCOL);
                return new TCPConnection(static::HOSTNAME, static::PORT);
                
            }

            public function testConnectionOpening()
            {
                $connection = $this->getConnection($this->getWrapper());
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Established);
            }

            public function testQUITCommand()
            {
                $streamWrapper = $this->getWrapper();
                $this->expectWrite($this->at(12), "QUIT", $streamWrapper);
                
                $streamWrapper->expects($this->at(14))
                              ->method(self::READ)
                              ->will($this->returnMessage(221, "Bye"));
                
                StreamWrapper::register($streamWrapper, static::PROTOCOL);
                $connection = new TCPConnection("localhost", 123);
                $connection->close();
                
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Closed);
            }

            public function testAUTHCommandWithAuthPlain()
            {
                $username = "test@test.com";
                $password = "123456";
                
                $streamWrapper = $this->getWrapper();
                $this->expectWrite($this->at(12), "AUTH PLAIN", $streamWrapper);
                
                $streamWrapper->expects($this->at(14))
                              ->method(self::READ)
                              ->will($this->returnMessage(334, NULL));
                
                $inputString = base64_encode(sprintf("\0%s\0%s", $username, $password));
                $this->expectWrite($this->at(16), $inputString, $streamWrapper);
                
                $streamWrapper->expects($this->at(18))
                              ->method(self::READ)
                              ->will($this->returnMessage(235, "Accepted"));
                
                
                $connection = $this->getConnection($streamWrapper);
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Established);
                
                $connection->authenticate(new Plain($username, $password));
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Connected);
            }

            public function testAUTHCommandWithAuthLogin()
            {
                $username = "test.authlogin@test.com";
                $password = "qwertyuiop123456789";
                
                $streamWrapper = $this->getWrapper();
                $this->expectWrite($this->at(12), "AUTH LOGIN", $streamWrapper);

                $streamWrapper->expects($this->at(14))
                              ->method(self::READ)
                              ->will($this->returnMessage(250, NULL));

                $this->expectWrite($this->at(16), base64_encode($username), $streamWrapper);

                $streamWrapper->expects($this->at(18))
                              ->method(self::READ)
                              ->will($this->returnMessage(334, "Accepted"));

                $this->expectWrite($this->at(20), base64_encode($password), $streamWrapper);

                $streamWrapper->expects($this->at(22))
                              ->method(self::READ)
                              ->will($this->returnMessage(235, "Authentication Performed"));

                $connection = $this->getConnection($streamWrapper);
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Established);
                
                $connection->authenticate(new Login($username, $password));
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Connected);
            }

        }

    }
    