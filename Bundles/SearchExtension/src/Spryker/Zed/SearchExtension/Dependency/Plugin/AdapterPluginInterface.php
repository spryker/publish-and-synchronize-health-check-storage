<?php
/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchExtension\Dependency\Plugin;

interface AdapterPluginInterface
{
    /**
     * Specification:
     * - Returns the total number of documents in the current index if no indexName is passed.
     * - Returns the total number of documents in the passed indexName.
     *
     * @api
     *
     * @param string|null $indexName
     *
     * @return int
     */
    public function getTotalCount(?string $indexName = null);

    /**
     * Specification:
     * - Returns the metadata information from the current index if no indexName is passed.
     * - Returns the metadata information from the passed indexName.
     * - Returns an empty array if the index is not installed.
     *
     * @api
     *
     * @param string|null $indexName
     *
     * @return array
     */
    public function getMetaData(?string $indexName = null);

    /**
     * Specification:
     * - Removes the current index if no indexName is passed.
     * - Removes the passed indexName.
     *
     * @api
     *
     * @param string|null $indexName
     *
     * @return mixed
     */
    public function delete(?string $indexName = null);

    /**
     * Specification:
     * - Returns a document from the current index with the given key in the given mapping type
     *
     * @api
     *
     * @param string $key
     * @param string $type
     *
     * @return \Elastica\Document
     */
    public function getDocument($key, $type);

    /**
     * Specification:
     * - Runs a simple full text search for the given search string
     * - Returns the raw result set ordered by relevance
     *
     * @api
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return mixed
     */
    public function searchKeys($searchString, $limit = null, $offset = null);
}
