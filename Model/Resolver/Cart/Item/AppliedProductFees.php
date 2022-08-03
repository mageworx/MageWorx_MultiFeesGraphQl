<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\MultiFeesGraphQl\Model\Resolver\Cart\Item;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\EnumLookup;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote\Item;
use MageWorx\MultiFees\Api\Data\FeeInterface;
use MageWorx\MultiFees\Api\QuoteProductFeeManagerInterface;
use MageWorx\MultiFeesGraphQl\Model\Resolver\Cart\AbstractAppliedFees;

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
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\RuntimeException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        /** @var Item $cartItem */
        $cartItem         = $value['model'];
        $cartItemId       = $cartItem->getId();
        $quote            = $cartItem->getQuote();
        $currencyCode     = $quote->getQuoteCurrencyCode();
        $productFeeDetail = $this->quoteProductFeeManager->getFeeDetailFromQuote($quote);
        $data             = [];

        if (is_array(current($productFeeDetail))) {
            foreach (current($productFeeDetail) as $feeId => $feeDataByItemId) {
                foreach ($feeDataByItemId as $itemId => $feeData) {
                    if ($cartItemId != $itemId) {
                        continue;
                    }
                    $feeData[FeeInterface::FEE_ID] = $feeId;
                    $data[]                        = $this->getPreparedFeeData($feeData, $currencyCode);
                }
            }
        }

        return $data ?: null;
    }
}
