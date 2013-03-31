<?php
    
    /**
     * @package tests.utils.net.SMTP.Client.Connection
     * @filesource tests\utils\net\SMTP\Client\Connection\State\TLSConnectionTest.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    
    namespace {
        $changeResourceType = false;
        $invalidStreamSocketClient = false;
    }
    
    namespace utils\net\SMTP\Client {
        function get_resource_type()
        {
            global $changeResourceType;
            if ($changeResourceType === TRUE) {
                return "file";
            }
            
            return call_user_func_array("\get_resource_type", func_get_args());
        }
    }

    namespace utils\net\SMTP\Client\Command {

        function stream_socket_enable_crypto($stream, $enable, $type)
        {
            return ($type === STREAM_CRYPTO_METHOD_TLS_CLIENT);
        }

    }

    namespace utils\net\SMTP\Client\Connection\State {

        if (!function_exists("stream_socket_client")) {
            function stream_socket_client($remote)
            {
                global $invalidStreamSocketClient;
                if ($invalidStreamSocketClient === false) {
                    $stream = fopen($remote, "w+");
                    return $stream;
                }

                return false;
            }
        }

        use utils\net\SMTP\Client\Message;
        use utils\net\SMTP\Client\Authentication\Login;
        use utils\net\SMTP\Client\Authentication\Plain;
        use tests\utils\net\SMTP\Client\Connection\StreamWrapper;
        use tests\utils\net\SMTP\Client\Connection\AbstractConnection;
        use utils\net\SMTP\Client\Connection\TLSConnection;
        use utils\net\SMTP\Client\Authentication;
        use \PHPUnit_Framework_Assert;

        class TLSConnectionTest extends AbstractConnection
        {
            
            protected function getWrapper($ehloHelo = TRUE)
            {
                $streamWrapper = parent::getWrapper();
                $this->expectWrite($this->at(12), "STARTTLS", $streamWrapper);
                $streamWrapper->expects($this->at(14))
                              ->method(self::READ)
                              ->will($this->returnMessage(220, "2.0.0 Ready to start TLS"));
                
                //Ehlo and helo will be performed after TLS negotiation at [16, 18, 20, 22].
                $this->ehloHelo($streamWrapper, array(16, 18, 20, 22), 250);
                return $streamWrapper;
            }

            private function getConnection($streamWrapper, $timeout = 30)
            {
                StreamWrapper::register($streamWrapper, static::PROTOCOL);
                return new TLSConnection(static::HOSTNAME, static::PORT, $timeout);
            }

            public function testConnectionOpening()
            {
                $connection = $this->getConnection($this->getWrapper());
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Established);
            }
            
            public function testStreamWriteAndReadAfterOpenConnection()
            {
                $streamWrapper = $this->getWrapper();
                $this->expectWrite($this->at(24), "It's just an test.", $streamWrapper);
                $streamWrapper->expects($this->at(26))
                              ->method(self::READ)
                              ->will($this->returnMessage(666, "The Number Of The Beast"));
                
                $connection = $this->getConnection($streamWrapper);
                $connection->write("It's just an test.");
                $message = $connection->read();
                
                $this->assertSame(666, $message->getCode());
                $this->assertEquals("The Number Of The Beast", $message->getMessage());
                $this->assertEquals("666 The Number Of The Beast", $message->getFullMessage());
            }
            
            public function testExchangedMessagesAfterOpenConnection()
            {
                $connection = $this->getConnection($this->getWrapper());
                $latestMessage = $connection->getLatestMessage();
                $exchangedMessages = $connection->getExchangedMessages();
                
                $this->assertTrue($latestMessage instanceof Message);
                $this->assertInternalType("array", $exchangedMessages);
                $this->assertGreaterThan(0, count($exchangedMessages));
            }

            public function testQuitCommand()
            {
                $streamWrapper = $this->getWrapper();
                $this->expectWrite($this->at(24), "QUIT", $streamWrapper);
                
                $streamWrapper->expects($this->at(26))
                              ->method(self::READ)
                              ->will($this->returnMessage(221, "Bye"));
                
                $connection = $this->getConnection($streamWrapper);
                $connection->close();
                
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Closed);
            }

            public function testAuthCommandWithAuthPlain()
            {
                $username = "test@test.com";
                $password = "123456";
                
                $streamWrapper = $this->getWrapper();
                $this->expectWrite($this->at(24), "AUTH PLAIN", $streamWrapper);
                
                $streamWrapper->expects($this->at(26))
                              ->method(self::READ)
                              ->will($this->returnMessage(334, NULL));
                
                $inputString = base64_encode(sprintf("\0%s\0%s", $username, $password));
                $this->expectWrite($this->at(28), $inputString, $streamWrapper);
                
                $streamWrapper->expects($this->at(30))
                              ->method(self::READ)
                              ->will($this->returnMessage(235, "Accepted"));
                
                
                $connection = $this->getConnection($streamWrapper);
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Established);
                $this->assertTrue($connection->authenticate(new Plain($username, $password)));
                
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Connected);
            }
            
            public function testAuthCommandWithAuthLogin()
            {
                $username = "test.authlogin@test.com";
                $password = "qwertyuiop123456789";
                
                $streamWrapper = $this->getWrapper();
                $this->expectWrite($this->at(24), "AUTH LOGIN", $streamWrapper);

                $streamWrapper->expects($this->at(26))
                              ->method(self::READ)
                              ->will($this->returnMessage(Authentication::ACCEPTED, NULL));

                $this->expectWrite($this->at(28), base64_encode($username), $streamWrapper);

                $streamWrapper->expects($this->at(30))
                              ->method(self::READ)
                              ->will($this->returnMessage(Authentication::ACCEPTED, "Accepted"));

                $this->expectWrite($this->at(32), base64_encode($password), $streamWrapper);

                $streamWrapper->expects($this->at(34))
                              ->method(self::READ)
                              ->will($this->returnMessage(Authentication::AUTHENTICATION_PERFORMED, "Authentication Performed"));

                $connection = $this->getConnection($streamWrapper);
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Established);
                $this->assertTrue($connection->authenticate(new Login($username, $password)));
                
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Connected);
            }
            
            /**
             * @expectedException RuntimeException
             * @expectedExceptionMessage Couldn't authenticate using the LOGIN mechanism
             */
            public function testAuthCommandWithInvalidAuthenticationMechanism()
            {
                $username = "test.invalid.auth@test.com";
                $password = "abcdef";
                
                $streamWrapper = $this->getWrapper();
                $this->expectWrite($this->at(24), "AUTH LOGIN", $streamWrapper);
                
                $message = "Unrecognized authentication method";
                $streamWrapper->expects($this->at(26))
                              ->method(self::READ)
                              ->will($this->returnMessage(Authentication::UNRECOGNIZED_AUTHENTICATION_TYPE, $message));
                
                $connection = $this->getConnection($streamWrapper);
                $connection->authenticate(new Login("test.invalid.auth@test.com", "abcdef"));
                
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Established);
            }
            
            /**
             * @expectedException BadMethodCallException
             */
            public function testWriteAtInvalidState()
            {
                $connection = $this->getConnection($this->getWrapper());
                $connection->changeState(new Closed());
                $connection->write("Hello World");
            }
            
            /**
             * @expectedException BadMethodCallException
             */
            public function testReadAtInvalidState()
            {
                $connection = $this->getConnection($this->getWrapper());
                $connection->changeState(new Closed());
                $connection->read();
            }
            
            /**
             * @expectedException BadMethodCallException
             */
            public function testOpenAtInvalidState()
            {
                $connection = $this->getConnection($this->getWrapper());
                $connection->open(self::PROTOCOL, self::HOSTNAME, self::PORT, 30);
            }
            
            /**
             * @expectedException BadMethodCallException
             */
            public function testCloseAtInvalidState()
            {
                $connection = $this->getConnection($this->getWrapper());
                $connection->changeState(new Closed());
                $connection->close();
            }
           
            /**
             * @expectedException BadMethodCallException
             */
            public function testAuthenticateAtInvalidState()
            {
                $connection = $this->getConnection($this->getWrapper());
                $connection->changeState(new Closed());
                $connection->authenticate(new Login("test@test.com", "test"));
            }
            
            public function testGetStream()
            {
                $connection = $this->getConnection($this->getWrapper());
                $this->assertTrue(is_resource($connection->getStream()));
                
                $connection->changeState(new Closed());
                $this->assertFalse($connection->getStream());
            }
            
            
            /**
             * @expectedException RuntimeException
             * @global boolean $changeResourceType
             */
            public function testGetStreamWithInvalidResourceType()
            {
                $connection = $this->getConnection($this->getWrapper());
                
                global $changeResourceType;
                $changeResourceType = true;
                $connection->getStream();
            }
            
            
            /**
             * @expectedException RuntimeException
             */
            public function testPerformEhloWithInvalidResponse()
            {
                $streamWrapper = (parent::getWrapper(FALSE));
                $this->expectWrite($this->at(4), "EHLO localhost", $streamWrapper);
            
                $streamWrapper->expects($this->at(6))
                              ->method(static::READ)
                              ->will($this->returnMessages(666, array(
                                "SIZE 35882577",
                                "8BITMIME",
                                "AUTH LOGIN PLAIN",
                                "ENHANCEDSTATUSCODES"
                              )));

                $this->getConnection($streamWrapper);
            }
            
            /**
             * @expectedException RuntimeException
             */
            public function testPerformHeloWithInvalidResponse()
            {
                $streamWrapper = (parent::getWrapper(FALSE));
                $this->expectWrite($this->at(4), "EHLO localhost", $streamWrapper);
            
                $streamWrapper->expects($this->at(6))
                              ->method(static::READ)
                              ->will($this->returnMessages(250, array(
                                "SIZE 35882577",
                                "8BITMIME",
                                "AUTH LOGIN PLAIN",
                                "ENHANCEDSTATUSCODES"
                              )));

                $this->expectWrite($this->at(8), "HELO localhost", $streamWrapper);
                
                $streamWrapper->expects($this->at(10))
                              ->method(static::READ)
                              ->will($this->returnMessage(666, "The Number Of The Beast"));
                
                $this->getConnection($streamWrapper);
            }
            
            /**
             * @expectedException Exception
             * @expectedExceptionMessage Timeout must be greater than zero.
             */
            public function testConnectionOpenWithInvalidTimeout()
            {
                new TLSConnection(self::HOSTNAME, self::PORT, 0);
            }
            
            /**
             * @expectedException Exception
             * @global boolean $invalidStreamSocketClient
             */
            public function testConnectionOpenWithInvalidSocketClient()
            {
                global $invalidStreamSocketClient;
                $invalidStreamSocketClient = TRUE;
                new TLSConnection(self::HOSTNAME, self::PORT);
            }
            
        }

    }
    