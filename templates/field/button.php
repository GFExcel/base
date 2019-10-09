<?php
/**
 * Button template that renders a button.
 * @var \GFExcel\AddOn\AbstractGFExcelAddon $this
 */
echo sprintf(
    '<button type="submit" name="%s" value="%s" %s>%s</button>',
    esc_attr($name ?? 'gfexcel-action'),
    esc_attr($value ?? ''),
    implode(' ', $attributes),
    esc_attr($label)
);
