<?php
namespace GFExcel\Transformer;

interface FieldInterface
{
    public function getColumns(): array;

    public function getRows(): array;
}
