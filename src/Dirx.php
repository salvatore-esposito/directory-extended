<?php
  /**
  * @author Salvatore Esposito
  * @version 3.0.0
  *
  * @copyright All rights reserved by Salvatore Esposito
  * @license GPLv3
  */

  namespace Salvio;

  /**
  * class Directory eXtended
  *
  */
class Dirx extends \Directory
{
    /**
   * Directory name or pathname
   *
   * @since 1.0.0
   * @var string
   */
  private $directoryName;

    /**
   * All Elements in this directory
   *
   * @since 1.1.0
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
   * Create a Dirx instance
   *
   * @since 1.0.0
   *
   * @param string $directoryName Directory name or pathname
   */
  public function __construct(string $directoryName)
  {
    $this->directoryName = $directoryName;
    $this->directoryElements = $this->scanDir();
  }

  /**
   * Return if the elements is a file or a dir.
   *
   * @since 1.1.0
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
    return sprintf($fullPathString, realpath($this->directoryName), $fileName);
  }

  /**
   * Scan all elements of this directory.
   * please note that . and .. are stripped
   *
   * @since 1.0.0
   *
   * @return array array of element founded.
   *
   */
  public function scanDir() : array
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
   * Return total count of elements of $this.
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
   * Return all elements of $this.
   *
   * @since 1.0.1
   *
   * @return array total files and dirs in the current directory object.
   */
  public function getAllElements() : array
  {
    return $this->directoryElements;
  }

  /**
   * Return total count of elements of passed type.
   *
   * @since 1.0.0
   *
   * @param int a constant type to use to reaearch in elements.
   *
   * @throws BadFunctionCallException if the provided argument is not
   * Dirx::FILE or Dirx::DIRECTORY
   *
   * @return int the total files or dirs founded in this dir.
   */
  public function getTotalElementsByType(int $type =  Dirx::FILE) : int
  {
    if($type !== Dirx::FILE && $type !== Dirx::DIRECTORY)
        throw new \BadFunctionCallException('Mismatch constant passed!');

    return count($this->searchByType($type));
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
  public function searchByType(int $type = Dirx::FILE
                                      | Dirx::DIRECTORY, array $elements = [] ) : array
  {
    if(!in_array($type, [Dirx::FILE,
                         Dirx::DIRECTORY,
                         Dirx::FILE | Dirx::DIRECTORY]
                )
      )
    {
      throw new \BadFunctionCallException('Value passed not allowed!');
    }

    $searchInThisElements = count($elements) ? $elements : $this->directoryElements;

    foreach ($searchInThisElements as $element) {
      $isRequestedFile = ( $type === Dirx::FILE );
      $isRequestedDir = ( $type === Dirx::DIRECTORY );

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
  public function searchByStringType(string $needle, int $type = Dirx::FILE
                                      | Dirx::DIRECTORY) : array
  {
    $byString = $this->searchByString($needle);
    return $this->searchByType($type, $byString);
  }

}
