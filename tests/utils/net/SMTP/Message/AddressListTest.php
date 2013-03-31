<?php
    
    use utils\net\SMTP\Message\Address;
    
    class AddressListTest extends PHPUnit_Framework_TestCase
    {
        
        protected function getAddressList()
        {
            $stub = $this->getMockBuilder("\\utils\\net\\SMTP\\Message\\AbstractAddressList")
                         ->setMethods(array("getName"))
                         ->getMockForAbstractClass();
            
            $stub->expects($this->any())
                 ->method("getName")
                 ->will($this->returnValue("Test"));
            
            return $stub;
        }

        public function testGetSetEncoding()
        {
            $addressList = $this->getAddressList();
            $this->assertEquals("ASCII", $addressList->getEncoding());
            
            $addressList->setEncoding("UTF-8");
            $this->assertEquals("UTF-8", $addressList->getEncoding());
        }
        
        public function testAddressListToStringWithOneAddressThatHasEmailAndName()
        {
            $addressList = $this->getAddressList();
            $addressList->addAddress(new Address("test@test.com", "Just Test"));
            $this->assertEquals("Test: Just Test <test@test.com>\r\n", (string) $addressList);
        }
        
        public function testAddressListToStringWithAddressThatHasOnlyEmail()
        {
            $addressList = $this->getAddressList();
            $addressList->addAddress(new Address("test@test.com"));
            $this->assertEquals("Test: test@test.com\r\n", (string) $addressList);
        }
        
        public function testAddressListToStringWithMultipleAddresses()
        {
            $addressList = $this->getAddressList();
            $addressList->addAddress(new Address("test@test.com"));
            $addressList->addAddress(new Address("test2@test.com", "Just The Second One"));
            $this->assertEquals("Test: test@test.com, Just The Second One <test2@test.com>\r\n", (string) $addressList);
        }
        
        public function testAddressListIteratorAndCountable()
        {
            $addressList = $this->getAddressList();
            $addressList->addAddress(new Address("test1@test.com"));
            $addressList->addAddress(new Address("test2@test.com"));
            
            $this->assertSame(2, count($addressList));
            $this->assertInstanceOf("Iterator", $addressList->getIterator());
            $this->assertInstanceOf("ArrayIterator", $addressList->getIterator());
        }

    }