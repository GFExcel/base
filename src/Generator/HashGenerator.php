<?php

namespace GFExcel\Generator;

use GFExcel\Contract\HashGeneratorInterface;

class HashGenerator implements HashGeneratorInterface
{
    /**
     * {@inheritdoc}
     * @since $ver$
     * @throws \Exception When it was not possible to gather sufficient entropy.
     */
    public function generate(): string
    {
        return bin2hex(random_bytes(16));
    }
}
