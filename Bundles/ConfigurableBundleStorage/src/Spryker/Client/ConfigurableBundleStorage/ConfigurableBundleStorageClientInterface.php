<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleStorage;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;

interface ConfigurableBundleStorageClientInterface
{
    /**
     * Specification:
     * - Finds a configurable bundle template within Storage with a given ID.
     * - Returns null if configurable bundle template was not found.
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorage(int $idConfigurableBundleTemplate): ?ConfigurableBundleTemplateStorageTransfer;

    /**
     * Specification:
     * - Finds configurable bundle template within Storage with a given uuid.
     * - Returns ConfigurableBundleTemplateStorageTransfer if found, null otherwise.
     *
     * @api
     *
     * @param string $configurableBundleTemplateUuid
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    public function findConfigurableBundleTemplateStorageByUuid(string $configurableBundleTemplateUuid): ?ConfigurableBundleTemplateStorageTransfer;

    /**
     * Specification:
     * - Finds product concrete Storage records for current locale by skus.
     * - Returns array of transfers indexed by sku.
     *
     * @api
     *
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductConcreteStoragesBySkusForCurrentLocale(array $skus): array;
}
