<?php

namespace App\Http\Controllers\API\V1;

use App\Models\MonthlyFee;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateMonthlyFeeRequest;

class MonthlyFeeController extends Controller
{
    use ApiResponseTrait;

    public function index() {
        $monthly_fee = MonthlyFee::find(1);

        return $this->success(["monthlyFee" => $monthly_fee["amount"]], 'Monthly Fee Fetched Successfully', 200);
    }

    public function update(UpdateMonthlyFeeRequest $request) {
        $request->validated($request->all());

        $monthly_fee = MonthlyFee::find(1);
        $monthly_fee->amount = $request->amount;
        $monthly_fee->save();

        return $this->success(["monthlyFee" => $monthly_fee["amount"]], 'Monthly Fee Updated Successfully', 200);
    }
}
