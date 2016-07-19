<?php

namespace fphammerle\helpers\table;

use \fphammerle\helpers\StringHelper;

class Row
{
    use \fphammerle\helpers\PropertyAccessTrait;

    private $_cells = [];

    /**
     * @param array<mixed> $cell_values
     */
    public function __construct($cell_values = [])
    {
        foreach($cell_values as $column_index => $cell_value) {
            $this->setCellValue($column_index, $cell_value);
        }
    }

    /**
     * @throws InvalidArgumentException
     * @param integer $column_index
     * @return Cell
     */
    public function getCell($column_index)
    {
        if(!is_int($column_index) || $column_index < 0) {
            throw new \InvalidArgumentException('column index must be an integer >= 0');
        }

        if(!isset($this->_cells[$column_index])) {
            $this->_cells[$column_index] = new Cell;
        }

        return $this->_cells[$column_index];
    }

    /**
     * @param integer $column_index
     * @param mixed $value
     */
    public function setCellValue($column_index, $value)
    {
        $this->getCell($column_index)->value = $value;
    }

    /**
     * @return integer
     */
    public function getColumnsCount()
    {
        return sizeof($this->_cells) > 0
            ? max(array_keys($this->_cells)) + 1
            : 0;
    }

    /**
     * @return string
     */
    public function toCSV($delimiter = ',', $columns_number = null)
    {
        if(empty($delimiter)) {
            throw new \InvalidArgumentException('empty delimiter');
        }

        if($columns_number === null) {
            $columns_number = $this->columnsCount;
        }

        $_empty_cell_csv = (new Cell)->toCSV($delimiter);
        $_cells_csv = [];
        for($column_index = 0; $column_index < $columns_number; $column_index++) {
            $_cells_csv[] = isset($this->_cells[$column_index])
                ? $this->_cells[$column_index]->toCSV($delimiter)
                : $_empty_cell_csv;
        }
        return implode($delimiter, $_cells_csv) . "\r\n";
    }
}
