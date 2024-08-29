<?php

use App\Enums\Currency;
use App\Enums\TransactionType;
use App\Models\TransactionCategory;
use App\Services\TransactionService;
use Tests\Generators\AccountGenerator;
use Tests\Generators\AccountSumGenerator;
use Tests\Generators\TransactionCategoryGenerator;
use Tests\Generators\TransactionGenerator;
use Tests\Generators\UserGenerator;

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

    $service = new TransactionService();
    $service->update($transaction, [
        'to_amount' => 4500
    ]);

    $this->assertEquals($toAccountSum->fresh()->balance, -500);
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

    $service = new TransactionService();
    $service->update($transaction, [
        'to_currency_id' => $newCurrencyId,
    ]);

    $this->assertEquals($toAccountSum->fresh()->balance, -5000);
    $this->assertEquals($newToAccountSum->fresh()->balance, 5000);
});

test('update to_account in transfer Transaction with update AccountSum', function () {
    $typeId = TransactionType::TRANSFER->value;
    $currencyId = Currency::UAH->value;

    UserGenerator::generate();
    $transactionCategory = TransactionCategory::query()->where('type_id', $typeId)->first();
    $fromAccount = AccountGenerator::generate();

    $toAccount = AccountGenerator::generate();
    $toAccountSum = AccountSumGenerator::generate([
        'account_id' => $toAccount->id,
        'currency_id' => $currencyId,
        'balance' => 0 // set int number
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

    $service = new TransactionService();

    $service->update($transaction, [
        'to_account_id' => $newToAccount->id,
    ]);

    $this->assertEquals($toAccountSum->fresh()->balance, -5000);
    $this->assertEquals($newToAccountSum->fresh()->balance, 5000);
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

    $service = new TransactionService();

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'account_id' => $account->id,
        'category_id' => $transactionCategory->id,
        'currency_id' => $currencyId,
        'amount' => -5000,
    ]);

    $service->update($transaction, [
        'account_id' => $newAccount->id
    ]);

    $this->assertEquals($accountSum->fresh()->balance, -25000);
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

    $service = new TransactionService();

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'account_id' => $account->id,
        'category_id' => $transactionCategory->id,
        'currency_id' => $currencyId,
        'amount' => 5000,
    ]);

    $service->update($transaction, [
        'currency_id' => Currency::USD->value
    ]);

    $this->assertEquals($accountSum->fresh()->balance, -35000);
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

    $service = new TransactionService();

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'account_id' => $account->id,
        'category_id' => $transactionCategory->id,
        'currency_id' => $currencyId,
        'amount' => 5000,
    ]);

    $service->update($transaction, [
        'amount' => 20000
    ]);

    $this->assertEquals($accountSum->fresh()->balance, -15000);
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

    $service = new TransactionService();

    $transaction = TransactionGenerator::generate([
        'type_id' => $typeId,
        'account_id' => $account->id,
        'category_id' => $transactionCategory->id,
        'currency_id' => $currencyId,
        'amount' => 500,
    ]);

    $service->update($transaction, [
        'amount' => -1500
    ]);

    $this->assertEquals($accountSum->fresh()->balance, 8000);
});
