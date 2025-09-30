<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\MultiFeesGraphQl\Model\Resolver\Cart;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\RuntimeException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\EnumLookup;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use MageWorx\MultiFees\Api\Data\FeeInterface;
use MageWorx\MultiFees\Api\QuoteProductFeeManagerInterface;

class AppliedProductFees extends AbstractAppliedFees
{
    /**
     * @var QuoteProductFeeManagerInterface
     */
    protected $quoteProductFeeManager;

    /**
     * @param QuoteProductFeeManagerInterface $quoteProductFeeManager
     * @param PriceCurrencyInterface $priceCurrency
     * @param EnumLookup $enumLookup
     */
    public function __construct(
        QuoteProductFeeManagerInterface $quoteProductFeeManager,
        PriceCurrencyInterface $priceCurrency,
        EnumLookup $enumLookup
    ) {
        parent::__construct($priceCurrency, $enumLookup);
        $this->quoteProductFeeManager = $quoteProductFeeManager;
    }

    /**
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws LocalizedException
     * @throws RuntimeException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null): ?array
    {
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        $quote            = $value['model'];
        $currencyCode     = $quote->getQuoteCurrencyCode();
        $productFeeDetail = $this->quoteProductFeeManager->getFeeDetailFromQuote($quote);
        $feesByItemId     = [];

        if (is_array(current($productFeeDetail))) {
            foreach (current($productFeeDetail) as $feeId => $feeDataByItemId) {
                foreach ($feeDataByItemId as $itemId => $feeData) {
                    $feeData[FeeInterface::FEE_ID] = $feeId;
                    $feesByItemId[(int)$itemId][]  = $this->getPreparedFeeData($feeData, $currencyCode);
                }
            }
        }

        $productFeeData = [];

        foreach ($feesByItemId as $itemId => $fees) {
            $productFeeData[] = [
                'quote_item_id' => $itemId,
                'fees'          => $fees
            ];
        }

        return $productFeeData ?: null;
    }
}
