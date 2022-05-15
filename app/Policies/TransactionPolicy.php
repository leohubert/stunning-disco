<?php

namespace App\Policies;

use App\Exceptions\TransactionPolicyException;
use App\Models\Client;
use App\Models\Company;
use App\Models\Product;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{
    use HandlesAuthorization;


    private function checkClientTransaction(
        Product $product,
        int $amountToBuy
    ): Response
    {
        $stockAfterTransaction = $product->stock - $amountToBuy;

        if ($stockAfterTransaction < 0) {
            return Response::deny('Not enough product stocks');
        }

        return Response::allow();
    }

    private function checkCompanyTransaction(
        Product $product,
        int $amountToBuy,
        Company $company
    ): Response
    {
        $balanceAfterTransaction = $company->balance - ($product->price * $amountToBuy);

        if ($balanceAfterTransaction < 0) {
            return Response::deny('Not enough money to buy this item');
        }

        return Response::allow();
    }

    /**
     * @throws TransactionPolicyException
     */
    public function create(
        User $user,
        Product $product,
        int $amountToBuy,
        Client|Company $buyer,
        Company|Provider $seller
    ): Response
    {
        if ($buyer->is($seller)) throw TransactionPolicyException::cannotBuyFromHimself();

        if (
            $buyer::class === Client::class &&
            $seller::class === Provider::class
        )  {
            throw TransactionPolicyException::clientCannotBuyToProvider();
        }

        if (
            $buyer::class === Company::class &&
            $seller::class === Company::class
        )  {
            throw TransactionPolicyException::companyCannotBuyProductFromAnotherCompany();
        }

        if ($buyer::class === Company::class) {
            return $this->checkCompanyTransaction(
                $product,
                $amountToBuy,
                $buyer
            );
        } else {
            return $this->checkClientTransaction(
                $product,
                $amountToBuy
            );
        }
    }
}
