<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        //从安全的角度来看，Laravel 无法向 DB 中插入一些可靠的数据。
        // セキュリティー解除

        Model::unguard();

        $this->call(UsersTableSeeder::class);

        // セキュリティーを再設定

        Model::reguard();
    }
}
