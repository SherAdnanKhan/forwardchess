<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client;

class OAuthClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('oauth_clients')->truncate();
        \Illuminate\Support\Facades\DB::table('oauth_access_tokens')->truncate();
        \Illuminate\Support\Facades\DB::table('oauth_auth_codes')->truncate();
        \Illuminate\Support\Facades\DB::table('oauth_personal_access_clients')->truncate();
        \Illuminate\Support\Facades\DB::table('oauth_refresh_tokens')->truncate();

        Client::create([
            'name'                   => 'backend',
            'secret'                 => Hash::make('THE_SECRET'),
            'redirect'               => '',
            'personal_access_client' => 0,
            'password_client'        => 1,
            'revoked'                => 0
        ]);
    }
}

