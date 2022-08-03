<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\MultiFeesGraphQl\Model\Resolver;

use MageWorx\MultiFees\Api\QuoteFeeManagerInterface;

abstract class AbstractQuoteFee extends AbstractFee
{
    /**
     * @var QuoteFeeManagerInterface
     */
    protected $quoteFeeManager;

    /**
     * @param QuoteFeeManagerInterface $quoteFeeManager
     */
    public function __construct(QuoteFeeManagerInterface $quoteFeeManager)
    {
        $this->quoteFeeManager = $quoteFeeManager;
    }

    /**
     * @param array $fees
     * @return array
     */
    protected function getData(array $fees): array
    {
        $data = [];

        foreach ($fees as $fee) {
            $data[] = $this->getPreparedFeeData($fee);
        }

        return $data;
    }
}
