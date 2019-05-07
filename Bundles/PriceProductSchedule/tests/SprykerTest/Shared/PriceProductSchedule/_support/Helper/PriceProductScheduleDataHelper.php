<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PriceProductSchedule\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\DataBuilder\PriceProductScheduleBuilder;
use Generated\Shared\DataBuilder\PriceProductScheduleListBuilder;
use Generated\Shared\DataBuilder\PriceTypeBuilder;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * @method \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface getFacade()
 */
class PriceProductScheduleDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $priceProductScheduleOverrideData
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function havePriceProductSchedule(array $priceProductScheduleOverrideData = []): PriceProductScheduleTransfer
    {
        $priceProductScheduleTransfer = (new PriceProductScheduleBuilder($this->preparePriceProductScheduleData($priceProductScheduleOverrideData)))
            ->build();

        $spyPriceProductScheduleEntity = $this->mapPriceProductScheduleTransferToEntity($priceProductScheduleTransfer);
        $spyPriceProductScheduleEntity->save();

        $priceProductScheduleTransfer->setIdPriceProductSchedule($spyPriceProductScheduleEntity->getIdPriceProductSchedule());

        return $priceProductScheduleTransfer;
    }

    /**
     * @param array $priceProductScheduleData
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    public function havePriceProductScheduleList(array $priceProductScheduleData = []): PriceProductScheduleListTransfer
    {
        $priceProductScheduleListTransfer = (new PriceProductScheduleListBuilder($priceProductScheduleData))
            ->build();

        $spyPriceProductScheduleListEntity = new SpyPriceProductScheduleList();
        $spyPriceProductScheduleListEntity->fromArray($priceProductScheduleListTransfer->modifiedToArray());
        $spyPriceProductScheduleListEntity->save();

        $priceProductScheduleListTransfer->setIdPriceProductScheduleList($spyPriceProductScheduleListEntity->getIdPriceProductScheduleList());

        return $priceProductScheduleListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule
     */
    protected function mapPriceProductScheduleTransferToEntity(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): SpyPriceProductSchedule {
        $spyPriceProductScheduleEntity = new SpyPriceProductSchedule();
        $spyPriceProductScheduleEntity->fromArray($priceProductScheduleTransfer->modifiedToArray());
        $spyPriceProductScheduleEntity->setFkStore($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getFkStore());
        $spyPriceProductScheduleEntity->setFkCurrency($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getFkCurrency());
        $spyPriceProductScheduleEntity->setFkPriceType($priceProductScheduleTransfer->getPriceProduct()->getPriceType()->getIdPriceType());
        $spyPriceProductScheduleEntity->setGrossPrice($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getGrossAmount());
        $spyPriceProductScheduleEntity->setNetPrice($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getNetAmount());
        $spyPriceProductScheduleEntity->setFkProduct($priceProductScheduleTransfer->getPriceProduct()->getIdProduct());
        $spyPriceProductScheduleEntity->setFkProductAbstract($priceProductScheduleTransfer->getPriceProduct()->getIdProductAbstract());
        $spyPriceProductScheduleEntity->setFkPriceProductScheduleList($priceProductScheduleTransfer->getPriceProductScheduleList()->getIdPriceProductScheduleList());

        return $spyPriceProductScheduleEntity;
    }

    /**
     * @param array $priceProductScheduleOverrideData
     *
     * @return array
     */
    protected function preparePriceProductScheduleData(array $priceProductScheduleOverrideData): array
    {
        $priceTypeTransfer = (new PriceTypeBuilder($priceProductScheduleOverrideData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::PRICE_TYPE]))
            ->build();
        unset($priceProductScheduleOverrideData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::PRICE_TYPE]);

        $moneyValueTransfer = (new MoneyValueBuilder($priceProductScheduleOverrideData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE]))
            ->build();
        unset($priceProductScheduleOverrideData[PriceProductScheduleTransfer::PRICE_PRODUCT][PriceProductTransfer::MONEY_VALUE]);

        $priceProductData = $priceProductScheduleOverrideData[PriceProductScheduleTransfer::PRICE_PRODUCT];
        $priceProductData[PriceProductTransfer::PRICE_TYPE] = $priceTypeTransfer;
        $priceProductData[PriceProductTransfer::MONEY_VALUE] = $moneyValueTransfer;

        $priceProductTransfer = (new PriceProductBuilder($priceProductData))
            ->build();
        unset($priceProductScheduleOverrideData[PriceProductScheduleTransfer::PRICE_PRODUCT]);

        $priceProductScheduleData = [
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $this->havePriceProductScheduleList(),
            PriceProductScheduleTransfer::PRICE_PRODUCT => $priceProductTransfer,
        ];

        return array_merge($priceProductScheduleData, $priceProductScheduleOverrideData);
    }
}