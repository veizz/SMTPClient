<?php
    
    /**
     * Please use this project: https://github.com/Nilhcem/FakeSMTP to make tests.
     * Just listen a Fake SMTP in port 23000 and run these connection tests.
     */
    use utils\net\SMTP\Client\Message;
    use utils\net\SMTP\Client\Connection\TCPConnection;

    class TCPConnectionTest extends PHPUnit_Framework_TestCase
    {

        const SMTP_SERVER_HOSTNAME = "localhost";
        const SMTP_SERVER_PORT = 23000;

        /**
         * @expectedException \Exception
         * @expectedExceptionMessage Couldn't connect to SMTP server test.abcde.com:1234567
         */
        public function testConnectionOpeningWithoutListeningServerAt()
        {
            $connection = new TCPConnection("test.abcde.com", 1234567);
        }

        public function testConnectionOpeningWithValidHostnameAndPort()
        {
            $hostname = static::SMTP_SERVER_HOSTNAME;
            $port = static::SMTP_SERVER_PORT;

            try {
                $connection = new TCPConnection($hostname, $port);
                $this->assertEquals("stream", get_resource_type($connection->getStream()));
            } catch (Exception $e) {
                $message = "Please listen a SMTP server at %s:%d to run this test";
                $this->markTestSkipped(sprintf($message, $hostname, $port));
            }
        }
        
        public function testConnectionHostnameWhenProvidedValid() {
            $connection = new TCPConnection(static::SMTP_SERVER_HOSTNAME, static::SMTP_SERVER_PORT);
            $this->assertEquals(static::SMTP_SERVER_HOSTNAME, $connection->getHostname());
        }

        /**
         * @depends testConnectionOpeningWithValidHostnameAndPort
         */
        public function testExchangedMessagesWhenOpenConnection()
        {
            $isInstanceCount = 0;
            $connection = new TCPConnection(static::SMTP_SERVER_HOSTNAME, static::SMTP_SERVER_PORT);
            $messages = $connection->getExchangedMessages();
            $this->assertInternalType("array", $messages);
            $this->assertGreaterThanOrEqual(3, $messages);
            
            foreach($messages AS $offset => $message) {
                if($message instanceof Message) {
                    ++ $isInstanceCount;
                }
            }
            
            // Test if all itens of array is a instance of Message
            $this->assertCount($isInstanceCount, $messages);
        }
        
        public function testLatestMessageWhenOpenConnection()
        {
            $connection = new TCPConnection(static::SMTP_SERVER_HOSTNAME, static::SMTP_SERVER_PORT);
            $latestMessage = $connection->getLatestMessage();
            
            $this->assertTrue($latestMessage instanceof Message);
            $this->assertEquals(250, $latestMessage->getCode());
        }
        
        public function testConnectionStateWhenOpenConnection()
        {
            $connection = new TCPConnection(static::SMTP_SERVER_HOSTNAME, static::SMTP_SERVER_PORT);
            $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
            
            $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
            $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Established);
        }
        
        public function testConnectionCloseAfterOpen()
        {
            $connection = new TCPConnection(static::SMTP_SERVER_HOSTNAME, static::SMTP_SERVER_PORT);
            $connection->close();
            
            $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
            $this->assertTrue($state instanceof \utils\net\SMTP\Client\ConnectionState);
            $this->assertTrue($state instanceof \utils\net\SMTP\Client\Connection\State\Closed);
        }
    
    }