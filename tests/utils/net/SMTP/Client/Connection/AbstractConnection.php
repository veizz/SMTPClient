<?php

    namespace tests\utils\net\SMTP\Client\Connection;
    use utils\net\SMTP\Client\Message;
    use utils\net\SMTP\Client\ConnectionState;
    use utils\net\SMTP\Client\Connection\State\Closed;
    use utils\net\SMTP\Client\Connection\State\Established;
    use \PHPUnit_Framework_Assert;
    use \PHPUnit_Framework_TestCase;


    abstract class AbstractConnection extends PHPUnit_Framework_TestCase
    {
        
        protected function setUp()
        {
            $this->markTestSkipped();
        }

        abstract public function getPort();
        abstract public function getHostname();
        abstract public function getValidConnection();
        abstract public function getInvalidConnection();

        /**
         * @expectedException \Exception
         */
        public function testConnectionOpeningWithoutListeningServerAt()
        {
            $connection = $this->getInvalidConnection();
        }

        public function testConnectionOpeningWithValidHostnameAndPort()
        {
            $hostname = $this->getHostname();
            $port = $this->getPort();

            try {
                $connection = $this->getValidConnection();
                $this->assertEquals("stream", get_resource_type($connection->getStream()));
            } catch (Exception $e) {
                $message = "Please listen a SMTP server at %s:%d to run this test";
                $this->markTestSkipped(sprintf($message, $hostname, $port));
            }
        }

        public function testConnectionHostnameWhenProvidedValid()
        {
            try {
                $connection = $this->getValidConnection();
                $this->assertEquals($this->getHostname(), $connection->getHostname());
            } catch (Exception $e) {
                $this->markTestSkipped();
            }
        }

        /**
         * @depends testConnectionOpeningWithValidHostnameAndPort
         */
        public function testExchangedMessagesWhenOpenConnection()
        {
            $isInstanceCount = 0;
            $connection = $this->getValidConnection();
            $messages = $connection->getExchangedMessages();
            $this->assertInternalType("array", $messages);
            $this->assertGreaterThanOrEqual(3, $messages);

            foreach ($messages AS $offset => $message) {
                if ($message instanceof Message) {
                    ++$isInstanceCount;
                }
            }

            $this->assertCount($isInstanceCount, $messages);
        }

        /**
         * @depends testConnectionOpeningWithValidHostnameAndPort
         */
        public function testLatestMessageWhenOpenConnection()
        {
            $connection = $this->getValidConnection();
            $latestMessage = $connection->getLatestMessage();

            $this->assertTrue($latestMessage instanceof Message);
            $this->assertEquals(250, $latestMessage->getCode());
        }

        /**
         * @depends testConnectionOpeningWithValidHostnameAndPort
         */
        public function testConnectionStateWhenOpenConnection()
        {
            $connection = $this->getValidConnection();
            $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");

            $this->assertTrue($state instanceof ConnectionState);
            $this->assertTrue($state instanceof Established);
        }

        /**
         * @depends testConnectionOpeningWithValidHostnameAndPort
         */
        public function testConnectionStateAndResourceAfterClose()
        {
            $connection = $this->getValidConnection();
            $connection->close();

            $this->assertFalse($connection->getStream());

            $state = PHPUnit_Framework_Assert::readAttribute($connection, "state");
            $this->assertTrue($state instanceof ConnectionState);
            $this->assertTrue($state instanceof Closed);
        }

    }