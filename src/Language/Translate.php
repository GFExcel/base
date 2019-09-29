<?php

namespace GFExcel\Language;

use GFExcel\GFExcel;

trait Translate
{
    /**
     * Translate the given text.
     * @since $ver$
     * @param string $text The text to translate.
     * @param bool $is_html_save Wheteher the string should be save for HTML usage.
     * @return string The translated text.
     */
    public function translate(string $text, bool $is_html_save = false): string
    {
        $translation = __($text, GFExcel::SLUG);

        return $is_html_save ? esc_html($translation) : $translation;
    }
}