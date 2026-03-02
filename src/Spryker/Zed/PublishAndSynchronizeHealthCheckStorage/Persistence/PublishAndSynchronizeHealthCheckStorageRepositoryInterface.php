<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Orm\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\SpyPublishAndSynchronizeHealthCheckStorage;

interface PublishAndSynchronizeHealthCheckStorageRepositoryInterface
{
    public function findOrCreatePublishAndSynchronizeHealthCheckStorageByIdPublishAndSynchronizeHealthCheck(
        int $idPublishAndSynchronizeHealthCheck
    ): SpyPublishAndSynchronizeHealthCheckStorage;

    public function getPublishAndSynchronizeHealthCheckTransferByIdPublishAndSynchronizeHealthCheck(
        int $idPublishAndSynchronizeHealthCheck
    ): PublishAndSynchronizeHealthCheckTransfer;
}
