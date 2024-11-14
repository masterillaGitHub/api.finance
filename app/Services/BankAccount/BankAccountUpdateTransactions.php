<?php
declare(strict_types=1);

namespace App\Services\BankAccount;

use App\Data\MonoBankStatementData;
use App\Enums\TransactionInputTypeEnum;
use App\Enums\TransactionParentCategoryEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Account;
use App\Models\AccountSum;
use App\Models\Currency;
use App\Models\Transaction;
use App\Services\BankManager\AbstractBankManager;
use App\Services\BankManager\BankManagerFactory;
use Illuminate\Support\Collection;

final class BankAccountUpdateTransactions
{
    const int MAX_DAYS = 30;
    private Collection $currencies;

    public function __construct()
    {
        $this->currencies = Currency::all();
    }

    public function handle(array $data): void
    {
        $accounts = empty($data['accountsIds'])
            ? Account::with('lastTransaction')->where('bank_id', $data['bankId'])->get()
            : Account::with('lastTransaction')->whereIn('id', $data['accountsIds'])->get();

        $bankManager = BankManagerFactory::make($data['bankId']);

        if ($accounts->isEmpty()) {
            return;
        }

        $statements = $this->getStatements($bankManager, $accounts);

        $this->handleTransactions($statements, $accounts);
    }

    private function handleTransactions(Collection $statements, Collection $accounts): void
    {
        $statements->each(function (Collection $transactions, int $accountId) use ($accounts): void {
            if ($transactions->isEmpty()) {
                return;
            }

            /** @var Account $account */
            $account = $accounts->firstWhere('id', $accountId);

            $transactions->each(
                fn(MonoBankStatementData $transaction): null => $this->storeTransaction($account, $transaction)
            );

            $this->updateAccountSum($account, $transactions->last());
        });
    }

    private function getStatements(AbstractBankManager $bankManager, Collection $accounts): Collection
    {
        $bankTransactions = collect();

        /** @var Account $account */
        foreach ($accounts as $key => $account) {
            // Щоб не навантажувати API банків
            if ($accounts->count() > 1 && $key > 0) {
                sleep(1);
            }

            $bankTransactions->put($account->id, $this->fetchStatement($bankManager, $account));
        }

        return $bankTransactions;
    }

    private function updateAccountSum(Account $account, MonoBankStatementData $transaction): void
    {
        $data = [
            'account_id' => $account->id,
            'currency_id' => $account->currency_id,
        ];
        $balance = $transaction->balance;

        if ($account->credit_limit) {
            $balance -= $account->credit_limit;
        }

        $accountSum = AccountSum::createOrFirst($data, $data);
        $accountSum->balance = $balance;
        $accountSum->save();
    }

    private function storeTransaction(Account $account, MonoBankStatementData $transaction): void
    {
        $currency = $this->currencies->firstWhere('numeric_code', $transaction->currencyCode);
        $typeId = $transaction->amount > 0
            ? TransactionTypeEnum::INCOME->value
            : TransactionTypeEnum::EXPENSE->value;
        $categoryId = $transaction->amount > 0
            ? TransactionParentCategoryEnum::INCOME->value
            : TransactionParentCategoryEnum::EXPENSE->value;


        Transaction::createOrFirst(['id_origin' => $transaction->id], [
            'user_id' => auth()->id(),
            'type_id' => $typeId,
            'account_id' => $account->id,
            'currency_id' => $currency->id,
            'category_id' => $categoryId,
            'input_type' => TransactionInputTypeEnum::AUTO->value,
            'amount' => $transaction->amount,
            'description' => $transaction->description,
            'note' => $transaction->comment,
            'info' => $transaction->toArray(),
            'id_origin' => $transaction->id,
            'transaction_at' => $transaction->time,
        ]);
    }

    private function fetchStatement(AbstractBankManager $manager, Account $account): Collection
    {
        $dateFrom = $account->lastTransaction?->transaction_at ?? now()->subDays(self::MAX_DAYS);

        return $manager->getStatement($account->id_origin, $dateFrom)
            ->sortBy('time')
            ;
    }
}
