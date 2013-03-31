<?php

    use utils\net\SMTP\Message\HeaderSet;

    class HeaderSetTest extends PHPUnit_Framework_TestCase
    {
        
        private function createHeader($name, $value)
        {
            $header = $this->getMockBuilder("\\utils\\net\\SMTP\\Message\\Header")
                           ->setMethods(array("getName", "getValue", "__toString"))
                           ->getMock();
            
            $header->expects($this->any())->method("getName")->will($this->returnValue($name));
            $header->expects($this->any())->method("getValue")->will($this->returnValue($value));
            return $header;
        }

        public function testSetIsEmpty()
        {
            $headerSet = new HeaderSet();
            $this->assertEquals(0, count($headerSet));
            $this->assertEquals(0, count($headerSet->toArray()));
            $this->assertEquals(0, count($headerSet->getIterator()));
        }

        public function testInsertHeaderIntoSet()
        {
            $headerSet = new HeaderSet();
            $headerSet = $headerSet->insert($this->createHeader("X-Test", "test"));
            $this->assertTrue($headerSet instanceof HeaderSet);
        }
        
        public function testRemoveHeaderFromSet()
        {
            $headerSet = new HeaderSet();
            $headerSet->insert($this->createHeader("X-Test", "test"));
            $this->assertTrue($headerSet->remove("X-Test"));
        }
        
        public function testRemoveUnexistentHeaderFromSet() {
            $headerSet = new HeaderSet();
            $this->assertNotSame(true, $headerSet->remove("X-Test"));
        }
        
        public function testGetExistentHeader()
        {
            $headerSet = new HeaderSet();
            $headerSet->insert($this->createHeader("X-Test", "test"));
            $this->assertTrue($headerSet->getHeader("X-Test") instanceof utils\net\SMTP\Message\Header);
        }
        
        public function testGetUnexistentHeader() {
            $headerSet = new HeaderSet();
            $this->assertFalse($headerSet->getHeader("X-Test"));
        }
        
        public function testContains() {
            $headerSet = new HeaderSet();
            $headerSet->insert($this->createHeader("X-Test", "test"));
            $this->assertTrue($headerSet->contains("X-Test"));
        }
        
        public function testContainsWithUnexistentHeader() {
            $headerSet = new HeaderSet();
            $this->assertFalse($headerSet->contains("X-Test"));
        }
        
        public function testToArray() {
            $headerSet = new HeaderSet();
            $this->assertInternalType("array", $headerSet->toArray());
        }
        
        public function testGetIterator() {
            $headerSet = new HeaderSet();
            $this->assertInstanceOf("Iterator", $headerSet->getIterator());
            $this->assertInstanceOf("ArrayIterator", $headerSet->getIterator());
        }
        
    }

    