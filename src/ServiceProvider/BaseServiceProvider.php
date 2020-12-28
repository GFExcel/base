<?php

namespace GFExcel\ServiceProvider;

use GFExcel\Action\ActionAwareInterface;
use GFExcel\Action\ActionInterface;
use GFExcel\Template\TemplateAwareInterface;
use GFExcel\Repository\FormRepository;
use GFExcel\Repository\FormRepositoryInterface;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

class BaseServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
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
    public function register(): void
    {
        $container = $this->getLeagueContainer();

        $container->add(
            FormRepositoryInterface::class,
            FormRepository::class
        )->addArgument(\GFAPI::class);
    }

    /**
     * Retrieve all tagged actions from the container.
     * @since $ver$
     * @return ActionInterface[] The actions.
     */
    protected function getActions(): array
    {
        $container = $this->getLeagueContainer();
        if (!$container->has(ActionAwareInterface::ACTION_TAG)) {
            return [];
        }

        return $container->get(ActionAwareInterface::ACTION_TAG);
    }

    /**
     * @inheritdoc
     * @since $ver$
     */
    public function boot(): void
    {
        $container = $this->getLeagueContainer();

        $container
            ->inflector(ActionAwareInterface::class)
            ->invokeMethod('setActions', [$this->getActions()]);

        $container
            ->inflector(TemplateAwareInterface::class)
            ->invokeMethod('addTemplateFolder', [dirname(__FILE__, 3) . '/templates/']);
    }
}
