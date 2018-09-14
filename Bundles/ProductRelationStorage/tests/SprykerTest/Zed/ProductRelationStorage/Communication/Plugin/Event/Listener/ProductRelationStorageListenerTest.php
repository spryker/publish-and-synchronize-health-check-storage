<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Orm\Zed\ProductRelationStorage\Persistence\SpyProductAbstractRelationStorageQuery;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\ProductRelation\Business\ProductRelationFacade;
use Spryker\Zed\ProductRelation\Dependency\ProductRelationEvents;
use Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageBusinessFactory;
use Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade;
use Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener\ProductRelationProductAbstractStorageListener;
use Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener\ProductRelationPublishStorageListener;
use Spryker\Zed\ProductRelationStorage\Communication\Plugin\Event\Listener\ProductRelationStorageListener;
use SprykerTest\Zed\ProductRelationStorage\ProductRelationStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductRelationStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductRelationStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductRelationStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductRelationStorage\ProductRelationStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransferRelated;

    /**
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $dbEngine = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
        if ($dbEngine !== 'pgsql') {
            throw new SkippedTestError('Warning: no PostgreSQL is detected');
        }

        $this->productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->productAbstractTransferRelated = $this->tester->haveProductAbstract();

        $this->addLocalizedAttributesToProductAbstract($this->productAbstractTransfer);
        $this->addLocalizedAttributesToProductAbstract($this->productAbstractTransferRelated);

        $this->tester->haveProductRelation(
            $this->productAbstractTransfer->getSku(),
            $this->productAbstractTransferRelated->getIdProductAbstract()
        );
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\ProductRelationFacade
     */
    protected function createProductRelationFacade()
    {
        return new ProductRelationFacade();
    }

    /**
     * @return void
     */
    public function testProductRelationPublishStorageListenerStoreData()
    {
        SpyProductAbstractRelationStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransferRelated->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractRelationStorageQuery::create()->count();

        $productRelationPublishStorageListener = new ProductRelationPublishStorageListener();
        $productRelationPublishStorageListener->setFacade($this->getProductRelationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransferRelated->getIdProductAbstract()),
        ];
        $productRelationPublishStorageListener->handleBulk($eventTransfers, ProductRelationEvents::PRODUCT_ABSTRACT_RELATION_PUBLISH);

        // Assert
        $this->assertProductAbstractRelationStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductRelationStorageListenerStoreData()
    {
        SpyProductAbstractRelationStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransferRelated->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractRelationStorageQuery::create()->count();

        $productRelationStorageListener = new ProductRelationStorageListener();
        $productRelationStorageListener->setFacade($this->getProductRelationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransferRelated->getIdProductAbstract(),
            ]),
        ];
        $productRelationStorageListener->handleBulk($eventTransfers, ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_CREATE);

        // Assert
        $this->assertProductAbstractRelationStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductRelationProductAbstractStorageListenerStoreData()
    {
        SpyProductAbstractRelationStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransferRelated->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractRelationStorageQuery::create()->count();

        $productRelationProductAbstractStorageListener = new ProductRelationProductAbstractStorageListener();
        $productRelationProductAbstractStorageListener->setFacade($this->getProductRelationStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductRelationProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransferRelated->getIdProductAbstract(),
            ]),
        ];
        $productRelationProductAbstractStorageListener->handleBulk($eventTransfers, ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_CREATE);

        // Assert
        $this->assertProductAbstractRelationStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageFacade
     */
    protected function getProductRelationStorageFacade()
    {
        $factory = new ProductRelationStorageBusinessFactory();
        $factory->setConfig(new ProductRelationStorageConfigMock());

        $facade = new ProductRelationStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractRelationStorage($beforeCount)
    {
        $productRelationStorageCount = SpyProductAbstractRelationStorageQuery::create()->count();
        $this->assertSame($beforeCount + 1, $productRelationStorageCount);
        $productAbstractRelationStorage = SpyProductAbstractRelationStorageQuery::create()->orderByIdProductAbstractRelationStorage()->findOneByFkProductAbstract($this->productAbstractTransferRelated->getIdProductAbstract());
        $this->assertNotNull($productAbstractRelationStorage);
        $data = $productAbstractRelationStorage->getData();
        $this->assertSame(1, count($data['product_relations']));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function addLocalizedAttributesToProductAbstract(ProductAbstractTransfer $productAbstractTransfer): void
    {
        $productAbstractTransfer->setLocalizedAttributes(
            new ArrayObject($this->tester->generateLocalizedAttributes())
        );

        $this->tester->getProductFacade()->saveProductAbstract($productAbstractTransfer);
    }
}
