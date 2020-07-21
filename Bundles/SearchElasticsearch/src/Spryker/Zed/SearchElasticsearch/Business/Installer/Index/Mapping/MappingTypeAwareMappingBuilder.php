<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping;

use Elastica\Index;
use Elastica\Type\Mapping;
use Generated\Shared\Transfer\IndexDefinitionTransfer;

/**
 * @deprecated Will be removed once the support of Elasticsearch 6 and lower is dropped.
 */
class MappingTypeAwareMappingBuilder implements MappingBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\IndexDefinitionTransfer $indexDefinitionTransfer
     * @param \Elastica\Index $index
     *
     * @return \Elastica\Type\Mapping
     */
    public function buildMapping(IndexDefinitionTransfer $indexDefinitionTransfer, Index $index)
    {
        $mappingTypeName = array_key_first($indexDefinitionTransfer->getMappings());
        $mappingData = $indexDefinitionTransfer->getMappings()[$mappingTypeName];
        $mappingType = $index->getType($mappingTypeName);
        $mapping = new Mapping($mappingType);

        foreach ($mappingData as $key => $value) {
            $mapping->setParam($key, $value);
        }

        return $mapping;
    }
}
