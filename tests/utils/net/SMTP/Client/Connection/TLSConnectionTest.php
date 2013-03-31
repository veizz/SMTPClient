<?php
    
    /**
     * @package tests.utils.net.SMTP.Client.Connection
     * @filesource tests\utils\net\SMTP\Client\Connection\State\TLSConnectionTest.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
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
                $stream = fopen($remote, "w+");
                return $stream;
            }
        }

        use utils\net\SMTP\Client\Message;
        use utils\net\SMTP\Client\Authentication\Login;
        use utils\net\SMTP\Client\Authentication\Plain;
        use tests\utils\net\SMTP\Client\Connection\StreamWrapper;
        use tests\utils\net\SMTP\Client\Connection\AbstractConnection;
        use utils\net\SMTP\Client\Connection\TLSConnection;
        use \PHPUnit_Framework_Assert;

        class TLSConnectionTest extends AbstractConnection
        {
            
            protected function getWrapper()
            {
                $streamWrapper = parent::getWrapper();
                $this->expectWrite($this->at(12), "STARTTLS", $streamWrapper);
                $streamWrapper->expects($this->at(14))
                              ->method(self::READ)
                              ->will($this->returnMessage(220, "2.0.0 Ready to start TLS"));
                
                //Ehlo and helo will be performed after TLS negotiation at [16, 18, 20, 22].
                $this->ehloHelo($streamWrapper, array(16, 18, 20, 22));
                return $streamWrapper;
            }

            private function getConnection($streamWrapper)
            {
                StreamWrapper::register($streamWrapper, static::PROTOCOL);
                return new TLSConnection(static::HOSTNAME, static::PORT);
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

            public function testQUITCommand()
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

            public function testAUTHCommandWithAuthPlain()
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

            public function testAUTHCommandWithAuthLogin()
            {
                $username = "test.authlogin@test.com";
                $password = "qwertyuiop123456789";
                
                $streamWrapper = $this->getWrapper();
                $this->expectWrite($this->at(24), "AUTH LOGIN", $streamWrapper);

                $streamWrapper->expects($this->at(26))
                              ->method(self::READ)
                              ->will($this->returnMessage(250, NULL));

                $this->expectWrite($this->at(28), base64_encode($username), $streamWrapper);

                $streamWrapper->expects($this->at(30))
                              ->method(self::READ)
                              ->will($this->returnMessage(334, "Accepted"));

                $this->expectWrite($this->at(32), base64_encode($password), $streamWrapper);

                $streamWrapper->expects($this->at(34))
                              ->method(self::READ)
                              ->will($this->returnMessage(235, "Authentication Performed"));

                $connection = $this->getConnection($streamWrapper);
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Established);
                $this->assertTrue($connection->authenticate(new Login($username, $password)));
                
                $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
                $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Connected);
            }

        }

    }
    