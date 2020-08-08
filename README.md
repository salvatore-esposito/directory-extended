# Directory manipulator class

### Install
Installing dir is simple:  you can download the class and use it or better you can use composer:

`composer require salvio/dir`

PHP 7.0 is required.

### Some examples

##### Create a new instance pointing to a directory:

use Directory\Directoryx

$mydir = new Directoryx( '/home/imauser/mydir' );

##### Return all the elements of this dir:

$mydir->getCountElements();

##### Return all the elements of this dir by type:

- ###### all directories:

	$dirx->getTotalElementsByType(Directoryx::DIRECTORY);


-  ###### all files:

	$dirx->getTotalElementsByType(Directoryx::FILES);

- ###### all files and directories:

	$dirx->getTotalElementsByType();

	$dirx->getTotalElementsByType(Directoryx::FILES | Directoryx::DIRECTORY);

##### Return all files and directories by a supplied string:

$dirx->searchByString('mar');

##### Return all elements by type and by a supplied string:

$dirx->searchByStringType( 'mar', Directoryx::DIRECTORY );
