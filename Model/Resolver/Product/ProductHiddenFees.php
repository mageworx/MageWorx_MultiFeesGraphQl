<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\MultiFeesGraphQl\Model\Resolver\Product;

use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Model\Quote\ItemFactory as QuoteItemFactory;
use MageWorx\MultiFees\Api\FeeCollectionManagerInterface;
use MageWorx\MultiFees\Api\QuoteProductFeeManagerInterface;
use MageWorx\MultiFees\Helper\Data;
use MageWorx\MultiFees\Helper\Price as HelperPrice;
use MageWorx\MultiFees\Model\ResourceModel\Fee\ProductFeeCollection;
use MageWorx\MultiFees\Api\Data\FeeInterface;

class ProductHiddenFees implements ResolverInterface
{
    /**
     * @var QuoteItemFactory
     */
    protected $quoteItemFactory;

    /**
     * @var FeeCollectionManagerInterface
     */
    protected $feeCollectionManager;

    /**
     * @var QuoteProductFeeManagerInterface
     */
    protected $quoteFeeManager;

    /**
     * @var HelperPrice
     */
    protected $helperPrice;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @param QuoteItemFactory $quoteItemFactory
     * @param FeeCollectionManagerInterface $feeCollectionManager
     * @param QuoteProductFeeManagerInterface $quoteFeeManager
     * @param HelperPrice $helperPrice
     * @param Data $helperData
     */
    public function __construct(
        QuoteItemFactory $quoteItemFactory,
        FeeCollectionManagerInterface $feeCollectionManager,
        QuoteProductFeeManagerInterface $quoteFeeManager,
        HelperPrice $helperPrice,
        Data $helperData
    ) {
        $this->quoteItemFactory     = $quoteItemFactory;
        $this->feeCollectionManager = $feeCollectionManager;
        $this->quoteFeeManager      = $quoteFeeManager;
        $this->helperPrice          = $helperPrice;
        $this->helperData           = $helperData;
    }

    /**
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws LocalizedException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null): ?array
    {
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        /** @var Product $product */
        $product = $value['model'];

        $quoteItem = $this->quoteItemFactory->create();
        $quoteItem->setProduct($product);
        $quoteItem->setQty(1);

        $this->helperData->setCurrentItem($quoteItem);

        $feeCollection = $this->getFeeCollection();
        $feeCollection = $this->quoteFeeManager->validateFeeCollectionByQuoteItem($feeCollection, $quoteItem);
        $data          = [];

        /** @var FeeInterface $fee */
        foreach ($feeCollection as $fee) {
            $data[] = $this->getPreparedFeeData($fee);
        }

        return $data;
    }

    /**
     * @return ProductFeeCollection
     * @throws LocalizedException
     */
    protected function getFeeCollection(): ProductFeeCollection
    {
        return $this->feeCollectionManager->getProductFeeCollection(
            false,
            false,
            FeeCollectionManagerInterface::HIDDEN_MODE_ONLY
        );
    }

    /**
     * @param FeeInterface $fee
     * @return array
     */
    protected function getPreparedFeeData(FeeInterface $fee): array
    {
        $options = $this->getPreparedFeeOptions($fee);

        return [
            'id'          => (int)$fee->getId(),
            'title'       => $fee->getTitle(),
            'description' => $fee->getDescription(),
            'input_type'  => $fee->getInputType(),
            'is_required' => (bool)$fee->getRequired(),
            'sort_order'  => (int)$fee->getSortOrder(),
            'options'     => $options ?: null
        ];
    }

    /**
     * @param FeeInterface $fee
     * @return array
     * @throws LocalizedException
     * @throws LocalizedException
     */
    protected function getPreparedFeeOptions(FeeInterface $fee): array
    {
        $options = [];

        foreach ($fee->getOptions() as $option) {
            if (!$option->getIsDefault()) {
                continue;
            }

            try {
                $labelPriceTitle = ' - ' . $this->helperPrice->getOptionFormatPrice($option, $fee);
            } catch (LocalizedException $e) {
                $labelPriceTitle = '';
            }

            $options[] = [
                'id'          => (int)$option->getId(),
                'field_label' => $option->getTitle() . $labelPriceTitle,
                'is_default'  => (bool)$option->getIsDefault(),
                'position'    => (int)$option->getPosition(),
                'title'       => $option->getTitle(),
                'price_type'  => $option->getPriceType(),
                'price'       => (float)$option->getPrice()
            ];
        }

        return $options;
    }
}
