<?php

namespace GFExcel\Tests\Transformer;

use GFExcel\Transformer\Combiner;
use GFExcel\Transformer\FieldInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test case for {@see Combiner}.
 * @since $ver$
 */
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
            'multiple_pre_fill' => [
                [
                    new ConcreteField(['A'], [['1']]),
                    new ConcreteField(['B'], [['2'], ['3']]),
                ],
                [
                    ['1', '2'],
                    [null, 3],
                ],
            ],
            'multiple_post_fill' => [
                [
                    new ConcreteField(['A'], [['1'], ['3']]),
                    new ConcreteField(['B'], [['2']]),
                ],
                [
                    ['1', '2'],
                    [3, null],
                ],
            ],
            'multiple_pre_post_fill' => [
                [
                    new ConcreteField(['A'], [['1'], ['2']]),
                    new ConcreteField(['B'], [['3']]),
                    new ConcreteField(['C'], [['4'], ['5'], ['6']]),
                    new ConcreteField(['D'], [['7']]),
                    new ConcreteField(['E', 'F'], [['8', '9'], ['10', '11']]),
                ],
                [
                    //A   B    C    D     E     F
                    ['1', '3', '4', '7', '8', '9'],
                    ['2', null, '5', null, '10', '11'],
                    [null, null, '6', null, null, null],
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
        $this->assertEquals($expected, iterator_to_array((new Combiner($fields))->getRows()));
    }

    /**
     * Test case for {@see Combiner::getRows} with a combiner being combined.
     *
     * Since a combiner is a {@see FieldInterface} instance, it should be able to combine a combiner.
     *
     * @since $ver$
     */
    public function testGetRowsWithCombinerField()
    {
        $field1 = new ConcreteField(['A'], [['1']]);
        $field2 = new ConcreteField(['B', 'C'], [['2', '3']]);
        $combiner_fields = new Combiner([$field1, $field2]);
        $combiner_combiner = new Combiner([$combiner_fields]);

        $this->assertEquals([['1','2','3']], iterator_to_array($combiner_combiner->getRows()));
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

    public function getRows(): iterable
    {
        // Yielding results to make sure the combiner can take generators.
        foreach ($this->rows as $row) {
            yield $row;
        }
    }
}
