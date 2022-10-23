<?php

namespace Data\Readers;

/**
 * This class reads a given csv file that exists into data folder
 * of the project with generators.
 */
class CsvFileReader
{
    /**
     * Object returned by fopen function to manage the file
     * to read.
     */
    protected $fileManager = null;

    /**
     * Determines up to how many lines to return from the
     * read file.
     *
     * @var string
     */
    protected $linesPerIteration = null;

    /**
     * Determines the separator character of values/colums
     * in the csv file.
     *
     * @var string
     */
    protected $fileSeparator = null;

    /**
     * Stores the headers of the CSV.
     *
     * @var array<string>
     */
    protected $headers = null;

    /**
     * Creates an csv file reader to get given quantity of rows
     * for each generator. The file will be opened with fopen function
     * so any error that exists for that function also will be throws
     * by this class.
     *
     * @param  string  $fileName Given file needs to be into "data" directory of the project.
     * @param  string  $fileSeparator Sets the separator character of the given csv.
     * @param  int  $linesPerIteration Sets the maximum size of rows returned by each iteration of the reader.
     */
    public function __construct(
        string $fileName,
        string $fileSeparator = '|',
        int $linesPerIteration = 1000)
    {
        $this->fileSeparator = $fileSeparator;
        $this->linesPerIteration = $linesPerIteration;

        $fileRoute = dirname(__FILE__).'/../'.$fileName;
        $this->fileManager = fopen($fileRoute, 'r');
    }

    /**
     * This function returns a generator to get one row of the file
     * once by once.
     *
     * @return generator
     */
    protected function getFileGeneratorPerRow()
    {
        while ($data = fgetcsv(stream: $this->fileManager, separator: $this->fileSeparator)) {
            if (is_null($this->headers)) {
                $this->headers = $data;

                continue;
            }
            yield $data;
        }
    }

    /**
     * This function transform given common array with csv row data
     * to an associative array with keys as csv headers.
     *
     * @param  array  $data Array row of the csv.
     * @return array Associative array generated.
     */
    protected function parseDataArrayToHeadersAssociativeArray(array $data)
    {
        $associativeArray = [];
        for ($i = 0; $i < count($data); $i++) {
            $associativeArray[$this->headers[$i]] = $data[$i];
        }

        return $associativeArray;
    }

    /**
     * Gets an iterator that returns an array of rows of the csv file to read.
     * The array returned will be associative array with keys as csv headers
     * and its length will be at least the given lines per iteration
     * in the class constructor.
     *
     * @return generator<array>
     */
    public function getRows()
    {
        $allLinesGenerator = $this->getFileGeneratorPerRow();
        while ($allLinesGenerator->valid()) {
            $linesForIteration = [];
            foreach (range(1, $this->linesPerIteration) as $iteration) {
                if (! $allLinesGenerator->valid()) {
                    break;
                }
                $currentLine = $allLinesGenerator->current();
                $currentLineFormatted = $this->parseDataArrayToHeadersAssociativeArray($currentLine);
                array_push($linesForIteration, $currentLineFormatted);
                $allLinesGenerator->next();
            }
            $thereAreLines = $allLinesGenerator->valid();
            if ($thereAreLines) {
                yield $linesForIteration;
            }
        }
    }

    /**
     * Close the reader of the file.
     * This function is equals to fclose php function.
     *
     * @return void
     */
    public function finishReader()
    {
        fclose($this->fileManager);
    }
}
