<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilder;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\AbstractTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\DateRangeFilterValueNormalizerPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\RequestToGuiTableDataRequestHydrator;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\RequestToGuiTableDataRequestHydratorInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaBuilder\ProductOfferCriteriaFilterBuilder;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaBuilder\ProductOfferCriteriaFilterBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\CreatedAtProductOfferCriteriaFilterExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\IsActiveProductOfferCriteriaFilterExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ProductOfferCriteriaFilterExpanderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\StockProductOfferCriteriaFilterExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\StoreProductOfferCriteriaFilterExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\UpdatedAtProductOfferCriteriaFilterExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ValidityProductOfferCriteriaFilterExpander;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\DataProvider\ProductOfferTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\DataProvider\ProductOfferTableDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductOfferTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\TableDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 */
class ProductOfferMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\ProductTable
     */
    public function createProductTable(): ProductTable
    {
        return new ProductTable(
            $this->getTranslatorFacade(),
            $this->createProductTableDataProvider()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\TableDataProviderInterface
     */
    public function createProductTableDataProvider(): TableDataProviderInterface
    {
        return new ProductTableDataProvider(
            $this->getRepository(),
            $this->getTranslatorFacade(),
            $this->getUtilDateTimeService(),
            $this->createProductNameBuilder(),
            $this->getMerchantUserFacade(),
            $this->createRequestToGuiTableDataRequestHydrator()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\ProductOfferTable
     */
    public function createProductOfferTable(): AbstractTable
    {
        return new ProductOfferTable(
            $this->getUtilEncodingService(),
            $this->getTranslatorFacade(),
            $this->createProductOfferTableDataProvider(),
            $this->getProductOfferTableFilters(),
            $this->createProductOfferCriteriaFilterBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaBuilder\ProductOfferCriteriaFilterBuilderInterface
     */
    public function createProductOfferCriteriaFilterBuilder(): ProductOfferCriteriaFilterBuilderInterface
    {
        return new ProductOfferCriteriaFilterBuilder(
            $this->getMerchantUserFacade(),
            $this->getLocaleFacade(),
            $this->getProductOfferCriteriaFilterExpanders()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\DataProvider\ProductOfferTableDataProviderInterface
     */
    public function createProductOfferTableDataProvider(): ProductOfferTableDataProviderInterface
    {
        return new ProductOfferTableDataProvider(
            $this->getRepository(),
            $this->getTranslatorFacade(),
            $this->getUtilDateTimeService(),
            $this->createProductNameBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterInterface
     */
    public function createIsActiveProductOfferTableFilter(): TableFilterInterface
    {
        return new IsActiveProductOfferTableFilter();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterInterface
     */
    public function createStockProductOfferTableFilter(): TableFilterInterface
    {
        return new StockProductOfferTableFilter();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterInterface
     */
    public function createStoreProductOfferTableFilter(): TableFilterInterface
    {
        return new StoreProductOfferTableFilter($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterInterface
     */
    public function createValidityProductOfferTableFilter(): TableFilterInterface
    {
        return new ValidityProductOfferTableFilter();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterInterface
     */
    public function createCreatedAtProductOfferTableFilter(): TableFilterInterface
    {
        return new CreatedAtProductOfferTableFilter();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterInterface
     */
    public function createUpdatedAtProductOfferTableFilter(): TableFilterInterface
    {
        return new UpdatedAtProductOfferTableFilter();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterInterface[]
     */
    public function getProductOfferTableFilters(): array
    {
        return [
            $this->createIsActiveProductOfferTableFilter(),
            $this->createStockProductOfferTableFilter(),
            $this->createStoreProductOfferTableFilter(),
            $this->createValidityProductOfferTableFilter(),
            $this->createCreatedAtProductOfferTableFilter(),
            $this->createUpdatedAtProductOfferTableFilter(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ProductOfferCriteriaFilterExpanderInterface
     */
    public function createIsActiveProductOfferCriteriaFilterExpander(): ProductOfferCriteriaFilterExpanderInterface
    {
        return new IsActiveProductOfferCriteriaFilterExpander();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ProductOfferCriteriaFilterExpanderInterface
     */
    public function createStockProductOfferCriteriaFilterExpander(): ProductOfferCriteriaFilterExpanderInterface
    {
        return new StockProductOfferCriteriaFilterExpander();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ProductOfferCriteriaFilterExpanderInterface
     */
    public function createStoreProductOfferCriteriaFilterExpander(): ProductOfferCriteriaFilterExpanderInterface
    {
        return new StoreProductOfferCriteriaFilterExpander();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ProductOfferCriteriaFilterExpanderInterface
     */
    public function createValidityProductOfferCriteriaFilterExpander(): ProductOfferCriteriaFilterExpanderInterface
    {
        return new ValidityProductOfferCriteriaFilterExpander($this->getUtilDateTimeService());
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ProductOfferCriteriaFilterExpanderInterface
     */
    public function createCreatedAtProductOfferCriteriaFilterExpander(): ProductOfferCriteriaFilterExpanderInterface
    {
        return new CreatedAtProductOfferCriteriaFilterExpander($this->getUtilDateTimeService());
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ProductOfferCriteriaFilterExpanderInterface
     */
    public function createUpdatedAtProductOfferCriteriaFilterExpander(): ProductOfferCriteriaFilterExpanderInterface
    {
        return new UpdatedAtProductOfferCriteriaFilterExpander($this->getUtilDateTimeService());
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaExpander\ProductOfferCriteriaFilterExpanderInterface[]
     */
    public function getProductOfferCriteriaFilterExpanders(): array
    {
        return [
            $this->createIsActiveProductOfferCriteriaFilterExpander(),
            $this->createStockProductOfferCriteriaFilterExpander(),
            $this->createStoreProductOfferCriteriaFilterExpander(),
            $this->createValidityProductOfferCriteriaFilterExpander(),
            $this->createCreatedAtProductOfferCriteriaFilterExpander(),
            $this->createUpdatedAtProductOfferCriteriaFilterExpander(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Builder\ProductNameBuilderInterface
     */
    public function createProductNameBuilder(): ProductNameBuilderInterface
    {
        return new ProductNameBuilder();
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductOfferMerchantPortalGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): ProductOfferMerchantPortalGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    public function getTranslatorFacade(): ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductOfferMerchantPortalGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferMerchantPortalGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\RequestToGuiTableDataRequestHydratorInterface
     */
    public function createRequestToGuiTableDataRequestHydrator(): RequestToGuiTableDataRequestHydratorInterface
    {
        return new RequestToGuiTableDataRequestHydrator(
            $this->getUtilEncodingService(),
            $this->getLocaleFacade(),
            // @todo refactor to plugin stack
            [
                $this->createDateRangeFilterValueNormalizerPlugin(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest\DateRangeFilterValueNormalizerPlugin
     */
    public function createDateRangeFilterValueNormalizerPlugin(): DateRangeFilterValueNormalizerPlugin
    {
        return new DateRangeFilterValueNormalizerPlugin();
    }
}
