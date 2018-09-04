<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToStoreFacadeInterface;

class MerchantRelationshipMinimumOrderValueTranslationReader implements MerchantRelationshipMinimumOrderValueTranslationReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface $glossaryFacade,
        MerchantRelationshipMinimumOrderValueToStoreFacadeInterface $storeFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function hydrateLocalizedMessages(MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer): MerchantRelationshipMinimumOrderValueTransfer
    {
        $storeTransfer = $this->storeFacade
            ->getStoreByName($merchantRelationshipMinimumOrderValueTransfer->getStore()->getName());

        foreach ($storeTransfer->getAvailableLocaleIsoCodes() as $localeIsoCode) {
            $this->initOrUpdateLocalizedMessages(
                $merchantRelationshipMinimumOrderValueTransfer,
                $localeIsoCode
            );
        }

        return $merchantRelationshipMinimumOrderValueTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     * @param string $localeIsoCode
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    protected function initOrUpdateLocalizedMessages(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer,
        string $localeIsoCode
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $translationValue = $this->findTranslationValue(
            $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValueThreshold()->getMessageGlossaryKey(),
            $this->createLocaleTransfer($localeIsoCode)
        );

        foreach ($merchantRelationshipMinimumOrderValueTransfer->getLocalizedMessages() as $minimumOrderValueLocalizedMessageTransfer) {
            if ($minimumOrderValueLocalizedMessageTransfer->getLocaleCode() === $localeIsoCode) {
                $minimumOrderValueLocalizedMessageTransfer->setMessage($translationValue);

                return $merchantRelationshipMinimumOrderValueTransfer;
            }
        }

        $merchantRelationshipMinimumOrderValueTransfer->addLocalizedMessage(
            (new MinimumOrderValueLocalizedMessageTransfer())
                ->setLocaleCode($localeIsoCode)
                ->setMessage($translationValue ? $translationValue : null)
        );

        return $merchantRelationshipMinimumOrderValueTransfer;
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function createLocaleTransfer(string $localeName): LocaleTransfer
    {
        return (new LocaleTransfer())
            ->setLocaleName($localeName);
    }

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    protected function findTranslationValue(string $keyName, LocaleTransfer $localeTransfer): ?string
    {
        if (!$this->glossaryFacade->hasTranslation($keyName, $localeTransfer)) {
            return null;
        }

        $translationTransfer = $this->glossaryFacade->getTranslation($keyName, $localeTransfer);

        return $translationTransfer->getValue();
    }
}
