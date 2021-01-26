<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use Generated\Shared\Transfer\CategoryNodeUrlFilterTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface;
use Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryUrlUpdater implements CategoryUrlUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface
     */
    protected $urlPathGenerator;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface $urlPathGenerator
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface $urlFacade
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        UrlPathGeneratorInterface $urlPathGenerator,
        CategoryToUrlInterface $urlFacade
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->urlPathGenerator = $urlPathGenerator;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategoryUrl(CategoryTransfer $categoryTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($categoryTransfer) {
            $this->executeUpdateCategoryUrlTransaction($categoryTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeUpdateCategoryUrlTransaction(CategoryTransfer $categoryTransfer): void
    {
        $categoryNodeUrlFilterTransfer = (new CategoryNodeUrlFilterTransfer())
            ->setCategoryNodeIds($this->getCategoryNodeIdsFromNodeCollection($categoryTransfer->getNodeCollectionOrFail()));
        $urlTransfers = $this->categoryRepository->getCategoryNodeUrls($categoryNodeUrlFilterTransfer);

        foreach ($categoryTransfer->getLocalizedAttributes() as $categoryLocalizedAttributesTransfer) {
            $this->updateUrlsForNodes(
                $categoryTransfer->getNodeCollection(),
                $urlTransfers,
                $categoryLocalizedAttributesTransfer->getLocaleOrFail()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer[] $urlTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function updateUrlsForNodes(NodeCollectionTransfer $nodeCollectionTransfer, array $urlTransfers, LocaleTransfer $localeTransfer): void
    {
        foreach ($nodeCollectionTransfer->getNodes() as $nodeTransfer) {
            $this->updateCategoryNodeUrlsForLocale($nodeTransfer, $urlTransfers, $localeTransfer);
            if (!$nodeTransfer->getChildrenNodes()) {
                continue;
            }
            $this->updateUrlsForNodes($nodeTransfer->getChildrenNodes(), $urlTransfers, $localeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer[] $urlTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function updateCategoryNodeUrlsForLocale(NodeTransfer $nodeTransfer, array $urlTransfers, LocaleTransfer $localeTransfer): void
    {
        foreach ($urlTransfers as $urlTransfer) {
            if ($urlTransfer->getFkLocaleOrFail() !== $localeTransfer->getIdLocaleOrFail()) {
                continue;
            }
            $urlPath = $this->urlPathGenerator->buildCategoryNodeUrlForLocale($nodeTransfer, $localeTransfer);
            $urlTransfer->setUrl($urlPath);
            $this->urlFacade->updateUrl($urlTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     *
     * @return int[]
     */
    protected function getCategoryNodeIdsFromNodeCollection(NodeCollectionTransfer $nodeCollectionTransfer): array
    {
        $categoryNodeIds = [];
        foreach ($nodeCollectionTransfer->getNodes() as $nodeTransfer) {
            $categoryNodeIds[] = $nodeTransfer->getIdCategoryNodeOrFail();
            if (!$nodeTransfer->getChildrenNodes()) {
                continue;
            }

            $categoryNodeIds = array_merge(
                $categoryNodeIds,
                $this->getCategoryNodeIdsFromNodeCollection($nodeTransfer->getChildrenNodes())
            );
        }

        return $categoryNodeIds;
    }
}
