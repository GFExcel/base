<?php

namespace GFExcel\Tests\Generator;

use GFExcel\Generator\HashGenerator;
use PHPUnit\Framework\TestCase;

class HashGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $hash = (new HashGenerator())->generate();
        $this->assertIsString($hash);
        $this->assertEquals(32, strlen($hash));
    }
}
