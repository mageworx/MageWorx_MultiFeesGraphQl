# MageWorx_MultiFeesGraphQl

GraphQL API module for Mageworx [Magento 2 Multi Fees](https://www.mageworx.com/magento-2-extra-product-fees.html) extension.

## Installation

**1) Installation using composer (from packagist)**
- Execute the following command: `composer require mageworx/module-multifeesgraphql`

**2) Copy-to-paste method**
- Download this module and upload it to the `app/code/MageWorx/MultiFeesGraphQl` directory *(create "MultiFeesGraphQl" first if missing)*

## How to use

### Add cart fees to the shopping cart

**addMwCartFeesToCart** mutation is used to add Cart Fees to the shopping cart.  
  
**Syntax**  
`addMwCartFeesToCart(input: AddMwFeesToCartInput): AddMwFeesToCartOutput`

The AddMwFeesToCartInput object may contain the following attributes:
```
cart_id: String! @doc(description:"The unique ID that identifies the customer's cart")
fee_data: [MwFeeDataInput!]!
```

The MwFeeDataInput object may contain the following attributes:
```
fee_id: Int! @doc(description: "Fee ID")
fee_option_ids: [Int!]! @doc(description: "An array of fee option IDs")
message: String
date: String
```

The AddMwFeesToCartOutput object contains the Cart object.  
  
**Request:**
```
mutation {
    addMwCartFeesToCart(
        input: {
            cart_id: "yoSUj6X411aAMMqALERGxnYQsY73iQvM"
            fee_data: [
                {
                    fee_id: 1
                    fee_option_ids: [1,2]
                },
                {
                    fee_id: 5
                    fee_option_ids: [6],
                    message: "tt-test mes",
                    date: "06/24/2023"
                }
            ]
        }
    ) {
        cart {
            mw_applied_quote_fees {
                id
                title
                type
                customer_message
                date
                price {
                    value
                    currency
                }
                tax {
                    value
                    currency
                }
                options {
                    id
                    title
                    price {
                        value
                        currency
                    }
                    tax {
                        value
                        currency
                    }
                }
            }
        }
    }
}
```

**Response:**
```
{
    "data": {
        "addMwCartFeesToCart": {
            "cart": {
                "mw_applied_quote_fees": [
                    {
                        "id": 1,
                        "title": "Cart Fee",
                        "type": "CART",
                        "customer_message": "",
                        "date": "",
                        "price": {
                            "value": 90,
                            "currency": "USD"
                        },
                        "tax": {
                            "value": 0,
                            "currency": "USD"
                        },
                        "options": [
                            {
                                "id": 1,
                                "title": "option +10",
                                "price": {
                                    "value": 30,
                                    "currency": "USD"
                                },
                                "tax": {
                                    "value": 0,
                                    "currency": "USD"
                                }
                            },
                            {
                                "id": 2,
                                "title": "option +20",
                                "price": {
                                    "value": 60,
                                    "currency": "USD"
                                },
                                "tax": {
                                    "value": 0,
                                    "currency": "USD"
                                }
                            }
                        ]
                    },
                    {
                        "id": 5,
                        "title": "Cart Fee 2",
                        "type": "CART",
                        "customer_message": "tt-test mes",
                        "date": "06/24/2023",
                        "price": {
                            "value": 10,
                            "currency": "USD"
                        },
                        "tax": {
                            "value": 0,
                            "currency": "USD"
                        },
                        "options": [
                            {
                                "id": 6,
                                "title": "op1",
                                "price": {
                                    "value": 10,
                                    "currency": "USD"
                                },
                                "tax": {
                                    "value": 0,
                                    "currency": "USD"
                                }
                            }
                        ]
                    }
                ]
            }
        }
    }
}
```

### Add shipping fees to the shopping cart

**addMwShippingFeesToCart** mutation is used to add Shipping Fees to the shopping cart.  
  
