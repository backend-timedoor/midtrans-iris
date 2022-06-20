<?php

namespace Timedoor\TmdMidtransIris;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @method static \Timedoor\TmdMidtransIris\Beneficiary beneficiary()
 * @method static \Timedoor\TmdMidtransIris\BankAccount bankAccount()
 * @method static \Timedoor\TmdMidtransIris\Payout payout()
 * @method static \Timedoor\TmdMidtransIris\Transaction transaction()
 * @method static \Timedoor\TmdMidtransIris\TopUp topUp()
 * 
 * @see \Timedoor\TmdMidtransIris\Iris
 */
class Facade extends BaseFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Iris::class;
    }
}