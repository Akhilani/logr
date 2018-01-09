<?php
/**
 * Created by PhpStorm.
 * User: Akhil Kadangode
 * Date: 14-09-2017
 * Time: 19:08
 */

namespace akhilani\logr;

/**
 * Class Reader
 * @package akhilani\logr
 */
class Reader implements ReaderInterface
{
    /**
     * bufferSize
     * @var int
     */
    public $bufferSize = 4096;

    /**
     * *Reads file and returns lines of text based on request parameters*
     * Checks if file is valid, opens file and iterates through lines of code
     * and saves requested lines of code into an array
     * closes the file and return json formatted array
     * @param string $filename
     * @param int $start
     * @param int $count
     * @return string
     */
    public function getFileContent(string $filename, int $start, int $count):string
    {

        if (!$this->isValidFile($filename)){
            return 'Invalid or empty file. Please input a text based file.';
        }
        $handle = @fopen($filename, 'r');
        $logs = array();
        if ($handle) {
            $i = 0;
            while (($buffer = fgets($handle, $this->bufferSize)) !== false) {

                if ($i >= $start && $i < $start+$count){
                    array_push($logs, array("line" => $i+1, "text" => trim($buffer)));
                }
                $i++;
            }

            fclose($handle);

            return json_encode(array ("logs" => $logs, "totalLogs" => $i));
        }
    }

    /**
     * Checks if file is valid
     * checks if file exists, it is an actual file
     * check mimetype to ensure that it is a text based file "text/*"
     * @param string $filename
     * @return bool
     */
    public function isValidFile(string $filename):bool
    {
        if (file_exists($filename) && (filetype($filename) === 'file')){ //check if valid file
            $mimeType = explode('/', mime_content_type($filename));
            if ($mimeType[0] === 'text'){
                return true;
            }
        }
        return false;
    }

}