<?php
require_once 'vendor/autoload.php';

$reader = new \akhilani\logr\Reader();
try{
    echo $reader->getFileContent($_GET['path'], $_GET['start'], $_GET['count']);
} catch (Error $e){
    echo $e->getMessage();
}


