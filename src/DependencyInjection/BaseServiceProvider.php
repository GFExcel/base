<?php

namespace GFExcel\DependencyInjection;

use GFExcel\Action\ActionAwareInteface;
use GFExcel\Action\ActionInterface;
use GFExcel\Contract\TemplateAwareInterface;
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
        )->addArgument(\GFAPI::class);

        $container
            ->inflector(ActionAwareInteface::class)
            ->invokeMethod('setActions', [$this->getActions()]);

        $container
            ->inflector(TemplateAwareInterface::class)
            ->invokeMethod('addTemplateFolder', [dirname(dirname(dirname(__FILE__))) . '/templates/']);
    }

    /**
     * Retrieve all tagged actions from the container.
     * @since $ver$
     * @return ActionInterface[]
     */
    protected function getActions(): array
    {
        $container = $this->getLeagueContainer();
        if (!$container->has(ActionAwareInteface::ACTION_TAG)) {
            return [];
        }

        return $container->get(ActionAwareInteface::ACTION_TAG);
    }
}
