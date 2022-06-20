## Table of Contents

- [Table of Contents](#table-of-contents)
- [Intro](#intro)
- [Disclaimer](#disclaimer)
- [Installing](#installing)
  - [Requirements](#requirements)
  - [Composer](#composer)
- [Getting Started](#getting-started)
- [References](#references)
  - [Beneficiaries](#beneficiaries)
    - [1.0 Get list beneficiaries](#10-get-list-beneficiaries)
    - [1.1 Create beneficiary](#11-create-beneficiary)
    - [1.2 Update beneficiary](#12-update-beneficiary)
  - [Bank Accounts](#bank-accounts)
    - [2.0 Get bank account balance](#20-get-bank-account-balance)
    - [2.1 Get bank account list (facilitator)](#21-get-bank-account-list-facilitator)
    - [2.2 Validate bank account](#22-validate-bank-account)
    - [2.3 Supported Banks](#23-supported-banks)
  - [Payouts](#payouts)
    - [2.0 Create payout(s)](#20-create-payouts)
    - [2.1 Approve payout(s)](#21-approve-payouts)
    - [2.2 Reject payout(s)](#22-reject-payouts)
    - [2.3 Get payout detail](#23-get-payout-detail)
  - [Transaction History](#transaction-history)
  - [Top Up Channel Information](#top-up-channel-information)
  - [Laravel Support](#laravel-support)

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

For every beneficiary, the `alias_name` will be used as a unique identifier. So make sure to use a unique value for the `alias_name`.

#### 1.0 Get list beneficiaries

**Example**

```php
$beneficiaries = $iris->beneficiary()->all();
```

#### 1.1 Create beneficiary

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

#### 1.2 Update beneficiary

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

#### 2.1 Get bank account list (facilitator)
This feature only available on a **facilitator** model. You can get a list of bank accounts that you own simply by using a helper method like below:

```php
$balance = $iris->bankAccount(\Timedoor\TmdMidtransIris\AccountType::FACILITATOR)->all();
```

This method will return an array of `\Timedoor\TmdMidtransIris\Models\BankAccount`.


#### 2.2 Validate bank account
You can ensure the validity of your user bank account using the `validate` method on bank account service like below:

```php
$result = $iris->bankAccount()->validate('bca', '12345678');
```

This method take two parameters which is:
- bank code
- account number

And it will return a `\Timedoor\TmdMidtransIris\Models\BankAccountValidated` on successfull call or an exception will be thrown if the account you validate is not valid. Please refer to this link if you want to find any supported bank accounts [https://iris-docs.midtrans.com/#supported-banks](https://iris-docs.midtrans.com/#supported-banks) or you can integrate with the built in method [2.3 Supported Banks](#23-supported-banks).

#### 2.3 Supported Banks
You can get all supported bank accounts in case you need to know which bank code to use or just to satisfy the UI design of your application. Refer to the example below to use it:

```php
$result = $iris->bankAccount()->bankList();
```

This method return an array of `\Timedoor\TmdMidtransIris\Models\Bank`.

### Payouts
This section will describe how you can use this library to manage your payouts. The Iris's API let us to create, approve, reject, get details and history of payouts with our account. To see more details about the Open API provided by Iris you can refer to this link: [https://iris-docs.midtrans.com](https://iris-docs.midtrans.com). In this section you will encounter some roles to interact with the API which is a `creator` and a `approver` each role has it's own specific job which will be described in the particular section.

#### 2.0 Create payout(s)
This API is only for `creator` role and will allow you to create single or multi payout at once. Below is the example on how to use it:

```php
$payouts = [
    (new \Timedoor\TmdMidtransIris\Dto\PayoutRequest)
        ->setBeneficiaryName('Example')
        ->setBeneficiaryAccount('1212121212')
        ->setBeneficiaryBank('bca')
        ->setAmount(10000)
        ->setNotes('just an example payout'),
    (new \Timedoor\TmdMidtransIris\Dto\PayoutRequest)
        ->setBeneficiaryName('Example 2')
        ->setBeneficiaryAccount('1313131313')
        ->setBeneficiaryBank('bni')
        ->setAmount(20000)
        ->setNotes('just an example payout')
];

$result = $iris->payout()->create($payouts);
```

The method `create` on `payout` service only accept an array of `\Timedoor\TmdMidtransIris\Dto\PayoutRequest`. You can construct the request using the setter method or you also could use the `fromArray` method since the `\Timedoor\TmdMidtransIris\Dto\PayoutRequest` extends from the `Timedoor\TmdMidtransIris\Utils\DataMapper`.

This method will return an array of `\Timedoor\TmdMidtransIris\Models\Payout[]` on successfull request whether you pass only a single or multiple payouts or an exception will be thrown if it fail.

#### 2.1 Approve payout(s)
The next step after you create a payout is to ***approve it***. This job will require a `approver` role. Payout approval request is pretty simple like this example:

```php
$result = $iris->payout()->approve(['ref001', 'ref002'], '333333');
```

The `approve` method accept 2 arguments: an array of `ref_nos` or payout id and an `otp`. If it success it will return an array of `["status" => "ok"]` or an exception will be thrown if it fails. For more information refer to this link [https://iris-docs.midtrans.com/#approve-payouts](https://iris-docs.midtrans.com/#approve-payouts).

#### 2.2 Reject payout(s)
You can reject any payout(s) that has been created and the status is `queued`. For example:

```php
$response = $iris->payout()->reject(['ref001'], 'the reason why you reject it');
```

When you reject payout, you will required to pass the **reject reason** but it doesn't require you to enter an otp like in approval. This method will throw an exception if it fails or an array will be returned if it success. For more info [https://iris-docs.midtrans.com/#reject-payouts](https://iris-docs.midtrans.com/#reject-payouts).

#### 2.3 Get payout detail
To get a more detailed information about a payout, you can use this method. For example:

```php
$payout = $iris->payout()->get('ref001');
```

This method will return a `\Timedoor\TmdMidtransIris\Models\Payout` or it can also throw an exception if it fail.

### Transaction History
You can get a history of transactions that has been saved into your account like `payouts` or `topups`. To get a list of transaction histories simply call the `history` method on the `transaction` service like below:

```php
$transactions = $iris->transaction()->history();
```

This method actually takes 2 parameters of `\DateTime` object but its optional. And it will return an array of `\Timedoor\TmdMidtransIris\Models\Transaction`. 

> There are a few rules and restrictions for filtering the history data by adjusting the `$from` and `$to` of the parameters, you can read more about it here [https://iris-docs.midtrans.com/#transaction-history](https://iris-docs.midtrans.com/#transaction-history).

### Top Up Channel Information
Before you can approve any payout(s), for the `aggregator` model you will need some balance in your account. To fill the balance in your account you need to top-up with certain amount of cash. This API will give you information to where should you transfer that certain amount to top-up your account balance. For example:

```php
$channels = $iris->topUp()->channels();
```

This method will return an array of `\Timedoor\TmdMidtransIris\Models\TopUpChannel`.

### Laravel Support
This package support Laravel out of the box. Once you install the package, all of the services will be available to you if you're using Laravel auto-discovery. After you install the package, you should configure the library according your own credentials:

```
MIDTRANS_IRIS_ENV=
MIDTRANS_IRIS_MERCHANT_KEY=
MIDTRANS_IRIS_CREATOR_KEY=
MIDTRANS_IRIS_APPROVER_KEY=
```

**Use it as dependency injection**
```php
public function index(Beneficiary $beneficiary)
{
    return $beneficiary->all();
}
```

**You can also use the facade**
```php
use Timedoor\TmdMidtransIris\Facade as Iris;
```

Then use the service you need:

```php
$payout = Iris::payout()->get('abc');

// or

$beneficiaries = Iris::beneficiary()->all();
```

<!-- omit in toc -->
#### Without Laravel auto-discovery
If you're using a version of Laravel without auto-discovery, you must specify the service provider in your `config/app.php` file:

```php
\Timedoor\TmdMidtransIris\IrisServiceProvider::class
```