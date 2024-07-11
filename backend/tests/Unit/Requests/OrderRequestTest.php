<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Validator;

class OrderRequestTest extends TestCase
{
    public function test_order_request_validation_passes()
    {
        $data = [
            'name' => 'Valid Order',
            'description' => 'Valid Description',
            'date' => '2024-07-06'
        ];

        $request = new OrderRequest();

        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_order_request_validation_fails()
    {
        $data = [
            'name' => '', // name is required
            'description' => 'Valid Description',
            'date' => '2024-07-06'
        ];

        $request = new OrderRequest();

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->passes());
    }
}
