<?php


namespace Paysera\CommissionTask\Service\Reader;


use Exception;

class CSVReader
{
    public $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function getSource()
    {
        if (!isset($this->source)) {
            throw new Exception("File does not exist");
        }

        return $this->source;
    }

    public function dataArray()
    {
        $result = [];

        try {
            if ($file = fopen($this->getSource(), "r")) {
                while (!feof($file)) {
                    $line     = fgets($file);
                    $row      = explode(',', $line);
                    $result[] = $row;
                }
                fclose($file);

                return $result;
            } else {
                echo "File does not exist \n";
            }
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}