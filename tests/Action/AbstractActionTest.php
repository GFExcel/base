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
        $this->assertEquals('concrete', (new ConcreteAction())->getName());
    }
}

class ConcreteAction extends AbstractAction
{
    /**
     * The name the action must have.
     * @since $ver$
     * @var string
     */
    protected static $name = 'concrete';

    /**
     * {@inheritdoc}
     */
    public function fire(AbstractGFExcelAddon $addon, array $form): void
    {
        // empty by design.
    }
}
