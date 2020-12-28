<?php
namespace GFExcel\Transformer;

/**
 * Field interface every field should adhere to.
 * @since $ver$
 */
interface FieldInterface
{
    /**
     * Should return the name of every column this field produces.
     * @since $ver$
     * @return string[] The column name.
     */
    public function getColumns(): array;

    /**
     * Should return the rows this field needs to render all cells.
     * @since $ver$
     * @return iterable|\Generator|mixed[] Row containing cells.
     */
    public function getRows(): iterable;
}
