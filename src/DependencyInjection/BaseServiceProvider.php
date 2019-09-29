<?php

namespace GFExcel\DependencyInjection;

use GFExcel\Repository\FormRepository;
use GFExcel\Repository\FormRepositoryInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class BaseServiceProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     * @since $ver$
     */
    protected $provides = [
        FormRepositoryInterface::class,
    ];

    /**
     * {@inheritdoc}
     * @since $ver$
     */
    public function register()
    {
        $container = $this->getLeagueContainer();

        $container->add(
            FormRepositoryInterface::class,
            FormRepository::class
        )->addArgument(new \GFAPI());
    }
}
