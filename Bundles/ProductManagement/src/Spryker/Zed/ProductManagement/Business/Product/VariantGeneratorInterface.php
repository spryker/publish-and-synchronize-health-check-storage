<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ProductManagement\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface VariantGeneratorInterface
{
    /**
     * @param array $tokenAttributeCollection
     *
     * @return array
     */
    public function generateTokens(array $tokenAttributeCollection);

    /**
     * $attributeCollection = Array
     *  (
     *     [color] => Array
     *      (
     *          [red] => Red
     *          [blue] => Blue
     *      )
     *     [flavour] => Array
     *      (
     *          [sweet] => Cakes
     *      )
     *     [size] => Array
     *      (
     *          [40] => 40
     *          [41] => 41
     *          [42] => 42
     *          )
     *      )
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeCollection
     *
     * @return array|\Generated\Shared\Transfer\ZedProductConcreteTransfer[]
     */
    public function generate(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection);
}