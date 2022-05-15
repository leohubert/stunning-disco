<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Client;
use App\Models\Company;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Transaction::paginate();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransactionRequest  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
