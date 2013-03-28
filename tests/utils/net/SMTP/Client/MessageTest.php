<?php

    /**
     * @filesource tests\utils\net\SMTP\Client\MessageTest.php
     * @author Tiago de Souza Ribeiro <tiago.sr@msn.com>
     */
    use utils\net\SMTP\Client\Message;

    class MessageTest extends PHPUnit_Framework_TestCase
    {

        public function testEndOfLineIsReturnAndNewline()
        {
            $this->assertEquals("\r\n", Message::EOL);
        }

        public function testProvidedMessageIsSameAsSetted()
        {
            $message = new Message("Test");
            $this->assertEquals("Test", $message->getFullMessage());
        }

        public function testFullMessageIsNotNullWhenProvided()
        {
            $message = new Message("Test");
            $this->assertNotNull($message->getFullMessage());
        }

        public function testCodeIsZeroWhenThreeFirstCharactersInMessageAreNotNumeric()
        {
            $message = new Message("Test");
            $this->assertEquals(0, $message->getCode());
        }

        public function testCodeIsThreeFirstCharactersInMessageWhenTheyAreNumeric()
        {
            $message = new Message("123Test");
            $this->assertEquals(123, $message->getCode());
        }

        public function testMessageIsFullMessageWhenCodeIsZero()
        {
            $message = new Message("Test");
            $this->assertEquals(0, $message->getCode());
            $this->assertEquals("Test", $message->getMessage());
        }

        public function testMessageIsNotFullMessageWhenCodeIsNotZero()
        {
            $message = new Message("123Test");
            $this->assertNotEquals(0, $message->getCode());
            $this->assertNotEquals("123Test", $message->getMessage());
            $this->assertEquals("Test", $message->getMessage());
        }

        public function testToStringIsFullMessageWhenProvided()
        {
            $message = new Message("Test");
            $this->assertEquals("Test", (string) $message);
        }

    }