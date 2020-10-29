<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersUpdateTableSeeder extends Seeder
{
    /**
     * Заполняем реферальными ссылками уже созданных пользователей по умолчанию.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')
            ->chunkById(100, function ($users) {
                foreach ($users as $user) {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['referral' => getUniqueReferralLink($user->email)]);
                }
            });
    }
}
