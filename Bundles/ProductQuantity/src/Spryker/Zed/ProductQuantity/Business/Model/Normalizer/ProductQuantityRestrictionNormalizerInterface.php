<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business\Model\Normalizer;

use Generated\Shared\Transfer\CartChangeTransfer;

interface ProductQuantityRestrictionNormalizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function normalizeCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;
}
