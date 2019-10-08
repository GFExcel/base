<?php

namespace GFExcel\Action;

use GFExcel\AddOn\AbstractGFExcelAddon;

interface ActionInterface
{
    /**
     * Should return a unique name for the action.
     * @since $ver$
     * @return string The name.
     */
    public static function getName(): string;

    /**
     * Proforms the action.
     * @since $ver$
     * @param AbstractGFExcelAddon $addon The AddOn instance.
     * @param array $form The form object.
     */
    public function fire(AbstractGFExcelAddon $addon, array $form): void;
}
