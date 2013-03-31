<?php

    use utils\net\SMTP\Message;
    use utils\net\SMTP\Message\Composer;
    use utils\net\SMTP\Message\HeaderSet;

    class ComposerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @param string $name header name
         * @param string $value header value
         * @return PHPUnit_Framework_MockObject_MockObject
         */
        private function createHeader($name, $value)
        {
            $header = $this->getMockBuilder("\\utils\\net\\SMTP\\Message\\Header")
                           ->setMethods(array("__toString", "getName", "getValue"))
                           ->getMock();
            
            $header->expects($this->any())->method("getName")->will($this->returnValue($name));
            $header->expects($this->any())->method("getValue")->will($this->returnValue($value));
            
            $header->expects($this->any())
                   ->method("__toString")
                   ->will($this->returnValue(sprintf("%s: %s", $name, $value)));
            
            return $header;
        }

        public function testComposeHeadersWithUniqueHeader()
        {
            $set = new HeaderSet();
            $set->insert($this->createHeader("Test", "test"));
            
            $composer = new Composer();
            $reflection = new ReflectionClass($composer);
            $method = $reflection->getMethod("composeHeaders");
            $method->setAccessible(true);
            
            $this->assertEquals("Test: test", $method->invokeArgs($composer, array($set)));
        }
        
        
        public function testComposeHeadersWithMultipleHeaders()
        {
            $set = new HeaderSet();
            $set->insert($this->createHeader("Test", "test"));
            $set->insert($this->createHeader("TestAbc", "testABC"));
            
            $composer = new Composer();
            
            $reflection = new ReflectionClass($composer);
            $method = $reflection->getMethod("composeHeaders");
            $method->setAccessible(true);
            
            $this->assertEquals("Test: test\r\nTestAbc: testABC", $method->invokeArgs($composer, array($set)));
        }
        
        public function testComposeMessage()
        {
            $set = new HeaderSet();
            $set->insert($this->createHeader("From", "test@test.com"));
            $set->insert($this->createHeader("Subject", "Just Testing"));
            
            $message = $this->getMockBuilder("\\utils\\net\\SMTP\\Message")
                            ->setMethods(array("getHeaderSet", "getBody"))
                            ->getMock();
            
            $message->expects($this->atLeastOnce())
                    ->method("getHeaderSet")
                    ->will($this->returnValue($set));
            
            $message->expects($this->atLeastOnce())
                    ->method("getBody")
                    ->will($this->returnValue("Testing ..."));
            
            $composer = new Composer();
            $composeExpects = "From: test@test.com\r\nSubject: Just Testing\r\n\r\nTesting ...";
            $this->assertEquals($composeExpects, $composer->compose($message));
        }

    }