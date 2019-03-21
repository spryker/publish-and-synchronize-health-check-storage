<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerDataImport\Business\Model\Step;

use Spryker\Zed\ContentBannerDataImport\Business\Model\DataSet\ContentBannerDataSetInterface;
use Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentBannerInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CheckLocalizedItemsStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentBannerInterface
     */
    protected $contentBanner;

    /**
     * @param \Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentBannerInterface $contentBanner
     */
    public function __construct(ContentBannerDataImportToContentBannerInterface $contentBanner)
    {
        $this->contentBanner = $contentBanner;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $validatedItems = [];
        foreach ($dataSet[ContentBannerDataSetInterface::CONTENT_LOCALIZED_ITEMS] as $idLocale => $attributes) {
            $validationResult = $this->contentBanner->validateContentBanner($attributes);

            if ($validationResult->getIsSuccess()) {
                $validatedItems[$idLocale] = $attributes;
            }
        }

        $dataSet[ContentBannerDataSetInterface::CONTENT_LOCALIZED_ITEMS] = $validatedItems;
    }
}