**Syntax**  
`addMwShippingFeesToCart(input: AddMwFeesToCartInput): AddMwFeesToCartOutput`
  
The requiest is similar to the mutation of addMwCartFeesToCart.  
  
### Add payment fees to the shopping cart. 

**addMwPaymentFeesToCart** mutation is used to add Payment Fees to the shopping cart.  
  
**Syntax**  
`addMwPaymentFeesToCart(input: AddMwFeesToCartInput): AddMwFeesToCartOutput`
  
The requiest is similar to the mutation of addMwCartFeesToCart.  
  
### Add product fees to the shopping cart

**addMwProductFeesToCart** mutation is used to add Product Fees to the shopping cart.  
  
**Syntax**  
`addMwProductFeesToCart(input: AddMwProductFeesToCartInput): AddMwFeesToCartOutput`

The AddMwProductFeesToCartInput object may contain the following attributes:
```
cart_id: String! @doc(description:"The unique ID that identifies the customer's cart")
fee_data: [MwFeeDataInput!]!
quote_item_id: Int! @doc(description: "Quote Item ID")
```

**Request:**
```
mutation {
    addMwProductFeesToCart(
        input: {
            cart_id: "yoSUj6X411aAMMqALERGxnYQsY73iQvM"
            quote_item_id: 25
            fee_data: [
                {
                    fee_id: 6
                    fee_option_ids: [8]
                }
            ]
        }
    ) {
        cart {
            mw_applied_product_fees {
                quote_item_id
                fees {
                    id
                    title
                    type
                    customer_message
                    date
                    price {
                        value
                        currency
                    }
                    tax {
                        value
                        currency
                    }
                    options {
                        id
                        title
                        price {
                            value
                            currency
                        }
                        tax {
                            value
                            currency
                        }
                    }
                }
            }
        }
    }
}
```

**Response:**
```
{
    "data": {
        "addMwProductFeesToCart": {
            "cart": {
                "mw_applied_product_fees": [
                    {
                        "quote_item_id": 25,
                        "fees": [
                            {
                                "id": 6,
                                "title": "Product Fee 2",
                                "type": "PRODUCT",
                                "customer_message": "",
                                "date": "",
                                "price": {
                                    "value": 1,
                                    "currency": "USD"
                                },
                                "tax": {
                                    "value": 0,
                                    "currency": "USD"
                                },
                                "options": [
                                    {
                                        "id": 8,
                                        "title": "op1",
                                        "price": {
                                            "value": 1,
                                            "currency": "USD"
                                        },
                                        "tax": {
                                            "value": 0,
                                            "currency": "USD"
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        }
    }
}
```
### Additional data in Cart object

The following attributes for Cart are added:
```
mw_applied_quote_fees: [MwAppliedFee] @doc(description: "Applied Quote Fees")
mw_applied_product_fees: [MwAppliedProductFee] @doc(description: "Applied Product Fees")
mw_cart_fees: [MwFee] @doc(description: "Cart Fees")
mw_shipping_fees: [MwFee] @doc(description: "Shipping Fees")
mw_payment_fees: [MwFee] @doc(description: "Payment Fees")
mw_product_fees: [MwProductFee] @doc(description: "Product Fees")
```

MwAppliedFee attributes:
```
id: Int @doc(description: "Fee ID")
title: String @doc(description: "Fee Title")
type: MwFeeTypeEnum @doc(description: "Either CART, SHIPPING, PAYMENT or PRODUCT")
customer_message: String @doc(description: "Customer Message")
date: String @doc(description: "Date")
price: Money
tax: Money
options: [MwAppliedFeeOption]
```

MwAppliedFeeOption attributes:
```
id: Int @doc(description: "Option ID")
title: String @doc(description: "Option Title")
price: Money
tax: Money
```

MwAppliedProductFee attributes:
```
quote_item_id: Int @doc(description: "Quote Item ID")
fees: [MwAppliedFee] @doc(description: "Product Fees")
```

