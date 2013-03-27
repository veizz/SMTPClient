<?php

    /**
     * @filesource AddressTest.php
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */
    use utils\net\SMTP\Message\Address;

    class AddressTest extends PHPUnit_Framework_TestCase
    {

        public function testAddressAuthorNameIsNullWhenNoSpecified()
        {
            $address = new Address("test@test.com");
            $this->assertNull($address->getName());
        }

<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> a52c5d0... Added test for author name data type
        public function testAddressAuthorNameIsNotNullWhenSpecified()
        {
            $address = new Address("test@test.com", "Test");
            $this->assertNotNull($address->getName());
            $this->assertEquals("Test", $address->getName());
        }
<<<<<<< HEAD
        
=======

>>>>>>> 2a97167... Added test for author name data type
>>>>>>> a52c5d0... Added test for author name data type
        public function providerForInvalidEmail()
        {
            return array(
                array(array(
                    NULL, false, true, "",
                    "test@domain", "test¹²@", "test+_+@#.m",
                    "test@domain@lalala.com", "1@2.3", "test@domain..com"
                ))
            );
        }
        
        public function providerForValidEmail() {
            return array(
                array(array(
                    'test@test.com', 'a.b@c.d', 'test@gmail.com.br',
                    'test_01@hotmail.com', 'test_email_01@gmail.com.br',
                    'test+email@gmail.com'
                ))
            );
        }

        /**
         * @dataProvider providerForInvalidEmail
         * @expectedException InvalidArgumentException
         */
        public function testAddressCreationWithInvalidSetOfAddresses($addresses)
        {
            foreach ($addresses AS $email) {
                $addresses = new Address($email);
            }
        }
        
        /**
         * @dataProvider providerForValidEmail
         */
        public function testAddressCreationWithValidSetOfAddresses($addresses)
        {
            $accepted = 0;
            foreach($addresses AS $email) {
                $address = new Address($email);
                ++$accepted;
            }
            
            $this->assertEquals($accepted, count($addresses));
        }
<<<<<<< HEAD
    
=======

<<<<<<< HEAD
<<<<<<< HEAD
        public function testIfProvidedAddressIsSameAsSetted()
        {
            $address = new Address("test@test.com");
            $this->assertNotNull($address->getEmail());
            $this->assertEquals("test@test.com", $address->getEmail());
        }

        public function testIfProvidedNameIsSameAsSetted()
        {
            $address = new Address("test@test.com", "Test");
            $this->assertNotNull($address->getName());
            $this->assertEquals("Test", $address->getName());
        }
=======
	   public function testAddressAuthorNameIsValidTypeWhenSpecified()
=======
	   public function testIfProvidedAddressIsSameAsSetted()
>>>>>>> 6faba8a... Add tests in setted values
	   {
		   $address = new Address("test@test.com");
		   $this->assertNotNull("test@test.com", $address->getEmail());
		   $this->assertEquals("test@test.com", $address->getEmail());
	   }

	   public function testIfProvidedNameIsSameAsSetted()
	   {
		   $address = new Address("test@test.com", "Test");
		   $this->assertNotNull("Text", $address->getName());
		   $this->assertEquals("Test", $address->getName());
	   }
>>>>>>> 2a97167... Added test for author name data type

>>>>>>> a52c5d0... Added test for author name data type
    }