<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentMethodTransfer;

interface ShipmentRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    public function isShipmentMethodUniqueForCarrier(ShipmentMethodTransfer $shipmentMethodTransfer): bool;

    /**
     * @param int $idShipmentMethod
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function getShipmentMethodPricesByIdShipmentMethod(int $idShipmentMethod): ArrayObject;
}
