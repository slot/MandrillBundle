<?php

use Hip\MandrillBundle\Message;

/**
 * Message Tests
 *
 * @author   : Sven Loth <sven.loth@hipaway.com>
 * @copyright: 2013 Hipaway Travel GmbH, Berlin
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testToIsInitializedAsArray()
    {
        $message = new Message();
        $recipientArray = $message->getTo();

        $this->assertTrue(is_array($recipientArray));
    }

    public function testAddToWithoutName()
    {
        $message = new Message();
        $message->addTo('test@email.com');
        $recipientArray = $message->getTo();

        $this->assertTrue(is_array($recipientArray));
        $this->assertEquals(count($recipientArray), 1);
        $this->assertArrayHasKey('email', $recipientArray[0]);
        $this->assertEquals($recipientArray[0]['email'], 'test@email.com');
        $this->assertArrayHasKey('name', $recipientArray[0]);
        $this->assertEquals($recipientArray[0]['name'], '');
    }

    public function testAddToWithName() {
        $message = new Message();
        $message->addTo('test@email.com', 'Abc Def');
        $recipientArray = $message->getTo();

        $this->assertTrue(is_array($recipientArray));
        $this->assertEquals(count($recipientArray), 1);
        $this->assertArrayHasKey('email', $recipientArray[0]);
        $this->assertEquals($recipientArray[0]['email'], 'test@email.com');
        $this->assertArrayHasKey('name', $recipientArray[0]);
        $this->assertEquals($recipientArray[0]['name'], 'Abc Def');
    }

    public function testAddToWith2Recipients() {
        $message = new Message();
        $message->addTo('test@email.com', 'Abc Def');
        $message->addTo('test2@email.com', 'Foo Bar');
        $recipientArray = $message->getTo();

        $this->assertTrue(is_array($recipientArray));
        $this->assertEquals(count($recipientArray), 2);
        $this->assertArrayHasKey('email', $recipientArray[0]);
        $this->assertEquals($recipientArray[0]['email'], 'test@email.com');
        $this->assertArrayHasKey('name', $recipientArray[0]);
        $this->assertEquals($recipientArray[0]['name'], 'Abc Def');

        $this->assertArrayHasKey('email', $recipientArray[1]);
        $this->assertEquals($recipientArray[1]['email'], 'test2@email.com');
        $this->assertArrayHasKey('name', $recipientArray[1]);
        $this->assertEquals($recipientArray[1]['name'], 'Foo Bar');
    }

    public function testAddToWithTypes()
    {
        $message = new Message();
        $message->addTo('to-test@example.com', 'Foo Bar');
        $message->addTo('cc-test@example.com', 'Foo User', 'cc');
        $message->addTo('bcc-test@example.com', 'Bar User', 'bcc');
        $recipientArray = $message->getTo();

        $this->assertTrue(is_array($recipientArray));
        $this->assertEquals(count($recipientArray), 3);
        $this->assertArrayHasKey('type', $recipientArray[0]);
        $this->assertEquals($recipientArray[0]['type'], 'to');
        $this->assertArrayHasKey('type', $recipientArray[1]);
        $this->assertEquals($recipientArray[1]['type'], 'cc');
        $this->assertArrayHasKey('type', $recipientArray[2]);
        $this->assertEquals($recipientArray[2]['type'], 'bcc');
    }

    public function testHeaderIsInitialized()
    {
        $message = new Message();

        $this->assertTrue(is_array($message->getHeaders()));
    }

    public function testAddReplyToHeader()
    {

        $message = new Message();

        $message->addHeader('Reply-To', 'test@email.com');

        $headers = $message->getHeaders();

        $this->assertTrue(is_array($headers));
        $this->assertEquals($headers['Reply-To'], 'test@email.com');

    }

    public function testAddXHeader()
    {
        $message = new Message();

        $message->addHeader('X-Binford', 'more power (9100)');

        $headers = $message->getHeaders();

        $this->assertTrue(is_array($headers));
        $this->assertEquals($headers['X-Binford'], 'more power (9100)');

    }

    public function testSetSubaccount()
    {
        $message = new Message();

        $message->setSubaccount('Subaccount Name');

        $this->assertEquals($message->getSubaccount(), 'Subaccount Name');
    }
    
    public function testAddImage()
    {
        $message = new Message();
        
        $message->addImage('image/jpg', '1x1', '/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAf/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AL+AD//Z');
        
        $images = $message->getImages();
        
        $this->assertEquals(1, count($images));
        $this->assertEquals($images[0]['name'], '1x1');
    }

    public function testSetReplyTo()
    {
        $testString = "foo@bar.baz";

        $message = new Message();

        $message->setReplyTo($testString);
        $headers = $message->getHeaders();
        $this->assertEquals($testString, $headers['Reply-To']);
    }

    public function testIsImportant()
    {
        $message = new Message();
        $message->isImportant();
        $headers = $message->getHeaders();
        $this->assertEquals('High', $headers['Importance']);
        $this->assertEquals('urgent', $headers['Priority']);
    }

    public function testSetMergeVar()
    {
        $message = new Message();
        $this->assertEquals(false, $message->getMerge());
        $message->addMergeVar('test@foo.com', 'testkey', 'testvalue');
        $this->assertEquals(true, $message->getMerge());
    }

    public function testSetGlobalMergeVar()
    {
        $message = new Message();
        $this->assertEquals(false, $message->getMerge());
        $message->addGlobalMergeVar('testkey', 'testvalue');
        $this->assertEquals(true, $message->getMerge());
    }

}