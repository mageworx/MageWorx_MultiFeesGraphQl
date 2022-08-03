<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\MultiFeesGraphQl\Model\Resolver;

class CartFees extends AbstractQuoteFee
{
    /**
     * @param int $quoteId
     * @return array
     */
    protected function getFees(int $quoteId): array
    {
        return $this->quoteFeeManager->getAvailableCartFees($quoteId);
    }
}
