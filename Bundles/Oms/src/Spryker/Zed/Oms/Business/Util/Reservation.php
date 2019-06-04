<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface;
use Spryker\Zed\Oms\Dependency\Service\OmsToUtilQuantityServiceInterface;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class Reservation implements ReservationInterface
{
    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface[]
     */
    protected $reservationHandlerPlugins;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Oms\Business\Util\ActiveProcessFetcherInterface
     */
    protected $activeProcessFetcher;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Service\OmsToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Zed\Oms\Business\Util\ActiveProcessFetcherInterface $activeProcessFetcher
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface[] $reservationHandlerPlugins
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Oms\Dependency\Service\OmsToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(
        ActiveProcessFetcherInterface $activeProcessFetcher,
        OmsQueryContainerInterface $queryContainer,
        array $reservationHandlerPlugins,
        OmsToStoreFacadeInterface $storeFacade,
        OmsToUtilQuantityServiceInterface $utilQuantityService
    ) {
        $this->activeProcessFetcher = $activeProcessFetcher;
        $this->queryContainer = $queryContainer;
        $this->reservationHandlerPlugins = $reservationHandlerPlugins;
        $this->storeFacade = $storeFacade;
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateReservationQuantity($sku)
    {
        $currentStoreReservationAmount = $this->sumReservedProductQuantitiesForSku($sku);

        $currentStoreTransfer = $this->storeFacade->getCurrentStore();
        $this->saveReservation($sku, $currentStoreTransfer, $currentStoreReservationAmount);
        foreach ($currentStoreTransfer->getStoresWithSharedPersistence() as $storeName) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeName);
            $this->saveReservation($sku, $storeTransfer, $currentStoreReservationAmount);
        }

        $this->handleReservationPlugins($sku);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return float
     */
    public function sumReservedProductQuantitiesForSku($sku, ?StoreTransfer $storeTransfer = null)
    {
        return $this->sumProductQuantitiesForSku(
            $this->activeProcessFetcher->getReservedStatesFromAllActiveProcesses(),
            $sku,
            false,
            $storeTransfer
        );
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return float
     */
    public function getOmsReservedProductQuantityForSku($sku, StoreTransfer $storeTransfer)
    {
        $storeTransfer->requireName();

        $idStore = $this->getIdStore($storeTransfer);

        $reservationEntity = $this->queryContainer
            ->queryProductReservationBySkuAndStore($sku, $idStore)
            ->findOne();

        $reservationQuantity = 0;
        if ($reservationEntity !== null) {
            $reservationQuantity = $reservationEntity->getReservationQuantity();
        }

        return $this->sumQuantities(
            $reservationQuantity,
            $this->getReservationsFromOtherStores($sku, $storeTransfer)
        );
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    protected function sumQuantities(float $firstQuantity, float $secondQuantity): float
    {
        return $this->utilQuantityService->sumQuantities($firstQuantity, $secondQuantity);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStoreTransfer
     *
     * @return int
     */
    public function getReservationsFromOtherStores($sku, StoreTransfer $currentStoreTransfer)
    {
        $reservationQuantity = 0;
        $reservationStores = $this->queryContainer
            ->queryOmsProductReservationStoreBySku($sku)
            ->find();

        foreach ($reservationStores as $omsProductReservationStoreEntity) {
            if ($omsProductReservationStoreEntity->getStore() === $currentStoreTransfer->getName()) {
                continue;
            }
            $reservationQuantity = $this->sumQuantities(
                $reservationQuantity,
                $omsProductReservationStoreEntity->getReservationQuantity()
            );
        }

        return $reservationQuantity;
    }

    /**
     * @return string[]
     */
    public function getReservedStateNames()
    {
        $stateNames = [];
        foreach ($this->activeProcessFetcher->getReservedStatesFromAllActiveProcesses() as $reservedState) {
            $stateNames[] = $reservedState->getName();
        }

        return $stateNames;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return float
     */
    protected function sumProductQuantitiesForSku(
        array $states,
        $sku,
        $returnTest = true,
        ?StoreTransfer $storeTransfer = null
    ) {

        if ($storeTransfer) {
            return (float)$this->queryContainer
                ->sumProductQuantitiesForAllSalesOrderItemsBySkuForStore(
                    $states,
                    $sku,
                    $storeTransfer->getName(),
                    $returnTest
                )
                ->findOne();
        }

        return (float)$this->queryContainer
            ->sumProductQuantitiesForAllSalesOrderItemsBySku($states, $sku, $returnTest)
            ->findOne();
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param float $reservationQuantity
     *
     * @return void
     */
    public function saveReservation(string $sku, StoreTransfer $storeTransfer, float $reservationQuantity): void
    {
        $storeTransfer->requireIdStore();

        $reservationEntity = $this->queryContainer
            ->queryProductReservationBySkuAndStore($sku, $storeTransfer->getIdStore())
            ->findOneOrCreate();

        $reservationEntity->setReservationQuantity($reservationQuantity);
        $reservationEntity->save();
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function handleReservationPlugins($sku)
    {
        foreach ($this->reservationHandlerPlugins as $reservationHandlerPluginInterface) {
            $reservationHandlerPluginInterface->handle($sku);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    protected function getIdStore(StoreTransfer $storeTransfer)
    {
        if ($storeTransfer->getIdStore()) {
            return $storeTransfer->getIdStore();
        }

        return $this->storeFacade
            ->getStoreByName($storeTransfer->getName())
            ->getIdStore();
    }
}
