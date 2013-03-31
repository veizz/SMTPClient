<?php

    /**
     * @package tests.utils.net.SMTP.Client.Connection
     * @filesource tests\utils\net\SMTP\Client\Connection\AbstractConnection.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     * @author Tiago de Souza Ribeiro <tiago.sr@msn.com>
     */
    namespace tests\utils\net\SMTP\Client\Connection;
    
    use \PHPUnit_Framework_TestCase;
    use utils\net\SMTP\Client\Message;
    use \ReflectionClass;
    use \PHPUnit_Framework_MockObject_Matcher_InvokedAtIndex;

    abstract class AbstractConnection extends PHPUnit_Framework_TestCase
    {
        
        /**
         * @see streamWrapper::stream_eof()
         * @const string
         */
        const EOF = "stream_eof";
        
        /**
         * @see streamWrapper::stream_open()
         * @const string
         */
        const OPEN = "stream_open";
        
        /**
         * @see streamWrapper::stream_write()
         * @const string
         */
        const WRITE = "stream_write";
        
        /**
         * @see streamWrapper::stream_read()
         * @const string
         */
        const READ = "stream_read";
        
        /**
         * @const integer
         */
        const PORT = 123;
        
        /**
         * @const string
         */
        const HOSTNAME = "localhost";
        
        /**
         * @const string
         */
        const PROTOCOL = "tcp";
            
        
        protected function ehloHelo($streamWrapper, array $positions = array(4, 6, 8, 10)) 
        {
            $this->expectWrite($this->at(current($positions)), "EHLO localhost", $streamWrapper);
            next($positions);
            
            $streamWrapper->expects($this->at(current($positions)))
                          ->method(static::READ)
                          ->will($this->returnMessages(250, array(
                              "250-SIZE 35882577",
                              "250-8BITMIME",
                              "250-AUTH LOGIN PLAIN",
                              "250 ENHANCEDSTATUSCODES"
                          )));

            next($positions);
            $this->expectWrite($this->at(current($positions)), "HELO localhost", $streamWrapper);
            
            next($positions);
            $streamWrapper->expects($this->at(current($positions)))
                          ->method(static::READ)
                          ->will($this->returnMessage(250, "Ok"));
        }

        /**
         * @return PHPUnit_Framework_MockObject_MockObject
         */
        protected function getStreamWrapper($protocol, $hostname, $port)
        {
            $streamWrapper = $this->getMockBuilder("StreamWrapper")
                                  ->setMethods(array(static::EOF, static::OPEN, static::READ, static::WRITE))
                                  ->getMock();

            $streamWrapper->expects($this->any())
                          ->method(static::EOF)
                          ->will($this->returnValue(false));

            $streamWrapper->expects($this->at(0))
                          ->method(static::OPEN)
                          ->with(sprintf("%s://%s:%d", $protocol, $hostname, $port))
                          ->will($this->returnValue(true));

            $streamWrapper->expects($this->at(2))
                          ->method(static::READ)
                          ->will($this->returnMessage(220, "Welcome, we're at your service."));
            
            $this->ehloHelo($streamWrapper);
            return $streamWrapper;
        }
        
        protected function expectWrite($index, $message, $streamWrapper)
        {
            $streamWrapper->expects($index)
                          ->method(static::WRITE)
                          ->with(sprintf("%s%s", $message, Message::EOL))
                          ->will($this->returnValue(0));
            
            return $streamWrapper;
        }

        /**
         * @param integer $code the message code
         * @param string $message the message 
         * @return string
         */
        protected function createMessage($code, $message)
        {
            return sprintf("%d %s%s", $code, $message, Message::EOL);
        }

        /**
         * @param integer $code the code of messages
         * @param array[string] $messages all messages to return
         * @return PHPUnit_Framework_MockObject_Stub_Return
         */
        protected function returnMessages($code, array $messages)
        {
            $i = 0;
            $max = count($messages);
            $returnMessages = array();
            
            foreach($messages AS $message) {
                $separator = (++$i >= $max) ? chr(0x20) : "-";
                $returnMessages[] = sprintf("%d%s%s%s", $code, $separator, $message, Message::EOL);
            }
            
            return $this->returnValue(implode($returnMessages));
        }

        /**
         * @param integer $code the code to be returned
         * @param string $message the message to be returned
         * @return PHPUnit_Framework_MockObject_Stub_Return
         */
        protected function returnMessage($code, $message) {
            return $this->returnValue($this->createMessage($code, $message));
        }
        
        protected function getWrapper()
        {
            $streamWrapper = $this->getStreamWrapper(self::PROTOCOL, gethostbyname(self::HOSTNAME), self::PORT);
            return $streamWrapper;
        }

        
    }