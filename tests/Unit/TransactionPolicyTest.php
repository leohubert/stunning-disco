<?php

namespace Tests\Unit;

use App\Exceptions\TransactionPolicyException;
use App\Models\Client;
use App\Models\Company;
use App\Models\Product;
use App\Models\Provider;
use App\Models\User;
use App\Policies\TransactionPolicy;
use Tests\TestCase;
use Throwable;

class TransactionPolicyTest extends TestCase
{
    private TransactionPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new TransactionPolicy();
    }

    /**
     * @test
     * @return void
     * @throws TransactionPolicyException
     */
    public function assert_client_is_allowed_to_buy_a_product(): void
    {
        $this->be($user = User::factory()->create());

        $product = Product::factory(['stock' => 10])->create();
        $client = Client::factory()->create();
        $company = Company::factory()->create();

        $amountToBuy = 2;

        $authorization = $this->policy->create(
            $user,
            $product,
            $amountToBuy,
            $client,
            $company
        );

        $this->assertTrue($authorization->allowed());
    }

    /**
     * @test
     * @return void
     * @throws TransactionPolicyException
     */
    public function assert_client_is_not_allowed_to_buy_a_product_if_not_enough_stocks(): void
    {
        $this->be($user = User::factory()->create());

        $product = Product::factory(['stock' => 10])->create();
        $client = Client::factory()->create();
        $company = Company::factory()->create();

        $amountToBuy = 11;

        $authorization = $this->policy->create(
            $user,
            $product,
            $amountToBuy,
            $client,
            $company
        );

        $this->assertTrue($authorization->denied());
    }

    /**
     * @test
     * @return void
     * @throws TransactionPolicyException
     */
    public function assert_company_cannot_as_enough_money_to_buy_a_product(): void
    {
        $this->be($user = User::factory()->create());

        $product = Product::factory(['price' => 5])->create();
        $provider = Provider::factory()->create();
        $company = Company::factory(['balance' => 10])->create();

        $authorization = $this->policy->create(
            $user,
            $product,
            3,
            $company,
            $provider,
        );

        $this->assertTrue($authorization->denied());

        $authorization = $this->policy->create(
            $user,
            $product,
            2,
            $company,
            $provider,
        );

        $this->assertTrue($authorization->allowed());
    }

    /**
     * @test
     * @return void
     */
    public function assert_client_cannot_buy_product_from_provider(): void
    {
        $this->be($user = User::factory()->create());

        $product = Product::factory(['stock' => 10])->create();
        $client = Client::factory()->create();
        $provider = Provider::factory()->create();

        $amountToBuy = 1;

        try {
            $this->policy->create(
                $user,
                $product,
                $amountToBuy,
                $client,
                $provider
            );
        } catch (Throwable $exception) {
            $this->assertInstanceOf(TransactionPolicyException::class, $exception);
            $this->assertEquals(
                TransactionPolicyException::clientCannotBuyToProvider()->getMessage(),
                $exception->getMessage()
            );
        }
    }

    /**
     * @test
     * @return void
     */
    public function assert_that_a_company_cannot_buy_a_product_from_himself(): void
    {
        $this->be($user = User::factory()->create());

        $product = Product::factory(['stock' => 10])->create();
        $company = Company::factory()->create();

        $amountToBuy = 1;

        try {
            $this->policy->create(
                $user,
                $product,
                $amountToBuy,
                $company,
                $company
            );
        } catch (Throwable $exception) {
            $this->assertInstanceOf(TransactionPolicyException::class, $exception);
            $this->assertEquals(
                TransactionPolicyException::cannotBuyFromHimself()->getMessage(),
                $exception->getMessage()
            );
        }
    }

    /**
     * @test
     * @return void
     */
    public function assert_that_a_company_cannot_buy_a_product_from_another_company(): void
    {
        $this->be($user = User::factory()->create());

        $product = Product::factory(['stock' => 10])->create();
        $company1 = Company::factory()->create();
        $company2 = Company::factory()->create();

        $amountToBuy = 1;

        try {
            $this->policy->create(
                $user,
                $product,
                $amountToBuy,
                $company1,
                $company2
            );
        } catch (Throwable $exception) {
            $this->assertInstanceOf(TransactionPolicyException::class, $exception);
            $this->assertEquals(
                TransactionPolicyException::companyCannotBuyProductFromAnotherCompany()->getMessage(),
                $exception->getMessage()
            );
        }
    }
}
