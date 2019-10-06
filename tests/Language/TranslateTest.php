<?php

namespace GFExcel\Tests\Language;

use GFExcel\Language\Translate;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

class TranslateTest extends TestCase
{
    use PHPMock;

    /**
     * Test case for {@see Translate::translate}.
     * @since $ver$
     */
    public function testTranslate()
    {
        $translate = $this->getFunctionMock('GFExcel\Language', '__');
        $translate->expects($this->exactly(2))->with('foo')->willReturn('bar');

        $esc_html = $this->getFunctionMock('GFExcel\Language', 'esc_html');
        $esc_html->expects($this->once())->with('bar')->willReturn('html_safe');

        $this->assertEquals('bar', (new TestTranslator())->translate('foo'));
        $this->assertEquals('html_safe', (new TestTranslator())->translate('foo', true));
    }
}

class TestTranslator
{
    use Translate;
}
