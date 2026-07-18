<?php

namespace App\Domain\CashRegister;

use App\Models\BusinessDayClosure;
use App\Models\CashRegister;

class CloseCashRegisterResult
{
    public function __construct(
        public CashRegister $cashRegister,
        public BusinessDayClosure $closure,
    ) {
    }
}
