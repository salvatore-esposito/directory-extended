<?php

require dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Directory\Directoryx;

class DirectoryxTest extends TestCase
{
    protected $dirx;

    public function setUp() : void
    {
      /* need to redeclare scan_dir */
      $this->dirx = new class('dummy_name') extends Directoryx
            {
              /**
              * override
              */
              public function scan_dir() : array
              {
                $scanDir = range(1,10);
                //$scanDir['elementsType'] =  [0,1,0,1,1,0,1,1,0,1 ];
                return $scanDir;
              }

              public function getRealPath(string $fileName = '')
              {
                return 'dummy_path';
              }

              public function isFile($element)
              {
                return true;
              }


            };
    }

    public function testSearchByString() : void
    {
      $needle = '2';

      $this->assertEquals(
                  ['2'],
                  $this->dirx->searchByString($needle, Directoryx::FILE)
             );
      $this->assertEquals(
                  ['2'],
                  $this->dirx->searchByString($needle, Directoryx::FILE | Directoryx::DIRECTORY)
              );
    }
}
