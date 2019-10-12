<?php

namespace GFExcel\Transformer;

class Combiner implements FieldInterface
{
    /**
     * Holds the columns provided by the field.
     * @since $ver$
     * @var string[] The column names.
     */
    private $columns = [];

    /**
     * Holds an array of arrays that contains the cells data for every row.
     * @since $ver$
     * @var mixed[][] The rows with the cell values.
     */
    private $rows = [];

    /**
     * Combines the columns and rows for the provided fields into the correct arrays.
     * @param FieldInterface[] $fields The fields to combine.
     */
    public function __construct(array $fields)
    {
        $c = 0;
        foreach ($fields as $field) {
            $this->columns = array_merge($this->columns, $field->getColumns());

            $fc = count($field->getColumns());

            foreach ($field->getRows() as $i => $row) {
                if (!isset($this->rows[$i])) {
                    $this->rows[$i] = [];
                }
                if (count($this->rows[$i]) < $c) {
                    for ($x = count($this->rows[$i]); $x < $c; $x++) {
                        $this->rows[$i][] = null;
                    }
                }
                $this->rows[$i] = array_merge($this->rows[$i], $row);
            }

            $c += $fc;
        }

        foreach ($this->rows as $i => $row) {
            $columns = count($row);
            if ($columns < $c) {
                for ($x = $columns; $x < $c; $x++) {
                    $this->rows[$i][] = null;
                }
            }
        }

    }

    /**
     * Returns the combined column names for every field.
     * @since $ver$
     * @return string[] The column names.
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Returns the rows, containing all the cells, for every field.
     * @since $ver$
     * @return mixed[][] The cell data.
     */
    public function getRows(): array
    {
        return $this->rows;
    }
}
