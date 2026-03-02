<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage;

use Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\SpyPublishAndSynchronizeHealthCheckQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Client\PublishAndSynchronizeHealthCheckStorageToStorageClientBridge;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Facade\PublishAndSynchronizeHealthCheckStorageToEventBehaviorFacade;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Facade\PublishAndSynchronizeHealthCheckStorageToPublishAndSynchronizeHealthCheckFacadeBridge;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig getConfig()
 */
class PublishAndSynchronizeHealthCheckStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

    /**
     * @var string
     */
    public const PROPEL_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_QUERY = 'PROPEL_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_QUERY';

    /**
     * @var string
     */
    public const FACADE_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK = 'FACADE_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK';

    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addStorageClient($container);

        return $container;
    }

    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addPublishAndSynchronizeHealthCheckQuery($container);

        return $container;
    }

    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addPublishAndSynchronizeHealthCheckFacade($container);

        return $container;
    }

    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new PublishAndSynchronizeHealthCheckStorageToEventBehaviorFacade($container->getLocator()->eventBehavior()->facade());
        });

        return $container;
    }

    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new PublishAndSynchronizeHealthCheckStorageToStorageClientBridge($container->getLocator()->storage()->client());
        });

        return $container;
    }

    protected function addPublishAndSynchronizeHealthCheckQuery(Container $container): Container
    {
        $container->set(static::PROPEL_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_QUERY, $container->factory(function (): SpyPublishAndSynchronizeHealthCheckQuery {
            return SpyPublishAndSynchronizeHealthCheckQuery::create();
        }));

        return $container;
    }

    protected function addPublishAndSynchronizeHealthCheckFacade(Container $container): Container
    {
        $container->set(static::FACADE_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK, function (Container $container) {
            return new PublishAndSynchronizeHealthCheckStorageToPublishAndSynchronizeHealthCheckFacadeBridge(
                $container->getLocator()->publishAndSynchronizeHealthCheck()->facade(),
            );
        });

        return $container;
    }
}
