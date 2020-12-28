<?php

namespace GFExcel\Action;

use GFExcel\Addon\AddonInterface;

interface ActionInterface
{
    /**
     * Should return a unique name for the action.
     * @since $ver$
     * @return string The name.
     */
    public function getName(): string;

    /**
     * Performs the action.
     * @since $ver$
     * @param AddonInterface $addon The Add on instance.
     * @param array $form The form object.
     */
    public function fire(AddonInterface $addon, array $form): void;
}
