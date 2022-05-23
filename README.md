## Table of Contents

-   [Table of Contents](#table-of-contents)
-   [Intro](#intro)
-   [Disclaimer](#disclaimer)
-   [Installing](#installing)
    -   [Requirements](#requirements)
    -   [Composer](#composer)
-   [Getting Started](#getting-started)
-   [References](#references)
    -   [Beneficiaries](#beneficiaries)
        -   [1.0 Get list beneficiaries](#10-get-list-beneficiaries)
        -   [1.2 Create beneficiary](#12-create-beneficiary)
        -   [1.3 Update beneficiary](#13-update-beneficiary)
    -   [Bank Accounts](#bank-accounts)
        -   [2.0 Get bank account balance](#20-get-bank-account-balance)

## Intro

The Midtrans Iris PHP library is a API client library to communicate with the Midtrans Iris API mainly for disbursement & payout things.

## Disclaimer

> NOTE: This library is only tested with the "aggregator" scheme although the API is pretty much complete. There are also a "facilitator" scheme, but it requires to have an account specific to that scheme to be able to test this library with the API key. For more information about the schemes please visit [https://iris-docs.midtrans.com/#business-flow](https://iris-docs.midtrans.com/#business-flow).

## Installing

### Requirements

PHP 7.2.34 and later.

### Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require timedoor/tmd-midtrans-iris
```

To use the library, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Getting Started

The basic usage looks like:

```php
$iris = new \Timedoor\TmdMidtransIris\Iris([
    'approver_api_key'  => 'your_api_key',
    'creator_api_key'   => 'your_api_key',
    'merchant_key'      => 'your_merchant_key'
]);

$beneficiaries = $iris->beneficiaries()->all();
var_dump($beneficiaries);
```

## References

For every service that you use, they might throw some exceptions like `UnauthorizedRequestException`, `BadRequestException` or `GeneralException`, so make sure you handle the exceptions that may occur.

> All examples will assume that you've already setup iris and have the iris instance object named `$iris`.

> Every `models` and `dtos` extends from `\Timedoor\TmdMidtransIris\Utils\DataMapper` which will allow you to construct your `models` and `dtos` from an array aside from using the `setter` methods.

### Beneficiaries

For every beneficiary, the `alias_name` will be used as a unique identifier. So make sure use a unique value for the `alias_name`.

#### 1.0 Get list beneficiaries

**Example**

```php
$beneficiaries = $iris->beneficiary()->all();
```

#### 1.2 Create beneficiary

**Example**

```php
$request = \Timedoor\TmdMidtransIris\Models\Beneficiary::fromArray([
    'name'          => 'John Doe',
    'bank'          => 'bca',
    'account'       => '141414141414',
    'alias_name'    => 'johndoe1',
    'email'         => 'john@example.com'
]);
```

You could also use `setters` to construct your request model, like:

```php
$request = (new \Timedoor\TmdMidtransIris\Models\Beneficiary)
            ->setName('John Doe')
            ->setBank('bca');
```

**Creating the beneficiary**

```php
$result = $iris->beneficiary()->create($request);
var_dump($result) // ['status' => 'created']
```

#### 1.3 Update beneficiary

Similar with creating beneficiary, but this will require one more attribute which is `alias_name`. Assuming the beneficiary you want to update will have `alias_name` of `johndoe1`

```php
$request = \Timedoor\TmdMidtransIris\Models\Beneficiary::fromArray([
    'name'          => 'Jane Doe',
    'bank'          => 'mandiri',
    'account'       => '1515151515',
    'alias_name'    => 'janedoe',
    'email'         => 'jane.doe@example.com'
]);

$result = $iris->beneficiary()->update('johndoe1', $request);
var_dump($result) // ['status' => 'updated']
```

### Bank Accounts

For bank accounts there will be some terms used in this scenario which is `facilitator` and `aggregator`. You can read more about it on [https://iris-docs.midtrans.com/#business-flow](https://iris-docs.midtrans.com/#business-flow).

> WARNING: Only **_Aggregator_** scheme was tested with integration test with the Iris API.

Bank account service will be seperated into 3 services:

-   **_Base_** bank account service
-   **_Aggregator_** specific bank account service
-   **_Facilitator_** specific bank account service

#### 2.0 Get bank account balance

**_Base_**

```php
$balance = $iris->bankAccount()->balance();
var_dump($balance); // int(10000) default is 0
```

**_Aggregator_**

Similar with getting account balance with base bank account service, you just need to add a `AccountType` parameter, for example:

```php
$balance = $iris->bankAccount(\Timedoor\TmdMidtransIris\AccountType::AGGREGATOR)->balance();
```

**_Facilitator_**

Just change the parameter to `\Timedoor\TmdMidtransIris\AccountType::FACILITATOR`

```php
$balance = $iris->bankAccount(\Timedoor\TmdMidtransIris\AccountType::FACILITATOR)->balance();
```
