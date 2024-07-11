<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Validator;

class ProductRequestTest extends TestCase
{
    public function test_product_request_validation()
    {
        $data = [
            'name' => 'Test Product',
            'price' => 100
        ];

        $request = new ProductRequest();

        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_product_request_validation_fails()
    {
        $data = [
            'name' => '',
            'price' => 'invalid'
        ];

        $request = new ProductRequest();

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->passes());
    }
}
