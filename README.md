# SimpleZipArchive #

Version 0.1

by Alexander Nikonov <http://habrahabr.ru/users/alexanderphp/>

## Introduction ##
Simple ZipArchive - is a simple tool for working with zip archives in PHP. Simple ZipArchive will create the archive directory, add a comment, write, read and delete files, as well as detailed information on the desired file.

## Minimum Requirements ##
Having a library of ZipArchive

### Installation ##

Setup is very easy. Simply download the class to the desired directory and declare it

Example:

    require_once 'SimpleZipArchive.php';
    $ZIP = new SimpleZipArchive('test.zip');


## Key Features ##

### Add directory ###
Specify an array of directories

    $ZIP = new SimpleZipArchive('test.zip');
    $ZIP->AddDir = array('dir1', 'dir2', 'dir3');
    $ZIP->SimpleZip();

### Add File ###

    $ZIP = new SimpleZipArchive('test.zip');
    $ZIP->AddFile = array(
            '/home/file.txt' => 'file.txt',
            '/home/img.jpg' => 'images.jpg',
            array(
                'logo.jpg' => file_get_contents('https://a248.e.akamai.net/assets.github.com/images/modules/about_page/github_logo.png'),
            ),
        );
    $ZIP->SimpleZip();
    
### Backing up the directory ###

    $ZIP = new SimpleZipArchive('test.zip');
    $ZIP->ZipDir = 'site';
    $ZIP->SimpleZip();
    
### Extract the entire archive ###

    $ZIP = new SimpleZipArchive('test.zip');
    $ZIP->Extract = true;
    $ZIP->To = '/home/';
    $ZIP->SimpleZip();

### Extract multiple files from the archive ###

    $ZIP = new SimpleZipArchive('test.zip');
    $ZIP->Extract = true;
    $ZIP->Entries = array('file1', 'file2', 'file3');
    $ZIP->To = '/home/';
    $ZIP->SimpleZip();
    
### Returns the contents of the file in the archive ###

    $ZIP = new SimpleZipArchive('test.zip');
    $ZIP->GetFrom = 'index.php';
    $content = $ZIP->SimpleZip();
    
### Delate File ###

    $ZIP = new SimpleZipArchive('test.zip');
    $ZIP->Delate = array('file.txt', 'images.jpg');
    $ZIP->SimpleZip();

### Add comments in archive ###

    $ZIP = new SimpleZipArchive('test.zip');
    $ZIP->Comment = 'this is a comment';
    $ZIP->SimpleZip();
    
### Detailed information about the file ###

    $ZIP = new SimpleZipArchive('test.zip');
    $ZIP->statName = 'index.php';
    var_dump($ZIP->SimpleZip());

### Rename file ###
    $ZIP = new SimpleZipArchive('test.zip');##
    $ZIP->ReName = array(
        'file.txt'=>'doc.txt',
        'images.jpg'=>'img.jpg'
        );
    $ZIP->SimpleZip();