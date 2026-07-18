<?php

namespace App\Http\Controllers\Prints;

use App\Http\Controllers\Controller;
use App\Models\BusinessDayClosure;

class ClosingSlipController extends Controller
{
    public function __invoke(BusinessDayClosure $closure)
    {
        $closure->load([
            'cashRegister',
            'closedBy',
        ]);

        return view('prints.closing-slip', [
            'closure' => $closure,
        ]);
    }
}
