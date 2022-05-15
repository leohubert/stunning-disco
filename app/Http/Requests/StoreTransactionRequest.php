<?php

namespace App\Http\Requests;

use App\Models\Client;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Provider;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'responsible_id' => ['required', 'exists:employees,id'],

            'buyer_id' => ['required', 'numeric'],
            'buyer_type' => ['required', 'string'],

            'seller_id' => ['required', 'numeric'],
            'seller_type' => ['required', 'string'],

            'product_id' => ['required', 'exists:products,id'],
            'amount' => ['required', 'numeric', 'min:1']
        ];
    }

    public function product(): Product
    {
        return Product::find($this->input('product_id'));
    }

    public function buyer(): Client|Company
    {
        $buyerId = $this->input('buyer_id');

        return match ($this->input('buyer_type')) {
            Company::class => Company::findOrFail($buyerId),
            Client::class => Client::findOrFail($buyerId),
            default  => abort(422, "Invalid buyer type")
        };
    }

    public function seller(): Company|Provider
    {
        $sellerId = $this->input('seller_id');

        return match ($this->input('seller_type')) {
            Company::class => Company::findOrFail($sellerId),
            Provider::class => Provider::findOrFail($sellerId),
            default  => abort(422, "Invalid seller type")
        };
    }

    public function responsible(): Employee
    {
        return Employee::find($this->input('responsible_id'));
    }
}