MwFee attributes:
```
id: Int @doc(description: "Fee ID")
title: String @doc(description: "Fee Title")
description: String @doc(description: "Fee Description")
input_type: String @doc(description: "Fee Input Type")
is_required: Boolean @doc(description: "Indicates whether the fee is required")
is_enabled_customer_message: Boolean @doc(description: "Indicates whether the customer message field is enabled")
customer_message_title: String @doc(description: "Customer Message Field Title")
is_enabled_date_field: Boolean @doc(description: "Indicates whether the date field is enabled")
date_field_title: String @doc(description: "Date Field Title")
sort_order: Int @doc(description: "Fee Sort Order")
options: [MwFeeOption] @doc(description: "An array of fee options")
```

MwFeeOption attributes:
```
id: Int @doc(description: "Option ID")
field_label: String @doc(description: "Option Field Label")
is_default: Boolean @doc(description: "Indicates whether the given option is the default option")
position: Int @doc(description: "Option Position")
title: String @doc(description: "Option Title")
price_type: String @doc(description: "Option Price Type")
price: Float @doc(description: "Option Price")
```

MwProductFee attributes:
```
quote_item_id: Int @doc(description: "Quote Item ID")
fees: [MwFee] @doc(description: "Product Fees")
```

**Request:**
```
{
    customerCart {
        items {
            id
            product {
                name
                sku
            }
            quantity
        }
        mw_applied_quote_fees {
            id
            title
            type
            customer_message
            date
            price {
                value
                currency
            }
            tax {
                value
                currency
            }
            options {
                id
                title
                price {
                    value
                    currency
                }
                tax {
                    value
                    currency
                }
            }
        }
        mw_applied_product_fees {
            quote_item_id
            fees {
                id
                title
                type
                customer_message
                date
                price {
                    value
                    currency
                }
                tax {
                    value
                    currency
                }
                options {
                    id
                    title
                    price {
                        value
                        currency
                    }
                    tax {
                        value
                        currency
                    }
                }
            }
        }
        mw_cart_fees {
            id
            title
            description
            input_type
            is_required
            is_enabled_customer_message
            customer_message_title
            is_enabled_date_field
            date_field_title
            sort_order
            options {
                id
                field_label
                is_default
                position
                title
                price_type
                price
            }
        }
        mw_shipping_fees {
            id
            title
            description
            input_type
            is_required
            is_enabled_customer_message
            customer_message_title
            is_enabled_date_field
            date_field_title
            sort_order
            options {
                id
                field_label
                is_default
                position
                title
                price_type
                price
            }
        }
        mw_payment_fees {
            id
            title
            description
            input_type
            is_required
            is_enabled_customer_message
            customer_message_title
            is_enabled_date_field
            date_field_title
            sort_order
            options {
                id
                field_label
                is_default
                position
                title
                price_type
                price
            }
        }
        mw_product_fees {
            quote_item_id
            fees {
                id
                title
                description
                input_type
                is_required
                is_enabled_customer_message
                customer_message_title
                is_enabled_date_field
                date_field_title
                sort_order
                options {
                    id
                    field_label
                    is_default
                    position
                    title
                    price_type
                    price
                }
            }
        }
    }
}
```

