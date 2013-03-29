<?php

    /**
     * @filesource tests\utils\net\SMTP\Message\Header\AbstractHeader.php
     * @author Tiago de Souza Ribeiro <tiago.sr@msn.com>
     */
    namespace tests\utils\net\SMTP\Message\Header;

    use utils\net\SMTP\Message\Address;
    use \PHPUnit_Framework_TestCase;

    abstract class AbstractHeader extends PHPUnit_Framework_TestCase
    {

        abstract public function testGetNameIsEqualsToHeaderName();

        abstract public function getHeader();

        public function assertPreConditions()
        {
            $this->assertTrue($this->getHeader() instanceof \utils\net\SMTP\Message\AddressList);
        }

        public function testAddressListIsEmpty()
        {
            $this->assertCount(0, $this->getHeader()->getAddresses());
        }

        public function providerForNamesAndEmailAddresses()
        {
            return array (array (
                    array (
                        array ("test@test.com", "test"),
                        array ("test1@test.com", "test1"),
                        array ("test2@test.com", null)
                    )
            ));
        }

        /**
         * @dataProvider providerForNamesAndEmailAddresses
         */
        public function testAddressesAreEqualsToProvidedAddresses($addressesSet)
        {
            $header = $this->getHeader();
            $stacked = array ();
            foreach ($addressesSet as $address) {
                $data = array_combine(array ("email", "author"), $address);
                $header->addAddress($addr = new Address($data["email"], $data["author"]));
                $stacked[] = $addr;
            }

            $this->assertEquals($stacked, $header->getAddresses());
        }

        /**
         * @dataProvider providerForNamesAndEmailAddresses
         */
        public function testAddressesNumberIsEqualToProvidedAddressesNumber($addressesSet)
        {
            $header = $this->getHeader();
            foreach ($addressesSet as $address) {
                $data = array_combine(array ("email", "author"), $address);
                $header->addAddress(new Address($data["email"], $data["author"]));
            }

            $this->assertCount(count($addressesSet), $header);
        }

        public function testHeaderInitialEncodingIsEqualToAscii()
        {
            $this->assertEquals("ASCII", $this->getHeader()->getEncoding());
        }

    }