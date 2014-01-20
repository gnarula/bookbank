<?php

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        User::create(array(
            'id' => 'admin',
            'password' => Hash::make('admin'),
            'name' => 'admin',
            'mobile' => '8412898988',
            'email' => 'admin@admin.com',
            'hostel' => '',
            'room_no' => ''
        ));
    }
}