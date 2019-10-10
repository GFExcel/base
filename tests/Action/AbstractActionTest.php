<?php

namespace GFExcel\Tests\Action;

use GFExcel\Action\AbstractAction;
use GFExcel\AddOn\AbstractGFExcelAddon;
use PHPUnit\Framework\TestCase;

class AbstractActionTest extends TestCase
{
    /**
     * Test case for {@see AbstractAction::getName}.
     * @since $ver$
     */
    public function testGetName()
    {
        $this->assertEquals('concrete', (new ConcreteAction('concrete'))->getName());
    }

    /**
     * Test case for {@see AbstractAction::getName} with a missing name.
     * @since $ver$
     */
    public function testGetNameWithException()
    {
        $this->expectExceptionMessage(sprintf('Action "%s" should implement a $name variable.', ConcreteAction::class));
        $this->assertEquals('concrete', (new ConcreteAction(''))->getName());
    }
}

class ConcreteAction extends AbstractAction
{
    /**
     * Helper constructor to set the name of the action.
     * @param string $name
     */
    public function __construct(string $name)
    {
        self::$name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function fire(AbstractGFExcelAddon $addon, array $form): void
    {
        // empty by design.
    }
}
