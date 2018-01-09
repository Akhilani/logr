<?php
/**
 * Created by PhpStorm.
 * User: Akhil Kadangode
 * Date: 14-09-2017
 * Time: 19:12
 */

namespace akhilani\logr;

/**
 * Interface ReaderInterface
 * @package akhilani\logr
 */
interface ReaderInterface
{

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
    public function getFileContent(string $filename, int $start, int $count);

    /**
     * Checks if file is valid
     * checks if file exists, it is an actual file
     * and then checks if it is text based file (mimetype is text/*)
     * @param string $filename
     * @return bool
     */
    public function isValidFile(string $filename);

}