<?php

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class ReaderTest extends TestCase
{
    protected $fileSystem;
    protected function setUp()
    {
        $directory = [
            'logs' => [
                'empty.log' => '',
                'proper.log' => "line 1\nline 2\nline 3\nline 4"
            ]
        ];
        // setup virtual file system
        $this->fileSystem = vfsStream::setup('root', 444, $directory);
    }

    public function testValidFile()
    {
        $reader = new \akhilani\logr\Reader();
        $validFile =  $reader->isValidFile($this->fileSystem->url().'/logs/proper.log');
        $this->assertTrue($validFile);
    }

    public function testInvalidFile()
    {
        $reader = new \akhilani\logr\Reader();
        $invalidFile =  $reader->isValidFile($this->fileSystem->url().'/logs/invalid-file.log');
        $this->assertFalse($invalidFile);
    }

    public function testValidFileReading()
    {
        $reader = new \akhilani\logr\Reader();
        $validFileContent =  $reader->getFileContent($this->fileSystem->url().'/logs/proper.log', 0, 10);
        $jsonArray = json_decode($validFileContent);
        $this->assertEquals('line 1', $jsonArray->logs[0]->text);
        $this->assertEquals('line 2', $jsonArray->logs[1]->text);
        $this->assertEquals('line 3', $jsonArray->logs[2]->text);
        $this->assertEquals('line 4', $jsonArray->logs[3]->text);
        $this->assertEquals(1, $jsonArray->logs[0]->line);
        $this->assertEquals(2, $jsonArray->logs[1]->line);
        $this->assertEquals(3, $jsonArray->logs[2]->line);
        $this->assertEquals(4, $jsonArray->logs[3]->line);
        $this->assertEquals(4, $jsonArray->totalLogs);
    }

    public function testEmptyFileReading()
    {
        $reader = new \akhilani\logr\Reader();
        $emptyFileContent =  $reader->getFileContent($this->fileSystem->url().'/logs/empty.log', 0, 10);
        $this->assertEquals('Invalid or empty file. Please input a text based file.', $emptyFileContent);
    }

    public function testInvalidFileReading()
    {
        $reader = new \akhilani\logr\Reader();
        $invalidFileContent = $reader->getFileContent($this->fileSystem->url() . '/logs/invalid-file.log', 0, 10);
        $this->assertEquals('Invalid or empty file. Please input a text based file.', $invalidFileContent);
        $invalidFileContent2 = $reader->getFileContent($this->fileSystem->url() . '/logs/', 0, 10);
        $this->assertEquals('Invalid or empty file. Please input a text based file.', $invalidFileContent2);
    }

}