<?php

    /**
     * @package utils.net.SMTP.Client
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\net\SMTP\Client\AbstractConnector.php
     */
    namespace utils\net\SMTP\Client;
    use utils\net\SMTP\Client\Connection;
    use utils\net\SMTP\Client\Message;
    use \Exception;
    use \ErrorException;
    use \LogicException;

    abstract class AbstractConnection implements Connection
    {
        /**
         * Determines if the connection to the server was been established
         * @var boolean
         */
        protected $established = false;

        /**
         * SMTP server hostname
         * @var string 
         */
        private $hostname;

        /**
         * Stores the latest server reply
         * @var string
         */
        private $lastMessage = null;

        /**
         * Stores all messages exchanged between client and server on current connection.
         * @var array[Message]
         */
        private $messages = array();

        /**
         * Retrieves SMTP server hostname.
         * @return string
         */
        public function getHostname()
        {
            return $this->hostname;
        }

        /**
         * Retrieves latest server reply.
         * @return string
         */
        public function getLatestServerReply()
        {
            return $this->lastMessage;
        }

        /**
         * Opens a connection with an SMTP server. 
         * @see AbstractConnection::open()
         */
        public function __construct($host, $port, $timeout = 30)
        {
            $this->open($host, $port, $timeout);
        }

        /**
         * Stream connected to SMTP server.
         * @var stream|NULL
         */
        private $stream = NULL;

        /**
         * Creates the client connection with SMTP server.
         * 
         * @param string $protocol protocol used to connect to the server
         * @param string $hostname SMTP server hostname
         * @param integer $port the SMTP server port
         * @param integer $timeout timeout in seconds for wait a connection.
         * 
         * @throws ErrorException if couldn't connect to the server
         * @throws LogicException if connect timeout is equals zero
         * @return boolean
         */
        public function createStreamSocketClient($protocol, $hostname, $port, $timeout = 30)
        {
            if (is_null($this->stream)) {
                if ($timeout < 0) {
                    $message = "Timeout must be greater than zero.";
                    throw new LogicException($message);
                }

                if (($host = gethostbyname($hostname)) !== $hostname) {
                    $errno = 0;
                    $errstr = NULL;

                    $remote = sprintf("%s://%s:%d", $protocol, $host, $port);
                    $this->stream = @stream_socket_client($remote, $errno, $errstr, $timeout);
                    
                    if ($this->stream === false) {
                        $message = sprintf("Couldn't connect to SMTP Server %s:%d", $host, $port);
                        throw new Exception($message, $errno, new ErrorException($errstr, $errno));
                    }
                } else {
                    $message = "The server's hostname is invalid, cannot resolve %s";
                    throw new ErrorException(sprintf($message, $hostname));
                }
            }

            $greeting = $this->read();
            $this->hostname = $host;
            return true;
        }

        /**
         * Retrieves the stream we're connected to.
         * @return stream|NULL
         */
        public function getStream()
        {
            return $this->stream;
        }

        /**
         * Writes data to the conected SMTP server.
         * @param string $data the data to be written.
         * @return integer
         */
        public function write($data)
        {
            $this->messages[] = new Message($data);
            return fwrite($this->getStream(), $data);
        }

        /**
         * Reads one line of the stream.
         * 
         * @link https://tools.ietf.org/html/rfc1869 Maximum command line length, 4.1.2
         * @return string
         */
        public function read()
        {
            while (!feof($this->getStream())) {
                $message = new Message(fgets($this->getStream(), 515));
                $this->messages[] = $message;

                if (substr($message, 3, 1) === chr(32)) {
                    $this->lastMessage = $message;
                    return $message;
                }
            }
        }

        /**
         * Retrieves all exchanged messages between client and server.
         * @return array[Message]
         */
        public function getExchangedMessages()
        {
            return $this->messages;
        }

        /**
         * Checks if the connection was properly established.
         * @return boolean
         */
        public function isEstablished()
        {
            return !!$this->established;
        }

        /**
         * Closes the opened stream resouce to free memory.
         * @return void
         */
        public function close()
        {
            if (fclose($this->stream)) {
                $this->stream = NULL;
                $this->established = false;
            }
        }

    }