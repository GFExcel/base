<?php

namespace GFExcel\Tests\Container;

use GFExcel\Container\ContainerAware;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerAwareTest extends TestCase
{
    /**
     * Trait under test.
     * @since $ver$
     * @var ContainerAware
     */
    private $trait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trait = new class {
            use ContainerAware;
        };
    }

    /**
     * Test case for {@see ContainerAware::setContainer} and {@see ContainerAware::getContaienr}.
     * @since $ver$
     */
    public function testContainer()
    {
        $container = $this->createMock(ContainerInterface::class);
        $this->assertNull($this->trait->setContainer($container));
        $this->assertEquals($container, $this->trait->getContainer());
    }
}