**Response:**
```
{
    "data": {
        "customerCart": {
            "items": [
                {
                    "id": "25",
                    "product": {
                        "name": "Fusion Backpack",
                        "sku": "24-MB02"
                    },
                    "quantity": 2
                },
                {
                    "id": "34",
                    "product": {
                        "name": "Push It Messenger Bag",
                        "sku": "24-WB04"
                    },
                    "quantity": 1
                }
            ],
            "mw_applied_quote_fees": [
                {
                    "id": 3,
                    "title": "Shipping Fee",
                    "type": "SHIPPING",
                    "customer_message": "",
                    "date": "",
                    "price": {
                        "value": 10,
                        "currency": "USD"
                    },
                    "tax": {
                        "value": 0,
                        "currency": "USD"
                    },
                    "options": [
                        {
                            "id": 4,
                            "title": "option +10",
                            "price": {
                                "value": 10,
                                "currency": "USD"
                            },
                            "tax": {
                                "value": 0,
                                "currency": "USD"
                            }
                        }
                    ]
                },
                {
                    "id": 4,
                    "title": "Payment Fee",
                    "type": "PAYMENT",
                    "customer_message": "",
                    "date": "",
                    "price": {
                        "value": 30,
                        "currency": "USD"
                    },
                    "tax": {
                        "value": 0,
                        "currency": "USD"
                    },
                    "options": [
                        {
                            "id": 5,
                            "title": "option +10",
                            "price": {
                                "value": 30,
                                "currency": "USD"
                            },
                            "tax": {
                                "value": 0,
                                "currency": "USD"
                            }
                        }
                    ]
                },
                {
                    "id": 5,
                    "title": "Cart Fee 2",
                    "type": "CART",
                    "customer_message": "",
                    "date": "",
                    "price": {
                        "value": 19.56,
                        "currency": "USD"
                    },
                    "tax": {
                        "value": 0,
                        "currency": "USD"
                    },
                    "options": [
                        {
                            "id": 7,
                            "title": "op2",
                            "price": {
                                "value": 19.56,
                                "currency": "USD"
                            },
                            "tax": {
                                "value": 0,
                                "currency": "USD"
                            }
                        }
                    ]
                },
                {
                    "id": 1,
                    "title": "Cart Fee",
                    "type": "CART",
                    "customer_message": "",
                    "date": "",
                    "price": {
                        "value": 90,
                        "currency": "USD"
                    },
                    "tax": {
                        "value": 0,
                        "currency": "USD"
                    },
                    "options": [
                        {
                            "id": 1,
                            "title": "option +10",
                            "price": {
                                "value": 30,
                                "currency": "USD"
                            },
                            "tax": {
                                "value": 0,
                                "currency": "USD"
                            }
                        },
                        {
                            "id": 2,
                            "title": "option +20",
                            "price": {
                                "value": 60,
                                "currency": "USD"
                            },
                            "tax": {
                                "value": 0,
                                "currency": "USD"
                            }
                        }
                    ]
                }
            ],
            "mw_applied_product_fees": [
                {
                    "quote_item_id": 25,
                    "fees": [
                        {
                            "id": 2,
                            "title": "Product Fee",
                            "type": "PRODUCT",
                            "customer_message": "",
                            "date": "",
                            "price": {
                                "value": 12,
                                "currency": "USD"
                            },
                            "tax": {
                                "value": 0,
                                "currency": "USD"
                            },
                            "options": [
                                {
                                    "id": 3,
                                    "title": "option +12",
                                    "price": {
                                        "value": 12,
                                        "currency": "USD"
                                    },
                                    "tax": {
                                        "value": 0,
                                        "currency": "USD"
                                    }
                                }
                            ]
                        }
                    ]
                },
                {
                    "quote_item_id": 34,
                    "fees": [
                        {
                            "id": 2,
                            "title": "Product Fee",
                            "type": "PRODUCT",
                            "customer_message": "",
                            "date": "",
                            "price": {
                                "value": 12,
                                "currency": "USD"
                            },
                            "tax": {
                                "value": 0,
                                "currency": "USD"
                            },
                            "options": [
                                {
                                    "id": 3,
                                    "title": "option +12",
                                    "price": {
                                        "value": 12,
                                        "currency": "USD"
                                    },
                                    "tax": {
                                        "value": 0,
                                        "currency": "USD"
                                    }
                                }
                            ]
                        }
                    ]
                }
            ],
            "mw_cart_fees": [
                {
                    "id": 1,
                    "title": "Cart Fee",
                    "description": "Cart Fee Description",
                    "input_type": "checkbox",
                    "is_required": false,
                    "is_enabled_customer_message": false,
                    "customer_message_title": "",
                    "is_enabled_date_field": false,
                    "date_field_title": "",
                    "sort_order": 0,
                    "options": [
                        {
                            "id": 1,
                            "field_label": "option +10-$30.00",
                            "is_default": true,
                            "position": 10,
                            "title": "option +10",
                            "price_type": "fixed",
                            "price": 10
                        },
                        {
                            "id": 2,
                            "field_label": "option +20-$60.00",
                            "is_default": true,
                            "position": 20,
                            "title": "option +20",
                            "price_type": "fixed",
                            "price": 20
                        }
                    ]
                },
                {
                    "id": 5,
                    "title": "Cart Fee 2",
                    "description": "Description Cart Fee  2",
                    "input_type": "radio",
                    "is_required": true,
                    "is_enabled_customer_message": true,
                    "customer_message_title": "Customer Message Title 2",
                    "is_enabled_date_field": true,
                    "date_field_title": "Date Field Title 2",
                    "sort_order": 10,
                    "options": [
                        {
                            "id": 6,
                            "field_label": "op1-$10.00",
                            "is_default": false,
                            "position": 10,
                            "title": "op1",
                            "price_type": "fixed",
                            "price": 10
                        },
                        {
                            "id": 7,
                            "field_label": "op2-12.00%",
                            "is_default": true,
                            "position": 12,
                            "title": "op2",
                            "price_type": "percent",
                            "price": 12
                        }
                    ]
                }
            ],
            "mw_shipping_fees": [
                {
                    "id": 3,
                    "title": "Shipping Fee",
                    "description": "Shipping Fee Description",
                    "input_type": "radio",
                    "is_required": false,
                    "is_enabled_customer_message": false,
                    "customer_message_title": "",
                    "is_enabled_date_field": false,
                    "date_field_title": "",
                    "sort_order": 0,
                    "options": [
                        {
                            "id": 4,
                            "field_label": "option +10-$10.00",
                            "is_default": false,
                            "position": 10,
                            "title": "option +10",
                            "price_type": "fixed",
                            "price": 10
                        }
                    ]
                }
            ],
            "mw_payment_fees": [
                {
                    "id": 4,
                    "title": "Payment Fee",
                    "description": "Payment Fee Description",
                    "input_type": "checkbox",
                    "is_required": false,
                    "is_enabled_customer_message": false,
                    "customer_message_title": "",
                    "is_enabled_date_field": false,
                    "date_field_title": "",
                    "sort_order": 0,
                    "options": [
                        {
                            "id": 5,
                            "field_label": "option +10-$30.00",
                            "is_default": true,
                            "position": 10,
                            "title": "option +10",
                            "price_type": "fixed",
                            "price": 10
                        }
                    ]
                }
            ],
            "mw_product_fees": [
                {
                    "quote_item_id": 25,
                    "fees": [
                        {
                            "id": 2,
                            "title": "Product Fee",
                            "description": "Product Fee Description",
                            "input_type": "hidden",
                            "is_required": true,
                            "is_enabled_customer_message": true,
                            "customer_message_title": "Customer Message Title",
                            "is_enabled_date_field": true,
                            "date_field_title": "Date Field Title",
                            "sort_order": 0,
                            "options": [
                                {
                                    "id": 3,
                                    "field_label": "option +12-$12.00",
                                    "is_default": true,
                                    "position": 12,
                                    "title": "option +12",
                                    "price_type": "fixed",
                                    "price": 12
                                }
                            ]
                        },
                        {
                            "id": 6,
                            "title": "Name for Default Store View",
                            "description": "Description for Default Store View",
                            "input_type": "drop_down",
                            "is_required": false,
                            "is_enabled_customer_message": false,
                            "customer_message_title": "Customer Message Title for Default Store View",
                            "is_enabled_date_field": false,
                            "date_field_title": "Date Field Title for Default Store View",
                            "sort_order": 0,
                            "options": [
                                {
                                    "id": 8,
                                    "field_label": "op1-$1.00",
                                    "is_default": false,
                                    "position": 1,
                                    "title": "op1",
                                    "price_type": "fixed",
                                    "price": 1
                                },
                                {
                                    "id": 9,
                                    "field_label": "op2-$2.00",
                                    "is_default": false,
                                    "position": 2,
                                    "title": "op2",
                                    "price_type": "fixed",
                                    "price": 2
                                }
                            ]
                        }
                    ]
                },
                {
                    "quote_item_id": 34,
                    "fees": [
                        {
                            "id": 2,
                            "title": "Product Fee",
                            "description": "Product Fee Description",
                            "input_type": "hidden",
                            "is_required": true,
                            "is_enabled_customer_message": true,
                            "customer_message_title": "Customer Message Title",
                            "is_enabled_date_field": true,
                            "date_field_title": "Date Field Title",
                            "sort_order": 0,
                            "options": [
                                {
                                    "id": 3,
                                    "field_label": "option +12-$12.00",
                                    "is_default": true,
                                    "position": 12,
                                    "title": "option +12",
                                    "price_type": "fixed",
                                    "price": 12
                                }
                            ]
                        },
                        {
                            "id": 6,
                            "title": "Name for Default Store View",
                            "description": "Description for Default Store View",
                            "input_type": "drop_down",
                            "is_required": false,
                            "is_enabled_customer_message": false,
                            "customer_message_title": "Customer Message Title for Default Store View",
                            "is_enabled_date_field": false,
                            "date_field_title": "Date Field Title for Default Store View",
                            "sort_order": 0,
                            "options": [
                                {
                                    "id": 8,
                                    "field_label": "op1-$1.00",
                                    "is_default": false,
                                    "position": 1,
                                    "title": "op1",
                                    "price_type": "fixed",
                                    "price": 1
                                },
                                {
                                    "id": 9,
                                    "field_label": "op2-$2.00",
                                    "is_default": false,
                                    "position": 2,
                                    "title": "op2",
                                    "price_type": "fixed",
                                    "price": 2
                                }
                            ]
                        }
                    ]
                }
            ]
        }
    }
}
```

