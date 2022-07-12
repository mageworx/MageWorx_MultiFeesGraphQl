<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\MultiFeesGraphQl\Model\Resolver;

use MageWorx\MultiFees\Api\Data\FeeDataInterface;

class AddPaymentFeesToCart extends AbstractAddQuoteFeesToCart
{
    /**
     * @param int $quoteId
     * @param FeeDataInterface $feeData
     */
    protected function process(int $quoteId, FeeDataInterface $feeData): void
    {
        $this->quoteFeeManager->setPaymentFees($quoteId, $feeData);
    }
}
