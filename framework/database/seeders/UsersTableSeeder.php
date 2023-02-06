<?php

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

//        $users = [
//            [
//                'firstName' => 'Radu',
//                'lastName'  => 'Dalbea',
//                'email'     => 'radudalbea@gmail.com',
//                'password'  => md5('radu1234'),
//                'isAdmin'   => 1,
//                'lastId'    => 100001,
//            ],
//            [
//                'firstName' => 'Leonard',
//                'lastName'  => 'Florea',
//                'email'     => 'leo.florea@gmail.com',
//                'password'  => md5('leo1234'),
//                'isAdmin'   => 1,
//                'lastId'    => 100002
//            ]
//        ];

        $results = DB::connection('old')->table('fc_users')->get();

        $admins = [
            'Mongoosepress@gmail.com',
            'leo.florea@gmail.com'
        ];

        foreach ($results as $item) {
            $created_at = ($item->user_registered !== '0000-00-00 00:00:00') ? $item->user_registered : null;

            $name      = explode(' ', $item->display_name);
            $firstName = array_shift($name);
            $lastName  = count($name) ? implode(' ', $name) : '';

            $user = [
                'firstName' => $firstName,
                'lastName'  => $lastName,
                'email'     => $item->user_email,
                'lastId'    => $item->ID,
                'password'  => $item->user_pass,
                'isAdmin'   => in_array($item->user_email, $admins) ? 1 : 0
            ];

            if (!empty($created_at)) {
                $user['created_at'] = $created_at;
            }

            $users[] = $user;
        }

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
