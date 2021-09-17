<?php


namespace Paysera\CommissionTask\Service\Reader;


use Exception;

class CSVReader
{
    public $source;

    /**
     * CSVReader constructor.
     * @param $source
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getSource()
    {
        if (!isset($this->source)) {
            throw new Exception("File does not exist");
        }

        return $this->source;
    }

    /**
     * @return array | Exception
     * @throws Exception
     */
    public function dataArray()
    {
        $result = [];

        try {
            if ($file = fopen($this->getSource(), "r")) {
                while (!feof($file)) {
                    $line     = fgets($file);
                    $row      = explode(',', trim($line));
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