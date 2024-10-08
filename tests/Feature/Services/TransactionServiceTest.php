<?php

use App\Enums\Currency;
use App\Enums\TransactionType;
use App\Models\TransactionCategory;
use Tests\Generators\AccountGenerator;
use Tests\Generators\AccountSumGenerator;
use Tests\Generators\TransactionCategoryGenerator;
use Tests\Generators\TransactionGenerator;
use Tests\Generators\UserGenerator;

test('update account and to_account from internal to external in transfer Transaction with update only internal AccountSum', function () {
    $typeId = TransactionType::TRANSFER->value;
    $currencyId = Currency::UAH->value;

    UserGenerator::generate();
    $transactionCategory = TransactionCategory::query()->where('type_id', $typeId)->first();
    $externalAccount = AccountGenerator::generate([
        'place_type' => \App\Enums\AccountPlaceType::EXTERNAL
    ]);
    $internalAccount = AccountGenerator::generate();
    $internalAccountSum = AccountSumGenerator::generate([
        'account_id' => $internalAccount->id,
        'currency_id' => $currencyId,
        'balance' => 0
    ]);

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'category_id' => $transactionCategory->id,

        'account_id' => $internalAccount->id,
        'currency_id' => $currencyId,
        'amount' => -5000,

        'to_account_id' => $externalAccount->id,
        'to_currency_id' => $currencyId,
        'to_amount' => 5000,
    ]);


    $transaction->update([
        'account_id' => $externalAccount->id,
        'to_account_id' => $internalAccount->id,
        'amount' => -5000,
        'to_amount' => 5000,
    ]);

    $this->assertEquals($internalAccountSum->fresh()->balance, 5000);
    $this->assertDatabaseMissing('account_sums', [
        'account_id' => $externalAccount->id,
    ]);
});

test('update to_account from internal to external in transfer Transaction with update internal AccountSum', function () {
    $typeId = TransactionType::TRANSFER->value;
    $currencyId = Currency::UAH->value;

    UserGenerator::generate();
    $transactionCategory = TransactionCategory::query()->where('type_id', $typeId)->first();
    $toExternalAccount = AccountGenerator::generate([
        'place_type' => \App\Enums\AccountPlaceType::EXTERNAL
    ]);
    $toInternalAccount = AccountGenerator::generate();
    $toInternalAccountSum = AccountSumGenerator::generate([
        'account_id' => $toInternalAccount->id,
        'currency_id' => $currencyId,
        'balance' => 0
    ]);
    $fromInternalAccount = AccountGenerator::generate();
    $fromInternalAccountSum = AccountSumGenerator::generate([
        'account_id' => $fromInternalAccount->id,
        'currency_id' => $currencyId,
        'balance' => 0
    ]);

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'category_id' => $transactionCategory->id,

        'account_id' => $fromInternalAccount->id,
        'currency_id' => $currencyId,
        'amount' => -5000,

        'to_account_id' => $toExternalAccount->id,
        'to_currency_id' => $currencyId,
        'to_amount' => 5000,
    ]);

    $transaction->update([
        'account_id' => $fromInternalAccount->id,
        'to_account_id' => $toInternalAccount->id,
        'amount' => -5000,
        'to_amount' => 5000,
    ]);

    $this->assertEquals($fromInternalAccountSum->fresh()->balance, -5000);
    $this->assertEquals($toInternalAccountSum->fresh()->balance, 5000);
    $this->assertDatabaseMissing('account_sums', [
        'account_id' => $toExternalAccount->id,
    ]);
});

test('update account from external to internal in transfer Transaction with update internal AccountSum', function () {
    $typeId = TransactionType::TRANSFER->value;
    $currencyId = Currency::UAH->value;

    UserGenerator::generate();
    $transactionCategory = TransactionCategory::query()->where('type_id', $typeId)->first();
    $toInternalAccount = AccountGenerator::generate();
    $toInternalAccountSum = AccountSumGenerator::generate([
        'account_id' => $toInternalAccount->id,
        'currency_id' => $currencyId,
        'balance' => 0
    ]);
    $fromInternalAccount = AccountGenerator::generate();
    $fromInternalAccountSum = AccountSumGenerator::generate([
        'account_id' => $fromInternalAccount->id,
        'currency_id' => $currencyId,
        'balance' => 0
    ]);

    $fromExternalAccount = AccountGenerator::generate([
        'place_type' => \App\Enums\AccountPlaceType::EXTERNAL
    ]);

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'category_id' => $transactionCategory->id,

        'account_id' => $fromExternalAccount->id,
        'currency_id' => $currencyId,
        'amount' => -5000,

        'to_account_id' => $toInternalAccount->id,
        'to_currency_id' => $currencyId,
        'to_amount' => 5000,
    ]);

    $transaction->update([
        'account_id' => $fromInternalAccount->id,
        'to_account_id' => $toInternalAccount->id,
        'amount' => -5000,
        'to_amount' => 5000,
    ]);

    $this->assertEquals($fromInternalAccountSum->fresh()->balance, -5000);
    $this->assertEquals($toInternalAccountSum->fresh()->balance, 5000);
    $this->assertDatabaseHas('account_sums', [
        'account_id' => $fromInternalAccount->id,
    ]);
});

