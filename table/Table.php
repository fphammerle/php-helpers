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
     * @return string
     */
    public function toCSV($delimiter = ',')
    {
        $columns_number = $this->columnsCount;
        $empty_row_csv = (new Row)->toCSV($delimiter, $columns_number);
        $rows_csv = [];
        $rows_number = sizeof($this->_rows) > 0 ? max(array_keys($this->_rows)) + 1 : 0;
        for($row_index = 0; $row_index < $rows_number; $row_index++) {
            $rows_csv[] = isset($this->_rows[$row_index])
                ? $this->_rows[$row_index]->toCSV($delimiter, $columns_number)
                : $empty_row_csv;
        }
        return implode('', $rows_csv);
    }
}
