<?php

    /**
     * @package utils.Net.SMTP
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @filesource utils\Net\SMTP\AbstractSTMPClientConnection.php
     */
    namespace utils\Net\SMTP;
    use utils\Net\SMTP\SMTPConnection;
    use \ErrorException;

    abstract class AbstractSMTPClientConnection implements SMTPConnection
    {

        /**
         * Determines if the connection to the server was been established.
         * @var boolean
         */
        protected $established = false;

        /**
         * SMTP server hostname.
         * @var string 
         */
        private $hostname;
        
        /**
         * Stores the latest server reply.
         * @var string
         */
        private $lastMessage = null;
        
        /**
         * Stores all messages exchanged between client and server on current connection.
         * @var array[string]
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
        public function getLatestServerReply() {
            return $this->lastMessage;
        }

        /**
         * Opens a connection with an SMTP server. 
         * @see \utils\Net\SMTP\AbstractSMTPClientConnection::open()
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
         * Connects to a server socket with specified information. 
         * @throws ErrorException if couldn't connect to the server
         * @param string $protocol the protocol used to connect to the server.
         * @param string $host the server hostname.
         * @param integer $port server's port.
         * @param integer $timeout timeout in seconds for wait a connection.
         * @return boolean
         */
        public function createStreamSocketClient($protocol, $host, $port, $timeout = 3)
        {
            if (is_null($this->stream)) {
                $this->stream = stream_socket_client(sprintf("%s://%s:%d", $protocol, $host, $port));
                stream_set_timeout($this->getStream(), $timeout);

                if ($this->stream === false) {
                    $message = sprintf("Couldn't connect to SMTP Server %s:%d", $host, $port);
                    throw new ErrorException($message);
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
            $this->messages[] = str_replace("\r\n", NULL, $data);
            return fwrite($this->getStream(), $data);
        }

        /**
         * Reads one line of the stream.
         * @return string
         */
        public function read()
        {
            while (!feof($this->getStream())) {
                $message = str_replace("\r\n", NULL, fgets($this->getStream(), 1024));
                $this->messages[] = $message;
                
                if (substr($message, 3, 1) === chr(32)) {
                    break;
                }
            }
            
            $this->lastMessage = $message;
            return $this->getLatestServerReply();
        }
        
        /**
         * Retrieves all exchanged messages between client and SMTP server.
         * @return array[string]
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
        public function close() {
            if(fclose($this->stream)){
                $this->stream = NULL;
                $this->established = false;
            }
        }

    }