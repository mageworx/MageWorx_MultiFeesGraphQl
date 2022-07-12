<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\MultiFeesGraphQl\Model\Resolver\Cart;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\EnumLookup;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use MageWorx\MultiFees\Api\Data\FeeInterface;
use MageWorx\MultiFees\Api\QuoteFeeManagerInterface;

class AppliedQuoteFees extends AbstractAppliedFees
{
    /**
     * @var QuoteFeeManagerInterface
     */
    protected $quoteFeeManager;

    /**
     * @param QuoteFeeManagerInterface $quoteFeeManager
     * @param PriceCurrencyInterface $priceCurrency
     * @param EnumLookup $enumLookup
     */
    public function __construct(
        QuoteFeeManagerInterface $quoteFeeManager,
        PriceCurrencyInterface $priceCurrency,
        EnumLookup $enumLookup
    ) {
        parent::__construct($priceCurrency, $enumLookup);
        $this->quoteFeeManager = $quoteFeeManager;
    }

    /**
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|null
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\RuntimeException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        $quote          = $value['model'];
        $currencyCode   = $quote->getQuoteCurrencyCode();
        $quoteFeeDetail = $this->quoteFeeManager->getFeeDetailFromQuote($quote);
        $quoteFeeData   = [];

        if (is_array(current($quoteFeeDetail))) {
            foreach (current($quoteFeeDetail) as $feeId => $feeData) {
                $feeData[FeeInterface::FEE_ID] = $feeId;
                $quoteFeeData[]                = $this->getPreparedFeeData($feeData, $currencyCode);
            }
        }

        return $quoteFeeData ?: null;
    }
}
