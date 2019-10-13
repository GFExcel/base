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
        // always start at zero.
        $column_index = 0;

        foreach ($fields as $field) {
            // Add columns to the internal array.
            $this->columns = array_merge($this->columns, $field->getColumns());
            $field_column_count = count($field->getColumns());

            // Iterate every row a field returns.
            $i = 0;
            foreach ($field->getRows() as $row) {
                // Initialize row if it doesn't exist yet.
                if (!isset($this->rows[$i])) {
                    $this->rows[$i] = [];
                }

                $this->fillMissingColumns($i, $column_index);
                // Merge values with the current row.
                $this->rows[$i] = array_merge($this->rows[$i], $row);
                $i++;
            }

            // Keep track of the current column count.
            $column_index += $field_column_count;
        }

        // Now that we have all rows and data, fill out the remaining missing cells.
        foreach ($this->rows as $i => $row) {
            $this->fillMissingColumns($i, $column_index);
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
     * {@inheritdoc}
     * Yields the rows, containing all the cells, for every field.
     * @since $ver$
     * @return iterable|\Generator|mixed[]
     */
    public function getRows(): iterable
    {
        foreach ($this->rows as $row) {
            yield $row;
        }
    }

    /**
     * Fills out any missing cells up to this point with `null`.
     * @since $ver$
     * @param int $row The row id to fill out.
     * @param int $total The total count of fields for the row.
     */
    private function fillMissingColumns(int $row, int $total): void
    {
        $length = count($this->rows[$row]);
        if ($length < $total) {
            for ($x = $length; $x < $total; $x++) {
                $this->rows[$row][] = null;
            }
        }
    }
}
