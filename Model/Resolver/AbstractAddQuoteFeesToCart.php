<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\MultiFeesGraphQl\Model\Resolver;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use MageWorx\MultiFees\Api\Data\FeeDataInterface;
use MageWorx\MultiFees\Api\Data\FeeDataInterfaceFactory;
use MageWorx\MultiFees\Api\QuoteFeeManagerInterface;

abstract class AbstractAddQuoteFeesToCart implements ResolverInterface
{
    /**
     * @var QuoteFeeManagerInterface
     */
    protected $quoteFeeManager;

    /**
     * @var GetCartForUser
     */
    protected $getCartForUser;

    /**
     * @var FeeDataInterfaceFactory
     */
    protected $feeDataFactory;

    /**
     * @param QuoteFeeManagerInterface $quoteFeeManager
     * @param GetCartForUser $getCartForUser
     * @param FeeDataInterfaceFactory $feeDataFactory
     */
    public function __construct(
        QuoteFeeManagerInterface $quoteFeeManager,
        GetCartForUser $getCartForUser,
        FeeDataInterfaceFactory $feeDataFactory
    ) {
        $this->quoteFeeManager = $quoteFeeManager;
        $this->getCartForUser  = $getCartForUser;
        $this->feeDataFactory  = $feeDataFactory;
    }

    abstract protected function process(int $quoteId, FeeDataInterface $feeData): void;

    /**
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array[]
     * @throws GraphQlInputException
     * @throws GraphQlNoSuchEntityException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $this->validateParameters($args);

        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();
        $cart    = $this->getCartForUser->execute($args['input']['cart_id'], $context->getUserId(), $storeId);
        $cartId  = (int)$cart->getId();

        try {
            $this->process($cartId, $this->getFeeDataObject($args));
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        } catch (CouldNotSaveException $e) {
            throw new LocalizedException(__($e->getMessage()), $e);
        }

        return [
            'cart' => [
                'model' => $cart
            ],
        ];
    }

    /**
     * @param array $args
     * @return FeeDataInterface
     * @throws GraphQlInputException
     */
    protected function getFeeDataObject(array $args): FeeDataInterface
    {
        $objects = [];

        foreach ($args['input']['fee_data'] as $datum) {
            if (empty($datum['fee_id'])) {
                throw new GraphQlInputException(__('Required parameter "fee_id" is missing'));
            }
            if (!isset($datum['fee_option_ids'])) {
                throw new GraphQlInputException(__('Required parameter "fee_option_ids" is missing'));
            }
            /** @var FeeDataInterface $feeDataObject */
            $feeDataObject = $this->feeDataFactory->create();
            $feeDataObject
                ->setId($datum['fee_id'])
                ->setOptions(implode(',', $datum['fee_option_ids']));

            $objects[] = $feeDataObject;
        }

        if (count($objects) === 1) {
            return $objects[0];
        }

        return $this->feeDataFactory->create()->setMultipleData($objects);
    }

    /**
     * @param array|null $args
     * @throws GraphQlInputException
     */
    protected function validateParameters(array $args = null): void
    {
        if (empty($args['input']['cart_id'])) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing'));
        }

        if (empty($args['input']['fee_data'])) {
            throw new GraphQlInputException(__('Required parameter "fee_data" is missing'));
        }
    }
}
