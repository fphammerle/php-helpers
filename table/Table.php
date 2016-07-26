<?php

namespace fphammerle\helpers\table;

class Table
{
    use \fphammerle\helpers\PropertyAccessTrait;

    private $_rows = [];

    /**
     * @param array<array<mixed>> $cell_values
     */
    public function __construct($cell_values = [])
    {
        foreach($cell_values as $row_index => $row_values) {
            $this->setRow($row_index, new Row($row_values));
        }
    }

    /**
     * @throws InvalidArgumentException
     * @param integer $row_index
     * @return Row
     */
    public function getRow($row_index)
    {
        if(!is_int($row_index) || $row_index < 0) {
            throw new \InvalidArgumentException(
                sprintf('row index must be an integer >= 0, %s given', print_r($row_index, true))
                );
        }

        if(!isset($this->_rows[$row_index])) {
            $this->_rows[$row_index] = new Row;
        }

        return $this->_rows[$row_index];
    }

    /**
     * @param Row $row
     */
    public function appendRow(Row $row)
    {
        $this->_rows[] = $row;
    }

    /**
     * @throws InvalidArgumentException
     * @param integer $row_index
     * @param Row $row
     */
    public function setRow($row_index, Row $row)
    {
        if(!is_int($row_index) || $row_index < 0) {
            throw new \InvalidArgumentException(
                sprintf('row index must be an integer >= 0, %s given', print_r($row_index, true))
                );
        }

        $this->_rows[$row_index] = $row;
    }

    /**
     * @param integer $row_index
     * @param integer $column_index
     * @return Cell
     */
    public function getCell($row_index, $column_index)
    {
        return $this->getRow($row_index)->getCell($column_index);
    }

    /**
     * @param integer $column_index
     * @param mixed $value
     */
    public function setCellValue($row_index, $column_index, $value)
    {
        $this->getCell($row_index, $column_index)->value = $value;
    }

    /**
     * @return integer
     */
    public function getColumnsCount()
    {
        return sizeof($this->_rows) > 0
            ? max(array_map(function($r) { return $r->columnsCount; }, $this->_rows))
            : 0;
    }

    /**
     * @return integer
     */
    public function getRowsCount()
    {
        return sizeof($this->_rows) > 0
            ? max(array_keys($this->_rows)) + 1
            : 0;
    }

    /**
     * @return string
     */
    public function toCSV($delimiter = ',')
    {
        $columns_number = $this->columnsCount;
        $empty_row_csv = (new Row)->toCSV($delimiter, $columns_number);
        $rows_csv = [];
        $rows_number = $this->rowsCount;
        for($row_index = 0; $row_index < $rows_number; $row_index++) {
            $rows_csv[] = isset($this->_rows[$row_index])
                ? $this->_rows[$row_index]->toCSV($delimiter, $columns_number)
                : $empty_row_csv;
        }
        return implode('', $rows_csv);
    }

    /**
     * @return string
     */
    public function toText()
    {
        $rows_number = $this->rowsCount;
        $cols_number = $this->columnsCount;
        if($rows_number == 0) {
            return '';
        } elseif($cols_number == 0) {
            return str_repeat("\n", $rows_number);
        }
        $cols_max_length = [];
        $string_table = [];
        for($col_index = 0; $col_index < $cols_number; $col_index++) {
            $cols_max_length[$col_index] = 0;
            for($row_index = 0; $row_index < $rows_number; $row_index++) {
                $cell_value = $this->getCell($row_index, $col_index)->value;
                if($cell_value === false) {
                    $cell_value_lines = ['0'];
                } else {
                    $cell_value_lines = explode("\n", (string)$cell_value);
                    assert(sizeof($cell_value_lines) > 0);
                }
                $string_table[$row_index][$col_index] = $cell_value_lines;
                $cols_max_length[$col_index] = max(
                    max(array_map(
                        function($line) { return strlen($line); },
                        $cell_value_lines
                        )),
                    $cols_max_length[$col_index]
                    );
            }
        }
        return implode("\n", array_map(
            function($row) use ($cols_number, $cols_max_length) {
                $lines_number = max(array_map(function($c) { return sizeof($c); }, $row));
                $lines = [];
                for($line_index = 0; $line_index < $lines_number; $line_index++) {
                    $line = '';
                    for($col_index = 0; $col_index < $cols_number; $col_index++) {
                        $line .= str_pad(
                            isset($row[$col_index][$line_index])
                                ? $row[$col_index][$line_index]
                                : '',
                            $cols_max_length[$col_index]
                                + ($col_index + 1 == $cols_number ? 0 : 1),
                            ' ',
                            STR_PAD_RIGHT
                            );
                    }
                    $lines[] = $line;
                }
                return implode("\n", $lines);
                },
            $string_table
            )) . "\n";
    }

    /**
     * @param array<mixed> $rows associative array
     * @return Table
     */
    public static function fromAssociativeArray(array $rows)
    {
        $keys = [];
        foreach($rows as $row) {
            $keys = array_unique(array_merge($keys, array_keys($row)));
        }
        // array_unique preserves keys
        $keys = array_values($keys);

        $t = new self([$keys]);
        foreach($rows as $row) {
            $t->appendRow(new Row(array_map(
                function($key) use ($row) {
                    return array_key_exists($key, $row) ? $row[$key] : null;
                },
                $keys
                )));
        }
        return $t;
    }
}
