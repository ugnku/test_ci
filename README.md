# EcommPay PHP SDK

![CI status](https://github.com/ugnku/test_ci/actions/workflows/ci.yml/badge.svg)
![Language](https://img.shields.io/github/languages/top/ugnku/test_ci)
![Code Size](https://img.shields.io/github/languages/code-size/ugnku/test_ci)
![Build status](https://img.shields.io/github/workflow/status/ugnku/test_ci/Test%20suite/5.6)
[![Coverage Status](https://coveralls.io/repos/github/ugnku/test_ci/badge.svg?branch=5.6)](https://coveralls.io/github/ugnku/test_ci?branch=5.6)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/3e9709207821477f8412081ecdf4b7a4)](https://www.codacy.com/gh/ugnku/test_ci/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ugnku/test_ci&amp;utm_campaign=Badge_Grade)
![Release](https://img.shields.io/github/v/release/ugnku/test_ci)
![Release Date](https://img.shields.io/github/release-date/ugnku/test_ci/master)

This is a set of libraries in the PHP language to ease integration of your service
with the EcommPay Payment Page.

Please note that for correct SDK operating you must have at least PHP 7.0.  

## Payment flow

![Payment flow](flow.png)

## Installation

Install with composer
```bash
composer require ecommpay/paymentpage-sdk
```

### Get URL for payment

```php
$gate = new ecommpay\Gate('secret');
$payment = new ecommpay\Payment('11', 'some payment id');
$payment->setPaymentAmount(1000)->setPaymentCurrency('RUB');
$url = $gate->getPurchasePaymentPageUrl($payment);
``` 

`$url` here is the signed URL.

If you want to use another domain for URL you can change it with optional `Gate` constructor parameter:
```php
new ecommpay\Gate('secret', 'https://mydomain.com/payment');
```
or change it with method 
```php
$gate->setPaymentBaseUrl('https://mydomain.com/payment');
```

### Handle callback from Ecommpay

You'll need to autoload this code in order to handle notifications:

```php
$gate = new ecommpay\Gate('secret');
$callback = $gate->handleCallback($data);
```

`$data` is the JSON data received from payment system;

`$callback` is the Callback object describing properties received from payment system;
`$callback` implements these methods: 
1.  `Callback::getPaymentStatus()` -  Get payment status.
2.  `Callback::getPayment()` - Get all payment data.
3.  `Callback::getPaymentId()` - Get payment ID in your system.
    
### TODO
-   [x] Payment Page opening 
-   [x] Notifications handling
-   [ ] Direct Gate requests
-   [ ] PHPDoc