### Additional data in ProductInterface

The following attribute for ProductInterface is added:
```
mw_product_hidden_fees: [MwFee] @doc(description: "Product Hidden Fees")
```

**Request:**
```
{
    products(filter: {sku: {eq: "24-MB02"}}) {
        items {
            __typename
            name    
            sku
            mw_product_hidden_fees {
                id
                title
                description
                sort_order
                options {
                    id
                    field_label
                    position
                    title
                    price_type
                    price
                }
            }
        }
    }
}
```

**Response:**
```
{
    "data": {
        "products": {
            "items": [
                {
                    "__typename": "SimpleProduct",
                    "name": "Fusion Backpack",
                    "sku": "24-MB02",
                    "mw_product_hidden_fees": [
                        {
                            "id": 2,
                            "title": "Product Fee",
                            "description": "Product Fee Description",
                            "sort_order": 0,
                            "options": [
                                {
                                    "id": 3,
                                    "field_label": "option +12 - $12.00",
                                    "position": 1,
                                    "title": "option +12",
                                    "price_type": "fixed",
                                    "price": 12
                                }
                            ]
                        },
                        {
                            "id": 8,
                            "title": "prod fee 3 hidden",
                            "description": "",
                            "sort_order": 0,
                            "options": [
                                {
                                    "id": 12,
                                    "field_label": "op1 - $3.00",
                                    "position": 1,
                                    "title": "op1",
                                    "price_type": "fixed",
                                    "price": 3
                                }
                            ]
                        },
                        {
                            "id": 7,
                            "title": "Product Fee hidden 2",
                            "description": "Description for Product Fee hidden 2",
                            "sort_order": 10,
                            "options": [
                                {
                                    "id": 13,
                                    "field_label": "op1 - $12.00",
                                    "position": 1,
                                    "title": "op1",
                                    "price_type": "fixed",
                                    "price": 12
                                },
                                {
                                    "id": 11,
                                    "field_label": "op2 - 10.00%",
                                    "position": 2,
                                    "title": "op2",
                                    "price_type": "percent",
                                    "price": 10
                                }
                            ]
                        }
                    ]
                }
            ]
        }
    }
}
```