test('update to_amount in transfer Transaction with update AccountSum', function () {
    $typeId = TransactionType::TRANSFER->value;
    $currencyId = Currency::UAH->value;

    UserGenerator::generate();
    $transactionCategory = TransactionCategory::query()->where('type_id', $typeId)->first();
    $fromAccount = AccountGenerator::generate();

    $toAccount = AccountGenerator::generate();
    $toAccountSum = AccountSumGenerator::generate([
        'account_id' => $toAccount->id,
        'currency_id' => $currencyId,
        'balance' => 0
    ]);

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'category_id' => $transactionCategory->id,
        'account_id' => $fromAccount->id,
        'currency_id' => $currencyId,
        'to_account_id' => $toAccount->id,
        'to_currency_id' => $currencyId,
        'amount' => -5000,
        'to_amount' => 5000,
    ]);

    $transaction->update([
        'to_amount' => 4500
    ]);

    $this->assertEquals($toAccountSum->fresh()->balance, 4500);
});

test('update to_currency in transfer Transaction with update AccountSum', function () {
    $typeId = TransactionType::TRANSFER->value;
    $currencyId = Currency::UAH->value;
    $newCurrencyId = Currency::USD->value;

    UserGenerator::generate();
    $transactionCategory = TransactionCategory::query()->where('type_id', $typeId)->first();
    $fromAccount = AccountGenerator::generate();

    $toAccount = AccountGenerator::generate();
    $toAccountSum = AccountSumGenerator::generate([
        'account_id' => $toAccount->id,
        'currency_id' => $currencyId,
        'balance' => 0
    ]);
    $newToAccountSum = AccountSumGenerator::generate([
        'account_id' => $toAccount->id,
        'currency_id' => $newCurrencyId,
        'balance' => 0,
    ]);


    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'category_id' => $transactionCategory->id,
        'account_id' => $fromAccount->id,
        'currency_id' => $currencyId,
        'to_account_id' => $toAccount->id,
        'to_currency_id' => $currencyId,
        'amount' => -5000,
        'to_amount' => 5000,
    ]);

    $transaction->update([
        'to_currency_id' => $newCurrencyId,
    ]);

    $this->assertEquals($toAccountSum->fresh()->balance, 0);
    $this->assertEquals($newToAccountSum->fresh()->balance, 5000);
});

test('update to_account in transfer Transaction with update AccountSum', function () {
    $typeId = TransactionType::TRANSFER->value;
    $currencyId = Currency::UAH->value;

    UserGenerator::generate();
    $transactionCategory = TransactionCategory::query()->where('type_id', $typeId)->first();
    $fromAccount = AccountGenerator::generate();
    $fromAccountSum = AccountSumGenerator::generate([
        'account_id' => $fromAccount->id,
        'currency_id' => $currencyId,
        'balance' => 0
    ]);

    $toAccount = AccountGenerator::generate();
    $toAccountSum = AccountSumGenerator::generate([
        'account_id' => $toAccount->id,
        'currency_id' => $currencyId,
        'balance' => 0
    ]);

    $newToAccount = AccountGenerator::generate();
    $newToAccountSum = AccountSumGenerator::generate([
        'currency_id' => $currencyId,
        'account_id' => $newToAccount->id,
        'balance' => 0,
    ]);

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'currency_id' => $currencyId,
        'category_id' => $transactionCategory->id,
        'account_id' => $fromAccount->id,
        'amount' => -5000,
        'to_amount' => 5000,
        'to_currency_id' => $currencyId,
        'to_account_id' => $toAccount->id,
    ]);

    $transaction->update([
        'to_account_id' => $newToAccount->id,
    ]);

    $this->assertEquals($fromAccountSum->fresh()->balance, -5000);
    $this->assertEquals($toAccountSum->fresh()->balance, 0);
    $this->assertEquals($newToAccountSum->fresh()->balance, 5000);
});

