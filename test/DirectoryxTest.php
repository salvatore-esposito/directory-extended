<?php

require dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Directory\Directoryx;

class DirectoryxTest extends TestCase
{
    protected $dirx;

    public function setUp() : void
    {
      /* need to ovverride some methods */
      $this->dirx = new class('dummy_name') extends Directoryx
            {
              /**
              * override
              */
              public function scanDir() : array
              {
                return range(1,10);
              }

              /**
              * override
              */
              public function getRealPath(string $fileName = '') : string
              {
                return 'dummy_path';
              }

              /**
              * override
              */
              public function isFile($element)
              {
                return true;
              }


            };

            $this->dirx->directoryElements = range(1,10);
    }

    public function testisFile() : void
    {
      $element = 'Imafile';
      $this->assertTrue($this->dirx->isFile($element));
    }

    public function testgetCountElements() :void
    {
      $expected = count(range(1,10));

      $this->assertEquals(
         $expected,
         $this->dirx->getCountElements());
     }

    public function testSearchByString() : void
    {
      $needle = '2';

      $this->assertEquals(
          ['2'],
          $this->dirx->searchByString($needle)
       );
    }

    public function testGetByType() : void
    {
      $expected = range(1,10);

      $this->assertNotEquals(
         $expected,
         $this->dirx->searchByType(Directoryx::DIRECTORY)
      );

      $this->assertEquals(
         $expected,
         $this->dirx->searchByType(Directoryx::FILE)
      );

      $this->assertEquals(
        $expected,
        $this->dirx->searchByType(Directoryx::FILE || Directoryx::DIRECTORY)
      );

      $this->assertEquals(
        $expected,
        $this->dirx->searchByType()
      );
    }

    public function testGetTotalElementsByType() : void
    {
      $expected = 10;

      $this->assertEquals(
        $expected,
        $this->dirx->getTotalElementsByType(Directoryx::FILE)
      );

    }

}
