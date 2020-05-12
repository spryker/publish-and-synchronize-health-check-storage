<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business\Reader;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;

class OrderItemCsvReader extends AbstractCsvReader
{
    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param int $offset
     * @param int $limit
     *
     * @return string[][]
     */
    public function csvReadBatch(DataExportConfigurationTransfer $dataExportConfigurationTransfer, int $offset, int $limit): array
    {
        $ordersData = $this->salesDataExportRepository->getOrderItemData($dataExportConfigurationTransfer, $offset, $limit);

        return $this->formatExportData($dataExportConfigurationTransfer->getFields(), $ordersData, $offset);
    }
}
