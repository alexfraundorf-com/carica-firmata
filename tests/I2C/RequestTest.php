<?php

namespace Carica\Firmata\I2C {

  include_once(__DIR__ . '/../Bootstrap.php');

  use Carica\Io;
  use Carica\Firmata;

  class RequestTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers Carica\Firmata\I2C\Request
     */
    public function testSendReadRequest() {
      $expected = "\xF0\x76\x02\x00\x37\x00\xF7";
      $request = new Request(
        $this->getBoardWithStreamFixture($expected),
        2,
        Firmata\I2C::MODE_READ,
        7
      );
      $request->send();
    }
    /**
     * @covers Carica\Firmata\I2C\Request
     */
    public function testSendWriteRequest() {
      $expected = "\xF0\x76\x03\x00\x48\x00\x61\x00\x6C\x00\x6C\x00\x6F\x00\xF7";
      $request = new Request(
        $this->getBoardWithStreamFixture($expected),
        3,
        Firmata\I2C::MODE_WRITE,
        'Hallo'
      );
      $request->send();
    }

    /**
     * @covers Carica\Firmata\I2C\Request
     */
    public function testSendWriteRequestWithArray() {
      $expected = "\xF0\x76\x03\x00\x7f\x01\x00\x00\x70\x01\xF7";
      $request = new Request(
        $this->getBoardWithStreamFixture($expected),
        3,
        Firmata\I2C::MODE_WRITE,
        [0xFF, 0x00, 0xF0]
      );
      $request->send();
    }

    public function getBoardWithStreamFixture($data) {
      $stream = $this->getMock('Carica\\Io\\Stream');
      $stream
        ->expects($this->once())
        ->method('write')
        ->with($data);
      $board = $this
        ->getMockBuilder('Carica\\Firmata\\Board')
        ->disableOriginalConstructor()
        ->getMock();
      $board
        ->expects($this->any())
        ->method('stream')
        ->will($this->returnValue($stream));
      return $board;
    }
  }
}