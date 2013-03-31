<?php

    namespace tests\utils\net\SMTP\Message;
    use utils\net\SMTP\Message\HeaderWrapper;

    class HeaderWrapperTest extends \PHPUnit_Framework_TestCase
    {

        /**
         * @expectedException InvalidArgumentException
         * @expectedExceptionMessage We can wrap only structured or unstructured headers
         */
        public function testWrapWithInvalidArgument()
        {
            $header = $this->getMock("\\utils\\net\\SMTP\\Message\\Header");
            HeaderWrapper::wrap($header);
        }

        public function testWrapStructured()
        {
            $header = $this->getMockBuilder("\\utils\\net\\SMTP\\Message\\Header\\Type\\Structured")
                           ->setMethods(array("getValue", "getName", "getDelimiter", "__toString"))
                           ->getMock();
                           
            $header->expects($this->once())
                   ->method("getDelimiter")
                   ->will($this->returnValue("|"));
                   
            $header->expects($this->exactly(2))
                   ->method("getValue")
                   ->will($this->returnValue("Test|test|test|test|test|test"));
            
           $wrapped = HeaderWrapper::wrap($header);
           $this->assertNotEquals($header->getValue(), $wrapped);
           $this->assertEquals("Test|\r\ntest|\r\ntest|\r\ntest|\r\ntest|", $wrapped);
        }

    }