test('update type in expense Transaction with add a new AccountSum', function () {
    $typeId = TransactionType::EXPENSE->value;
    $currencyId = Currency::UAH->value;

    UserGenerator::generate();
    $transactionCategory = TransactionCategoryGenerator::generate([
        'type_id' => $typeId,
    ]);
    $account = AccountGenerator::generate();
    $accountSum = AccountSumGenerator::generate([
        'account_id' => $account->id,
        'currency_id' => $currencyId,
        'balance' => -30000
    ]);

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'account_id' => $account->id,
        'category_id' => $transactionCategory->id,
        'currency_id' => $currencyId,
        'amount' => -5000,
    ]);

    $transaction->update([
        'type_id' => TransactionType::INCOME->value,
        'amount' => 5000
    ]);

    $this->assertEquals($accountSum->fresh()->balance, -25000);
});

test('update account in expense Transaction with add a new AccountSum', function () {
    $typeId = TransactionType::EXPENSE->value;
    $currencyId = Currency::UAH->value;

    UserGenerator::generate();
    $transactionCategory = TransactionCategoryGenerator::generate([
        'type_id' => $typeId,
    ]);
    $account = AccountGenerator::generate();
    $accountSum = AccountSumGenerator::generate([
        'account_id' => $account->id,
        'currency_id' => $currencyId,
        'balance' => -30000
    ]);

    $newAccount = AccountGenerator::generate();
    $newAccountSum = AccountSumGenerator::generate([
        'currency_id' => $currencyId,
        'account_id' => $newAccount->id,
        'balance' => 0,
    ]);

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'account_id' => $account->id,
        'category_id' => $transactionCategory->id,
        'currency_id' => $currencyId,
        'amount' => -5000,
    ]);

    $transaction->update([
        'account_id' => $newAccount->id
    ]);

    $this->assertEquals($accountSum->fresh()->balance, -30000);
    $this->assertEquals($newAccountSum->fresh()->balance, -5000);
});

test('update currency in income Transaction with add a new AccountSum', function () {
    $typeId = TransactionType::INCOME->value;
    $currencyId = Currency::UAH->value;

    UserGenerator::generate();
    $transactionCategory = TransactionCategoryGenerator::generate([
        'type_id' => $typeId,
    ]);
    $account = AccountGenerator::generate();
    $accountSum = AccountSumGenerator::generate([
        'currency_id' => $currencyId,
        'balance' => -30000
    ]);

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'account_id' => $account->id,
        'category_id' => $transactionCategory->id,
        'currency_id' => $currencyId,
        'amount' => 5000,
    ]);

    $transaction->update([
        'currency_id' => Currency::USD->value
    ]);

    $this->assertEquals($accountSum->fresh()->balance, -30000);
    $this->assertDatabaseHas('account_sums', [
        'account_id' => $account->id,
        'currency_id' => Currency::USD->value,
        'balance' => 5000,
    ]);
});

test('update amount in income Transaction with update balance in Account', function () {
    $typeId = TransactionType::INCOME->value;
    $currencyId = Currency::UAH->value;

    UserGenerator::generate();
    $transactionCategory = TransactionCategoryGenerator::generate([
        'type_id' => $typeId,
    ]);
    $account = AccountGenerator::generate();
    $accountSum = AccountSumGenerator::generate([
        'currency_id' => $currencyId,
        'balance' => -30000
    ]);

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'account_id' => $account->id,
        'category_id' => $transactionCategory->id,
        'currency_id' => $currencyId,
        'amount' => 5000,
    ]);

    $transaction->update([
        'amount' => 20000
    ]);

    $this->assertEquals($accountSum->fresh()->balance, -10000);
});

test('update amount in expense Transaction with update balance in Account', function () {
    $typeId = TransactionType::EXPENSE->value;
    $currencyId = Currency::UAH->value;

    UserGenerator::generate();
    $transactionCategory = TransactionCategoryGenerator::generate([
        'type_id' => $typeId,
    ]);
    $account = AccountGenerator::generate();
    $accountSum = AccountSumGenerator::generate([
        'currency_id' => $currencyId,
        'balance' => 10000
    ]);

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'account_id' => $account->id,
        'category_id' => $transactionCategory->id,
        'currency_id' => $currencyId,
        'amount' => 500,
    ]);

    $transaction->update([
        'amount' => -1500
    ]);

    $this->assertEquals($accountSum->fresh()->balance, 8500);
});
