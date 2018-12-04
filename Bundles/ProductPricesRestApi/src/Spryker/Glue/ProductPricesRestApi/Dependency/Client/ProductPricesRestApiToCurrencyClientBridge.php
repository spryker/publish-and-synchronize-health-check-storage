<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Dependency\Client;

class ProductPricesRestApiToCurrencyClientBridge implements ProductPricesRestApiToCurrencyClientInterface
{
    /**
     * @var \Spryker\Client\Currency\CurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @param \Spryker\Client\Currency\CurrencyClientInterface $currencyClient
     */
    public function __construct($currencyClient)
    {
        $this->currencyClient = $currencyClient;
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrent()
    {
        return $this->currencyClient->getCurrent();
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function setCurrentCurrencyIsoCode(string $currencyIsoCode): void
    {
        $this->currencyClient->setCurrentCurrencyIsoCode($currencyIsoCode);
    }
}