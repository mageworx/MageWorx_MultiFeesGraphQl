<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\MultiFeesGraphQl\Model\Resolver\Cart;

use Magento\Framework\GraphQl\Query\EnumLookup;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

abstract class AbstractAppliedFees implements ResolverInterface
{
    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var EnumLookup
     */
    protected $enumLookup;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param EnumLookup $enumLookup
     */
    public function __construct(PriceCurrencyInterface $priceCurrency, EnumLookup $enumLookup)
    {
        $this->priceCurrency = $priceCurrency;
        $this->enumLookup    = $enumLookup;
    }

    /**
     * @param array $options
     * @param string $currencyCode
     * @return array
     */
    protected function getPreparedOprions(array $options, string $currencyCode): array
    {
        $data = [];

        foreach ($options as $id => $optionData) {
            $data[] = [
                'id'    => (int)$id,
                'title' => $optionData['title'],
                'price' => [
                    'value'    => $this->priceCurrency->roundPrice((float)$optionData['price']),
                    'currency' => $currencyCode
                ],
                'tax'   => [
                    'value'    => $this->priceCurrency->roundPrice((float)$optionData['tax']),
                    'currency' => $currencyCode
                ]
            ];
        }

        return $data;
    }
}
