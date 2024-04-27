<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $managers = [
            [
                'email' => 'nguyenvanmanh2001it1@gmail.com',
                'password' => Hash::make('123456'),
                'line_user_id' => 'U9b60d708a68e2b81a7ff7f9c57540779',
                'channel_id' => 1,
                'role' => 'manager',
                'is_delete' => 0,
                'is_block' => 0,
                'name' => 'Nguyễn Văn Mạnh',
                'avatar' => null,
                'phone' => '0971404372',
                'address' => 'Phú Đa - Phú Vang - TT Huế',
                'gender' => 0,
                'date_of_birth' => '2001-08-29',
                'token_verify_email' => null,
                'email_verified_at' => now(),
            ],
            [
                'email' => 'nguyenvanmanh.it1@yopmail.com',
                'password' => Hash::make('123456'),
                'line_user_id' => 'U667ca434abb5753fd28330c0441c7c78',
                'channel_id' => 2,
                'role' => 'manager',
                'is_delete' => 0,
                'is_block' => 0,
                'name' => 'Nhật Minh',
                'avatar' => null,
                'phone' => '01236000333',
                'address' => '120 Nguyễn Lương Bằng - Liên Chiểu - Đà Nẵng',
                'gender' => 0,
                'date_of_birth' => '2001-09-29',
                'token_verify_email' => null,
                'email_verified_at' => now(),
            ],
        ];

        $channels = [
            [
                'account_manager_id' => 1,
                'channel_id' => '2003175796',
                'channel_name' => '[test]chanel_test1',
                'channel_secret' => '779f329a5ba363654bb7edbc2b84b6d7',
                'channel_access_token' => 'Sgxs8tgbGJN41Q92WuIQPIggm3feQjyK3GWGClLc0t16jPhg15HXsECa4l/ce39y54Vpd2PmoxcthCJZyAjYlUeoivpkWtVeXjQQ5vGpEpa7uo2bm2j8exh4zrzIG6C7yJacZtcN1NRlOP7wYN0PnwdB04t89/1O/w1cDnyilFU=',
                'picture_url' => null,
            ],
            [
                'account_manager_id' => 2,
                'channel_id' => '2003308317',
                'channel_name' => 'test_dut1',
                'channel_secret' => '1446e7d4c9eec2443fc21ce0cf4b45ec',
                'channel_access_token' => 'txnQ8efyzbIGDv6VZ1HrATucI+rskmpJGYquRfuhTqUwnGude26UC75NIR5mtWRbyIJ6piDEaedbW3dPeJjL5Gmj0fns5EW1tDa7aXBXpH/hwDWR+KFYD0rggc3p8uplB8VIhcqSslKShINtK58JhgdB04t89/1O/w1cDnyilFU=',
                'picture_url' => null,
            ],
        ];

        foreach ($managers as $manager) {
            User::create($manager);
        }

        foreach ($channels as $channel) {
            Channel::create($channel);
        }
    }
}
