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

            private function getConnection($streamWrapper)
            {
                $this->expectWrite($this->at(12), "STARTTLS", $streamWrapper);
                $streamWrapper->expects($this->at(14))
                              ->method(self::READ)
                              ->will($this->returnMessage(220, "2.0.0 Ready to start TLS"));
                
                //Ehlo and helo will be performed after TLS negotiation at [16, 18, 20, 22].
                $this->ehloHelo($streamWrapper, array(16, 18, 20, 22));
                
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

        }

    }
    