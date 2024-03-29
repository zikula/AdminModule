<?php

declare(strict_types=1);

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\AdminModule\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\AdminModule\Entity\AdminModuleEntity;
use Zikula\AdminModule\Entity\RepositoryInterface\AdminModuleRepositoryInterface;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\ExtensionsModule\Entity\RepositoryInterface\ExtensionRepositoryInterface;
use Zikula\ExtensionsModule\Event\ExtensionPostInstallEvent;

class ModuleEventListener implements EventSubscriberInterface
{
    /**
     * @var AdminModuleRepositoryInterface
     */
    protected $adminModuleRepository;

    /**
     * @var ExtensionRepositoryInterface
     */
    protected $extensionRepository;

    /**
     * @var VariableApiInterface
     */
    protected $variableApi;

    /**
     * @var bool
     */
    private $installed;

    public function __construct(
        AdminModuleRepositoryInterface $adminModuleRepository,
        ExtensionRepositoryInterface $extensionRepository,
        VariableApiInterface $variableApi,
        string $installed
    ) {
        $this->adminModuleRepository = $adminModuleRepository;
        $this->extensionRepository = $extensionRepository;
        $this->variableApi = $variableApi;
        $this->installed = '0.0.0' !== $installed;
    }

    public static function getSubscribedEvents()
    {
        return [
            ExtensionPostInstallEvent::class => ['moduleInstall']
        ];
    }

    /**
     * Handle module install event.
     */
    public function moduleInstall(ExtensionPostInstallEvent $event): void
    {
        if (!$this->installed) {
            return;
        }
        $extension = $event->getExtensionBundle();
        $category = (int) $this->variableApi->get('ZikulaAdminModule', 'defaultcategory');
        $module = $this->extensionRepository->findOneBy(['name' => $extension->getName()]);
        $sortOrder = $this->adminModuleRepository->countModulesByCategory($category);

        // move the module
        $adminModuleEntity = $this->adminModuleRepository->findOneBy(['mid' => $module->getId()]);
        if (!$adminModuleEntity) {
            $adminModuleEntity = new AdminModuleEntity();
        }
        $adminModuleEntity->setMid($module->getId());
        $adminModuleEntity->setCid($category);
        $adminModuleEntity->setSortorder($sortOrder);

        $this->adminModuleRepository->persistAndFlush($adminModuleEntity);
    }
}
