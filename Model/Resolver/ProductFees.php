<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\MultiFeesGraphQl\Model\Resolver;

use MageWorx\MultiFees\Api\QuoteProductFeeManagerInterface;

class ProductFees extends AbstractFee
{
    /**
     * @var QuoteProductFeeManagerInterface
     */
    protected $quoteProductFeeManager;

    /**
     * @param QuoteProductFeeManagerInterface $quoteProductFeeManager
     */
    public function __construct(QuoteProductFeeManagerInterface $quoteProductFeeManager)
    {
        $this->quoteProductFeeManager = $quoteProductFeeManager;
    }

    /**
     * @param int $quoteId
     * @return array
     */
    protected function getFees(int $quoteId): array
    {
        return $this->quoteProductFeeManager->getAvailableProductFees($quoteId);
    }

    /**
     * @param array $fees
     * @return array
     */
    protected function getData(array $fees): array
    {
        $data = [];

        foreach ($fees as $itemFees) {
            $itemFeesData = [];

            foreach ($itemFees['feesDetails'] as $feeData) {
                $itemFeesData[] = $this->getPreparedFeeData($feeData);
            }

            $data[] = [
                'quote_item_id' => (int)$itemFees['quoteItemId'],
                'fees'          => $itemFeesData
            ];
        }

        return $data;
    }
}
