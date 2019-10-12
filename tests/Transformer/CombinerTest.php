<?php

namespace GFExcel\Tests\Transformer;

use GFExcel\Transformer\Combiner;
use GFExcel\Transformer\FieldInterface;
use PHPUnit\Framework\TestCase;

class CombinerTest extends TestCase
{
    /**
     * Test case for {@see Combiner::getColumns}.
     * @since $ver$
     */
    public function testGetColumns()
    {
        $field1 = new ConcreteField(['A'], [['1']]);
        $field2 = new ConcreteField(['B', 'C'], [['2', '3']]);

        $this->assertEquals(['A', 'B', 'C'], (new Combiner([$field1, $field2]))->getColumns());
    }

    /**
     * Data provider for {@see self::testGetRows}.
     * @since $ver$
     * @return mixed[] The provided data.
     */
    public function dataProviderForTestGetRows(): array
    {
        return [
            'normal' => [
                [
                    new ConcreteField(['A'], [['1']]),
                    new ConcreteField(['B'], [['2']]),
                ],
                [
                    ['1', '2'],
                ],
            ],
            'multiple' => [
                [
                    new ConcreteField(['A'], [['1']]),
                    new ConcreteField(['B'], [['2'], ['3']]),
                ],
                [
                    ['1', '2'],
                    [null, 3],
                ],
            ],
            'multiple_with_outfill' => [
                [
                    new ConcreteField(['A'], [['1'], ['3']]),
                    new ConcreteField(['B'], [['2']]),
                ],
                [
                    ['1', '2'],
                    [3, null],
                ],
            ],
            'ultimate_test' => [
                [
                    new ConcreteField(['A'], [['1'], ['3']]),
                    new ConcreteField(['B'], [['2']]),
                    new ConcreteField(['C'], [['4'], ['6'], ['7'], ['8']]),
                    new ConcreteField(['D'], [[], ['9']]),
                    new ConcreteField(['E', 'F'], [['5', '10']]),
                ],
                [
                    ['1', '2', '4', null, '5', '10'],
                    ['3', null, '6', '9', null, null],
                    [null, null, '7', null, null, null],
                    [null, null, '8', null, null, null],
                ],
            ]
        ];
    }

    /**
     * Test case for {@see Combiner::getRows}.
     * @since $ver$
     * @param FieldInterface[] $fields The array of fields to combine.
     * @param array $expected The expected array output.
     * @dataProvider dataProviderForTestGetRows The data provider.
     */
    public function testGetRows(array $fields, array $expected)
    {
        $this->assertEquals($expected, (new Combiner($fields))->getRows());
    }
}

class ConcreteField implements FieldInterface
{
    /**
     * @since $ver$
     * @var array
     */
    private $columns;
    /**
     * @since $ver$
     * @var array
     */
    private $rows;

    public function __construct(array $columns, array $rows)
    {
        $this->columns = $columns;
        $this->rows = $rows;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getRows(): array
    {
        return $this->rows;
    }
}
