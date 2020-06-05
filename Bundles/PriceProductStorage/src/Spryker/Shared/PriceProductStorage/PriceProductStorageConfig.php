<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProductStorage;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PriceProductStorageConfig extends AbstractSharedConfig
{
    /**
     * @see \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DATA
     */
    public const PRICE_DATA = 'priceData';

    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     */
    public const PRICE_NET_MODE = 'NET_MODE';

    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     */
    public const PRICE_GROSS_MODE = 'GROSS_MODE';

    /**
     * @see \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_MODES
     */
    public const PRICE_MODES = [
        'NET_MODE',
        'GROSS_MODE',
    ];

    /**
     * Defines queue name for publish.
     */
    public const PUBLISH_PRICE_PRODUCT_ABSTRACT = 'publish.price_product_abstract';

    /**
     * Defines queue name for publish.
     */
    public const PUBLISH_PRICE_PRODUCT_CONCRETE = 'publish.price_product_concrete';

    /**
     * Defines error queue name as used when with asynchronous event handling
     */
    public const PUBLISH_PRICE_PRODUCT_ABSTRACT_ERROR_QUEUE = 'publish.price_product_abstract.error';

    /**
     * Defines error queue name as used when with asynchronous event handling
     */
    public const PUBLISH_PRICE_PRODUCT_CONCRETE_ERROR_QUEUE = 'publish.price_product_concrete.error';
}
