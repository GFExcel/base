<?php

namespace GFExcel\DependencyInjection;

use GFExcel\Contract\ActionAwareInteface;
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


        $container
            ->inflector(ActionAwareInteface::class)
            ->invokeMethod('setActions', [$this->getActions()]);
    }

    protected function getActions(): array
    {
        $container = $this->getLeagueContainer();
        if (!$container->has(ActionAwareInteface::ACTION_TAG)) {
            return [];
        }

        return $container->get(ActionAwareInteface::ACTION_TAG);
    }
}
