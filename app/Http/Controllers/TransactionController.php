<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Client;
use App\Models\Company;
use App\Models\Transaction;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

class TransactionController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreTransactionRequest  $request
     * @return Response
     * @throws AuthorizationException
     */
    public function store(StoreTransactionRequest $request)
    {
        $product = $request->product();
        $buyer = $request->buyer();
        $seller = $request->seller();
        $amountToBuy = $request->input('amount');

        $this->authorize('create', [
            Transaction::class,
            $product,
            $amountToBuy,
            $buyer,
            $seller
        ]);

        if ($buyer::class === Company::class) {
            $buyer->update([
                'balance' => $buyer->balance - ($product->price * $amountToBuy)
            ]);
            $product->update([
                'stock' => $product->stock + $amountToBuy
            ]);
        } else if ($buyer::class === Client::class) {
            $product->update([
                'stock' => $product->stock - $amountToBuy
            ]);
        } else {
            return response('Unhandled case', 422);
        }


        Transaction::create([
            'buyer_id' => $buyer->id,
            'buyer_type' => $buyer::class,

            'seller_id' => $seller->id,
            'seller_type' => $seller::class,

            'product_id' => $product->id,
            'amount' => $amountToBuy,
            'responsible' => $request->responsible()->id
        ]);

        return response()->noContent();
    }
}
