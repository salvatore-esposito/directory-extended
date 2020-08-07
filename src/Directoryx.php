<?php
  /**
  * @author Salvatore Esposito
  * @version 1.0.0
  *
  * @copyright All rights reserved by Salvatore Esposito
  * @license GPLv3
  */

  namespace Directory;

  /**
  * class Directory eXtended
  *
  */
class Directoryx extends \Directory
{
    /**
   * Directory name or pathname
   *
   * @since 1.0.1
   * @var string
   */
  private $directoryName;

    /**
   * All Elements in this directory
   *
   * @since 1.0.0
   * @var string
   */
  private $directoryElements;

    /**
   * File constant
   *
   * @since 1.0.0
   */
  public const FILE = 0b01;

    /**
   * File constant
   *
   * @since 1.0.0
   */
  public const DIRECTORY = 0b10;

  /**
   * Create a Directoryx instance
   *
   * @since 1.0.0
   *
   * @param string $directoryName Directory name or pathname
   */
  public function __construct(string $directoryName)
  {
    $this->directoryName = $directoryName;
    $this->directoryElements = $this->scan_dir();
  }

  /**
   * Return if the elements is a file or a dir.
   *
   * @since 1.0.0
   *
   * @param string $element name of elements to be tested.
   * @return bool type file|dir of the element.
   */
  public function isFile($element)
  {
    return is_file($element);
  }

  /**
   * Return the full path of file/dir.
   *
   * @since 1.0.0
   *
   * @param string $fileName Tname or relative path of the file.
   * @return string full path f the file passed.
   */
  public function getRealPath(string $fileName = '') : string
  {
    $fullPathString = $fileName ? "%s/%s" : "%s%s";
    return sprintf($fullPathString, getRealPath($this->directoryName), $fileName);
  }

  /**
   * Scan all element of thsi directory.
   * please note that . and .. are stripped
   *
   * @since 1.0.0
   *
   * @return array array of element founded.
   *
   */
  public function scan_dir() : array
  {
    $dir  = opendir ( $this->getRealPath());
    $result = [];

    while($file = $this->read($dir))
    {
      if(strpos($file, '.') === 0) continue;
      $result[] = $file;
    }
    closedir($dir);
    return $result;
  }

  /**
   * Return all element of $this.
   *
   * @since 1.0.0
   *
   * @return int total file and dir number in the current directory object.
   */
  public function getCountElements() : int
  {
    return count($this->directoryElements);
  }

  /**
   * Return all element of $this.
   *
   * @since 1.0.1
   *
   * @return array total file and dir in the current directory object.
   */
  public function getAllElements() : array
  {
    return $this->directoryElements;
  }

  /**
   * Return the full path of all elements in this directory.
   *
   * @since 1.0.0
   *
   * @param string $callabe is_file or is_dir function.
   *
   * @throws BadFunctionCallException if the provided argument is not
   * is_dir or is_file
   *
   * @return int the total file or dir founded in this dir.
   */
  public function getTotalElementsByType(string $callable = 'is_file') : int
  {
    if($callable !== 'is_file' && $callable !== 'is_dir')
        throw new \BadFunctionCallException('Only is_file or is_dir Accepted');

    $files = 0;
    foreach ($this->directoryElements as $file) {
      if( call_user_func ( $callable, $this->getRealPath($file) ) )
      {
        $files++;
      }
    }

    return $files;
  }

  /**
   * Return the all the elements searched by needle.
   *
   * @since 1.1.0
   *
   * @param string $needle the string to use to search of.
   *
   *
   * @return array the elements filtered by the needle.
   */
  public function searchByString(string $needle) : array
  {
    $found = [];
    foreach ($this->directoryElements as $element) {
      if(strpos ( $element , $needle ) !== FALSE)
      {
        $found[] = $element;
      }
    }

    return $found;
  }

  /**
   * Return the all the elements searched by type.
   *
   * @since 1.1.0
   *
   * @param int a constant type to use to reaearch in elements.
   *
   *
   * @return array the elements filtered by the type supplied.
   */
  public function searchByType(int $type = Directoryx::FILE
                                      | Directoryx::DIRECTORY) : array
  {
    if(!in_array($type, [Directoryx::FILE,
                         Directoryx::DIRECTORY,
                         Directoryx::FILE | Directoryx::DIRECTORY]
                )
      )
    {
      throw new \BadFunctionCallException('Value passed not allowed!');
    }

    foreach ($this->directoryElements as $element) {
      $isRequestedFile = ( $type === Directoryx::FILE );
      $isRequestedDir = ( $type === Directoryx::DIRECTORY );

      $isFile = $this->isFile(($this->getRealPath($element)));

      $switchElement = !( ($isRequestedFile && !$isFile) ||
                          ($isRequestedDir && $isFile) );

      $found[] = $switchElement ? $element : NULL;
    }
    return array_filter($found);
  }

  /**
   * Return the all the elements filtered by the needle and type.
   *
   * @since 1.1.0
   *
   * @param string $needle the string to use to search of.
   * @param int a constant type to use to reaearch in elements.
   *
   * @return array the elements filtered by the type and needle supplied.
   */
  public function searchByStringAndType(string $needle, int $type = Directoryx::FILE
                                      | Directoryx::DIRECTORY) : array
  {
    $byString = $this->searchByString($needle);
    return $this->searchByType($byString);
  }

}
