<?php

namespace SprykerFeature\Zed\Payone\Business\Api\Response\Container;

class PreAuthorizationResponseContainer extends AbstractResponseContainer
{
    /**
     * @var string
     */
    protected $txid;

    /**
     * @var string
     */
    protected $userid;
}
