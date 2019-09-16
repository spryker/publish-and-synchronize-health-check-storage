<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Reader;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @param string $groupKey
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getItemsByConfiguredBundleGroupKey(string $groupKey, QuoteTransfer $quoteTransfer): ArrayObject
    {
        $itemTransfers = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getConfiguredBundle() && $itemTransfer->getConfiguredBundle()->getGroupKey() === $groupKey) {
                $itemTransfers[] = $itemTransfer;
            }
        }

        return new ArrayObject($itemTransfers);
    }
}
