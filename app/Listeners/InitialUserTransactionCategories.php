<?php

namespace App\Listeners;

use App\Actions\DefaultTransactionCategory;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class InitialUserTransactionCategories
{
    private DefaultTransactionCategory $categories;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->categories = new DefaultTransactionCategory();
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $this->createToUser($user);
    }


    private function createToUser(User $user): void
    {
        collect($this->categories->all())
            ->map(fn(array $category) => [...$category, 'user_id' => $user->id])
            ->map(fn(array $category) => $this->save($category));
    }

    private function save(array $category): void
    {
        if (Arr::has($category, 'children')) {
            $children = Arr::pull($category, 'children');
        }

        $item = TransactionCategory::create($category);

        if (isset($children)) {
            foreach ($children as $child) {

                TransactionCategory::create([
                    ...$child,
                    'user_id' => $category['user_id'],
                    'parent_id' => $item->id
                ]);
            }
        }
    }
}
