<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ConfigurableBundleFacadeInterface
{
    /**
     * Specification:
     * - Retrieves configurable bundle template in Persistence.
     * - Filters by criteria from ConfigurableBundleTemplateFilterTransfer.
     * - Expands found configurable bundle template with translations.
     * - Provides translations for locales specified in ConfigurableBundleTemplateFilterTransfer::translationLocales, or for all available locales otherwise.
     * - If single locale translation was requested but doesn't exist, fallback translation will be provided, or translation key if nothing found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function getConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer;

    /**
     * Specification:
     * - Retrieves configurable bundle templates in Persistence.
     * - Filters by criteria from ConfigurableBundleTemplateFilterTransfer.
     * - Expands found configurable bundle templates with translations.
     * - Provides translations for locales specified in ConfigurableBundleTemplateFilterTransfer::translationLocales, or for all available locales otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer
     */
    public function getConfigurableBundleTemplateCollection(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateCollectionTransfer;

    /**
     * Specification:
     * - Persists configurable bundle template.
     * - Expects minimum one translation to be provided.
     * - Expects locale definition for each provided translation.
     * - Generates translation key using first translation.
     * - Persists provided configurable bundle template name translations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function createConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer;

    /**
     * Specification:
     * - Expects configurable bundle template ID to be provided.
     * - Persists configurable bundle template.
     * - Expects minimum one translation to be provided.
     * - Expects locale definition for each provided translation.
     * - Updates translation key using first translation.
     * - Updates provided configurable bundle template name translations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function updateConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer;

    /**
     * Specification:
     * - Expects configurable bundle template ID to be provided.
     * - Updates isActive configurable bundle template property to true in Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function activateConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer;

    /**
     * Specification:
     * - Expects configurable bundle template ID to be provided.
     * - Updates isActive configurable bundle template property to false in Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function deactivateConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer;

    /**
     * Specification:
     * - Expects configurable bundle template ID to be provided.
     * - Retrieves configurable bundle template by id.
     * - Removes configurable bundle template from Persistence.
     * - Removes configurable bundle template slots from Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function deleteConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ConfigurableBundleTemplateResponseTransfer;

    /**
     * Specification:
     * - Retrieves configurable bundle template slot in Persistence.
     * - Filters by criteria from ConfigurableBundleTemplateSlotFilterTransfer.
     * - Expands found configurable bundle template slots with translations.
     * - Expands found configurable bundle template slots with product lists.
     * - Provides translations for locales specified in ConfigurableBundleTemplateSlotFilterTransfer::translationLocales, or for all available locales otherwise.
     * - If single locale translation was requested but doesn't exist, fallback translation will be provided, or translation key if nothing found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    public function getConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer;

    /**
     * Specification:
     * - Retrieves configurable bundle template slots in Persistence.
     * - Filters by criteria from ConfigurableBundleTemplateSlotFilterTransfer.
     * - Expands found configurable bundle template slots with translations.
     * - Provides translations for locales specified in ConfigurableBundleTemplateSlotFilterTransfer::translationLocales, or for all available locales otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotCollectionTransfer
     */
    public function getConfigurableBundleTemplateSlotCollection(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ConfigurableBundleTemplateSlotCollectionTransfer;

    /**
     * Specification:
     * - Persists configurable bundle template slot.
     * - Expects configurable bundle template ID to be provided.
     * - Creates new product list, assigns it to a slot.
     * - Expects minimum one translation to be provided.
     * - Expects locale definition for each provided translation.
     * - Generates translation key using first translation.
     * - Persists provided configurable bundle template slot name translations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    public function createConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer;

    /**
     * Specification:
     * - Expects configurable bundle template slot ID to be provided.
     * - Expects product list ID to be provided.
     * - Persists configurable bundle template slot.
     * - Expects minimum one translation to be provided.
     * - Expects locale definition for each provided translation.
     * - Updates translation key using first translation.
     * - Persists provided configurable bundle template slot name translations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    public function updateConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer;

    /**
     * Specification:
     * - Expects configurable bundle template slot ID to be provided.
     * - Removes configurable bundle template slot with given ID from Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotResponseTransfer
     */
    public function deleteConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ConfigurableBundleTemplateSlotResponseTransfer;

    /**
     * Specification:
     * - Retrieves active configurable bundle template UUIDs related to this quote.
     * - Removes item from QuoteTransfer if its configurable bundle template is removed.
     * - Removes item from QuoteTransfer if its configurable bundle template is inactive.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeInactiveConfiguredBundleItemsFromQuote(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Retrieves configurable bundle template slots which use given product list by ConfigurableBundleTemplateSlotFilterTransfer::productList::idProductList.
     * - Returns ProductListResponseTransfer with check results.
     * - ProductListResponseTransfer::isSuccessful is equal to true when usage cases were not found, false otherwise.
     * - ProductListResponseTransfer::messages contains usage details.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function checkProductListUsageAmongSlots(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ProductListResponseTransfer;
}
