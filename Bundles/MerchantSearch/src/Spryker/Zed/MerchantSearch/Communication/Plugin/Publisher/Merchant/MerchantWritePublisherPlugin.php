<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Communication\Plugin\Publisher\Merchant;

use Spryker\Shared\MerchantSearch\MerchantSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\MerchantSearch\MerchantSearchConfig getConfig()
 * @method \Spryker\Zed\MerchantSearch\Business\MerchantSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSearch\Communication\MerchantSearchCommunicationFactory getFactory()
 */
class MerchantWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $this->getFacade()->writeCollectionByMerchantEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            MerchantSearchConfig::MERCHANT_PUBLISH,
            MerchantSearchConfig::ENTITY_SPY_MERCHANT_CREATE,
            MerchantSearchConfig::ENTITY_SPY_MERCHANT_UPDATE,
        ];
    }
}
