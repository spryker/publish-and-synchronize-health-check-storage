<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface AvailabilityNotificationRepositoryInterface
{
    /**
     * @param string $email
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneSubscriptionByEmailAndSkuAndStore(string $email, string $sku, StoreTransfer $storeTransfer): ?AvailabilitySubscriptionTransfer;

    /**
     * @param string $subscriptionKey
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneSubscriptionBySubscriptionKey(string $subscriptionKey): ?AvailabilitySubscriptionTransfer;

    /**
     * @param string $customerReference
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer|null
     */
    public function findOneSubscriptionByCustomerReferenceAndSkuAndStore(string $customerReference, string $sku, StoreTransfer $storeTransfer): ?AvailabilitySubscriptionTransfer;
}
