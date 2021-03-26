<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Quote\Business\QuoteFacadeInterface;

class CheckoutToQuoteFacadeBridge implements CheckoutToQuoteFacadeInterface
{
    /**
     * @var \Spryker\Zed\Quote\Business\QuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\Quote\Business\QuoteFacadeInterface $quoteFacade
     */
    public function __construct($quoteFacade)
    {
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function updateQuote(QuoteTransfer $quoteTransfer)
    {
        $this->quoteFacade->updateQuote($quoteTransfer);
    }
}
