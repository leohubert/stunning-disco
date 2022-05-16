<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function assert_client_can_buy_product(): void
    {
        $this->initUser();

        $product = Product::factory(['stock' => 10])->create();
        $client = Client::factory()->create();
        $company = Company::factory()->create();
        $employee = Employee::factory()->create();

        $this->assertDatabaseCount(Transaction::class, 0);
        $this->assertEquals(10, $product->stock);

        $this->post(route('api.transactions.store'), [

            'buyer_id' => $client->id,
            'buyer_type' => $client::class,

            'seller_id' => $company->id,
            'seller_type' => $company::class,

            'product_id' => $product->id,
            'amount' => 4,

            'responsible_id' => $employee->id,

        ])->assertNoContent();
        $this->assertDatabaseCount(Transaction::class, 1);

        $product->refresh();
        $this->assertEquals(6, $product->stock);
    }

    /**
     * @test
     * @return void
     */
    public function assert_client_cannot_buy_product_if_not_enough_stock(): void
    {
        $this->initUser();

        $product = Product::factory(['stock' => 10])->create();
        $client = Client::factory()->create();
        $company = Company::factory()->create();
        $employee = Employee::factory()->create();

        $this->assertDatabaseCount(Transaction::class, 0);
        $this->assertEquals(10, $product->stock);

        $this->post(route('api.transactions.store'), [

            'buyer_id' => $client->id,
            'buyer_type' => $client::class,

            'seller_id' => $company->id,
            'seller_type' => $company::class,

            'product_id' => $product->id,
            'amount' => 12,

            'responsible_id' => $employee->id,

        ])->assertForbidden();

        $this->assertDatabaseCount(Transaction::class, 0);

        $product->refresh();
        $this->assertEquals(10, $product->stock);
    }

    /**
     * @test
     * @return void
     */
    public function assert_company_can_buy_product_if_have_enough_money(): void
    {
        $this->initUser();

        $product = Product::factory(['stock' => 10, 'price' => 5])->create();
        $company = Company::factory(['balance' => 20])->create();
        $provider = Provider::factory()->create();
        $employee = Employee::factory()->create();

        $this->assertDatabaseCount(Transaction::class, 0);
        $this->assertEquals(10, $product->stock);
        $this->assertEquals(20, $company->balance);

        $this->post(route('api.transactions.store'), [

            'buyer_id' => $company->id,
            'buyer_type' => $company::class,

            'seller_id' => $provider->id,
            'seller_type' => $provider::class,

            'product_id' => $product->id,
            'amount' => 3,

            'responsible_id' => $employee->id
        ])->assertNoContent();

        $this->assertDatabaseCount(Transaction::class, 1);

        $product->refresh();
        $company->refresh();

        $this->assertEquals(13, $product->stock);
        $this->assertEquals(5, $company->balance);
    }


    /**
     * @test
     * @return void
     */
    public function assert_company_cannot_buy_product_if_dont_have_enough_money(): void
    {
        $this->initUser();

        $product = Product::factory(['stock' => 10, 'price' => 5])->create();
        $company = Company::factory(['balance' => 20])->create();
        $provider = Provider::factory()->create();
        $employee = Employee::factory()->create();

        $this->assertDatabaseCount(Transaction::class, 0);
        $this->assertEquals(10, $product->stock);
        $this->assertEquals(20, $company->balance);

        $this->post(route('api.transactions.store'), [

            'buyer_id' => $company->id,
            'buyer_type' => $company::class,

            'seller_id' => $provider->id,
            'seller_type' => $provider::class,

            'product_id' => $product->id,
            'amount' => 5,

            'responsible_id' => $employee->id
        ])->assertForbidden();

        $this->assertDatabaseCount(Transaction::class, 0);

        $product->refresh();
        $company->refresh();

        $this->assertEquals(10, $product->stock);
        $this->assertEquals(20, $company->balance);
    }
}
