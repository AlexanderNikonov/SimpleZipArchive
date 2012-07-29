<?php

/**
 * @author Alexander Nikonov
 * @version 0.1
 * @filesource https://github.com/AlexanderNikonov/SimpleZipArchive
 */
class SimpleZipArchive {

    /**
     * The name of the archive
     * @var string 
     * @example $ZIP->ArchiveName = 'archive.zip';
     */
    var $ArchiveName = false;

    /**
     * In which directory is the extraction
     * @var string
     * @example $ZIP->To = '/archives';
     */
    var $To = false;

    /**
     * If true, extract the archive into the directory &$To
     * @var true/false
     * @example $ZIP->Extract = true;
     */
    var $Extract = false;

    /**
     * A list of recoverable files
     * @var array/or/false 
     * @example $ZIP->Entries = array('readme.txt', 'image.jpg'); 
     */
    var $Entries = false;

    /**
     * Comment to the archive
     * @var string 
     * @example $ZIP->Comment = 'This is a test archive';
     */
    var $Comment = false;

    /**
     * Script Charset
     * @var string
     * @example $ZIP->Charset =  'UTF-8';
     */
    var $Charset = 'UTF-8';

    /**
     * List of files to be added
     * @var array
     * @example 
     * $ZIP->AddFile = array(
     *  '/home/file.txt' => 'file.txt', 
     *  '/home/img.jpg' => 'images.jpg', 
     *  array(
     *      'logo.jpg' => file_get_contents('https://a248.e.akamai.net/assets.github.com/images/modules/about_page/github_logo.png'),
     *  ),
     * );
     */
    var $AddFile = false;

    /**
     * List of dir to be added
     * @var array 
     * @example 
     * $ZIP->AddDir = array('test', 'test/test2');
     */
    var $AddDir = false;

    /**
     * If you want to archive a directory recursively
     * @var string 
     * @example $ZIP->ZipDir = '/home/data/site.ru';
     */
    var $ZipDir = false;

    /**
     * Returns the contents of the file in the archive
     * @var string
     * @example 
     * $ZIP->GetFrom = 'index.php';
     * $content = $ZIP->SimpleZip();
     */
    var $GetFrom = false;

    /**
     * Returns the contents of the element in the success or FALSE on failure.
     * @var array
     * @example $ZIP->Delate = array('file.txt', 'images.jpg');
     */
    var $Delate = false;

    /**
     * List rename files
     * @var array
     * @example $ZIP->ReName = array('file.txt'=>'doc.txt','images.jpg'=>'img.jpg');
     */
    var $ReName = false;

    /**
     * Returns an array containing detailed information on the item or, or FALSE on failure.
     * @var string
     * @example
     * $ZIP->statName = 'index.php';
     * var_dump($ZIP->SimpleZip());
     */
    var $statName = false;

    /**
     * 
     * @param string $ArchiveName
     * @param string $To
     */
    function SimpleZipArchive($ArchiveName, $To = '') {
        $this->ArchiveName = $ArchiveName;
        $this->To = $To;
    }

    /**
     * 
     * @return true or false
     */
    function SimpleZip() {
        $zip = new ZipArchive();
        if ($zip->open($this->ArchiveName, ZIPARCHIVE::CREATE) !== true) {
            return false;
        }
        if ($this->Extract)
            $zip = $this->Extract($zip);
        if ($this->ZipDir)
            $zip = $this->ZipDirectory($zip);
        if ($this->Comment)
            $zip = $this->AddComment($zip);
        if ($this->AddDir)
            $zip = $this->AddDir($zip);
        if ($this->AddFile)
            $zip = $this->AddFile($zip);
        if ($this->Delate)
            $zip = $this->DelateName($zip);
        if ($this->ReName)
            $zip = $this->ReName($zip);
        if ($this->GetFrom)
            return $zip->getFromName($this->GetFrom);
        if ($this->statName)
            return $zip->statName($this->statName);



        $zip->close();
        return;
    }

    /**
     * 
     * @param object $zip
     * @return object
     */
    private function AddDir($zip) {
        foreach ($this->AddDir as $value) {
            $value = iconv($this->Charset, 'CP866', $value);
            $zip->addEmptyDir($value);
        }
        return $zip;
    }

    /**
     * 
     * @param array $zip
     * @return object
     */
    private function AddFile($zip) {
        foreach ($this->AddFile as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $StringName => $StringValue) {
                    if ($this->utf8) {
                        $StringName = iconv($this->Charset, 'CP866', $StringName);
                        $StringValue = iconv($this->Charset, 'CP866', $StringValue);
                    }
                    $zip->addFromString($StringName, $StringValue);
                }
            }
            else {
                $key = iconv($this->Charset, 'CP866', $key);
                $value = iconv($this->Charset, 'CP866', $value);
                $zip->addFile($key, $value);
            }
        }
        return $zip;
    }

    /**
     * 
     * @param object $zip
     * @return object
     */
    private function ZipDirectory($zip) {

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->ZipDir . "/"));
        foreach ($iterator as $key => $value) {
            if ($key == '..' or $key == '.')
                continue;
            $key = str_replace(DIRECTORY_SEPARATOR . "..", '/', $key);
            $zip->addFile(realpath($key), iconv($this->Charset, 'CP866', $key));
        }
        return $zip;
    }

    /**
     * 
     * @param object $zip
     * @return object
     */
    private function Extract($zip) {
        if ($this->Entries)
            $zip->extractTo($this->To, $this->Entries);
        else
            $zip->extractTo($this->To);
        return $zip;
    }

    /**
     * 
     * @param object $zip
     * @return object
     */
    private function DelateName($zip) {
        if (is_array($this->Delate)) {
            foreach ($this->Delate as $value) {
                if ($this->utf8)
                    $value = iconv($this->Charset, 'CP866', $value);
                $zip->deleteName($value);
            }
        }

        return $zip;
    }

    /**
     * 
     * @param string $zip
     * @return object
     */
    private function AddComment($zip) {

        $zip->setArchiveComment(iconv($this->Charset, 'windows-1251', $this->Comment));

        return $zip;
    }

    /**
     * 
     * @param object $zip
     * @return object
     */
    private function ReName($zip) {
        if (is_array($this->ReName)) {
            foreach ($this->ReName as $key => $value) {

                $key = iconv($this->Charset, 'CP866', $key);
                $value = iconv($this->Charset, 'CP866', $value);
                $zip->renameName($key, $value);
            }
        }
        return $zip;
    }

}