### Additional data in CartItemInterface

The following attribute for CartItemInterface is added:
```
mw_applied_product_fees: [MwAppliedFee] @doc(description: "Applied Product Fees")
```

**Request:**
```
{
    cart(cart_id: "RCBVPrmgjDnNYin5RLhXxvHOp3sYTZkv") {
        items {
            id
            product {
                name
                sku
            }
            quantity
            mw_applied_product_fees {
                id
                title
                type
                customer_message
                date
                price {
                    value
                    currency
                }
                tax {
                    value
                    currency
                }
                options {
                    id
                    title
                    price {
                        value
                        currency
                    }
                    tax {
                        value
                        currency
                    }
                }
            }
        }
    }
}
```

**Response:**
```
{
    "data": {
        "cart": {
            "items": [
                {
                    "id": "47",
                    "product": {
                        "name": "Fusion Backpack",
                        "sku": "24-MB02"
                    },
                    "quantity": 1,
                    "mw_applied_product_fees": [
                        {
                            "id": 2,
                            "title": "Product Fee",
                            "type": "PRODUCT",
                            "customer_message": "",
                            "date": "",
                            "price": {
                                "value": 12,
                                "currency": "USD"
                            },
                            "tax": {
                                "value": 0,
                                "currency": "USD"
                            },
                            "options": [
                                {
                                    "id": 3,
                                    "title": "option +12",
                                    "price": {
                                        "value": 12,
                                        "currency": "USD"
                                    },
                                    "tax": {
                                        "value": 0,
                                        "currency": "USD"
                                    }
                                }
                            ]
                        },
                        {
                            "id": 8,
                            "title": "prod fee 3 hidden",
                            "type": "PRODUCT",
                            "customer_message": "",
                            "date": "",
                            "price": {
                                "value": 3,
                                "currency": "USD"
                            },
                            "tax": {
                                "value": 0,
                                "currency": "USD"
                            },
                            "options": [
                                {
                                    "id": 12,
                                    "title": "op1",
                                    "price": {
                                        "value": 3,
                                        "currency": "USD"
                                    },
                                    "tax": {
                                        "value": 0,
                                        "currency": "USD"
                                    }
                                }
                            ]
                        },
                        {
                            "id": 7,
                            "title": "Product Fee hidden 2",
                            "type": "PRODUCT",
                            "customer_message": "",
                            "date": "",
                            "price": {
                                "value": 17.9,
                                "currency": "USD"
                            },
                            "tax": {
                                "value": 0,
                                "currency": "USD"
                            },
                            "options": [
                                {
                                    "id": 13,
                                    "title": "op1",
                                    "price": {
                                        "value": 12,
                                        "currency": "USD"
                                    },
                                    "tax": {
                                        "value": 0,
                                        "currency": "USD"
                                    }
                                },
                                {
                                    "id": 11,
                                    "title": "op2",
                                    "price": {
                                        "value": 5.9,
                                        "currency": "USD"
                                    },
                                    "tax": {
                                        "value": 0,
                                        "currency": "USD"
                                    }
                                }
                            ]
                        }
                    ]
                },
                {
                    "id": "48",
                    "product": {
                        "name": "Radiant Tee",
                        "sku": "WS12"
                    },
                    "quantity": 1,
                    "mw_applied_product_fees": [
                        {
                            "id": 2,
                            "title": "Product Fee",
                            "type": "PRODUCT",
                            "customer_message": "",
                            "date": "",
                            "price": {
                                "value": 12,
                                "currency": "USD"
                            },
                            "tax": {
                                "value": 0,
                                "currency": "USD"
                            },
                            "options": [
                                {
                                    "id": 3,
                                    "title": "option +12",
                                    "price": {
                                        "value": 12,
                                        "currency": "USD"
                                    },
                                    "tax": {
                                        "value": 0,
                                        "currency": "USD"
                                    }
                                }
                            ]
                        },
                        {
                            "id": 8,
                            "title": "prod fee 3 hidden",
                            "type": "PRODUCT",
                            "customer_message": "",
                            "date": "",
                            "price": {
                                "value": 3,
                                "currency": "USD"
                            },
                            "tax": {
                                "value": 0,
                                "currency": "USD"
                            },
                            "options": [
                                {
                                    "id": 12,
                                    "title": "op1",
                                    "price": {
                                        "value": 3,
                                        "currency": "USD"
                                    },
                                    "tax": {
                                        "value": 0,
                                        "currency": "USD"
                                    }
                                }
                            ]
                        }
                    ]
                }
            ]
        }
    }
}
```

### Additional data in StoreConfig object

The following attributes for StoreConfig are added:
```
mw_multi_fees_is_enabled: Boolean @doc(description: "Indicates whether the MultiFees functionality is enabled")
mw_display_product_fee_on_product_page_is_enabled: Boolean @doc(description: "Indicates whether the product fee display is enabled on the product page")
mw_fees_block_label_on_product_page: String @doc(description: "The label for the fees block that is displayed on the product page")
mw_product_fees_position: MwHiddenFeePositionEnum @doc(description: "Either BELOW_ADD_TO_CART or ABOVE_ADD_TO_CART")
mw_apply_fee_on_click_is_enabled: Boolean @doc(description: "Indicates whether to apply fee on click")
mw_multi_fees_tax_calculation_type: MwFeeTaxCalculationTypeEnum @doc(description: "Either EXCLUDING_TAX or INCLUDING_TAX")
mw_prices_type_in_additional_fees_block: MwFeeAmountTypeEnum @doc(description: "Either EXCLUDING_TAX, INCLUDING_TAX or INCLUDING_AND_EXCLUDING_TAX")
mw_additional_fees_amount_type_in_cart_total: MwFeeAmountTypeEnum @doc(description: "Either EXCLUDING_TAX, INCLUDING_TAX or INCLUDING_AND_EXCLUDING_TAX")
```
  
**Request:**
```
{
  storeConfig {
    store_code
    store_name
    mw_multi_fees_is_enabled
    mw_display_product_fee_on_product_page_is_enabled
    mw_fees_block_label_on_product_page
    mw_product_fees_position
    mw_apply_fee_on_click_is_enabled
    mw_multi_fees_tax_calculation_type
    mw_prices_type_in_additional_fees_block
    mw_additional_fees_amount_type_in_cart_total
  }
}
```
   
**Response:**
```
{
    "data": {
        "storeConfig": {
            "store_code": "default",
            "store_name": "Default Store View",
            "mw_multi_fees_is_enabled": true,
            "mw_display_product_fee_on_product_page_is_enabled": true,
            "mw_fees_block_label_on_product_page": "Additional Product Fees",
            "mw_product_fees_position": "BELOW_ADD_TO_CART",
            "mw_apply_fee_on_click_is_enabled": false,
            "mw_multi_fees_tax_calculation_type": "EXCLUDING_TAX",
            "mw_prices_type_in_additional_fees_block": "INCLUDING_AND_EXCLUDING_TAX",
            "mw_additional_fees_amount_type_in_cart_total": "INCLUDING_AND_EXCLUDING_TAX"
        }
    }
}
```
