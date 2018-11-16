<?php
/**
 * File: UsersTableSeeder.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

use Illuminate\Database\Seeder;
use App\Repositories\Users\UserRepository;

/**
 * Class UsersTableSeeder
 */
class UsersTableSeeder extends Seeder
{
    /**
     * List of users
     */
    protected const DATA = [
        [
            'name' => 'Vladimir Pogarsky',
            'email' => 'vladimir.pogarsky@roonyx.team',
            'email_verified_at' => '2018-11-07 05:11:37',
            'password' => '12345',
        ],
        [
            'name' => 'Alexey Chubukov',
            'email' => 'chubukov.alexey@roonyx.team',
            'email_verified_at' => '2018-11-07 05:11:37',
            'password' => '12345',
        ],
        [
            'name' => 'test',
            'email' => 'test@gmail.com',
            'email_verified_at' => '2018-11-07 05:11:37',
            'password' => 'test',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @param UserRepository $repository
     * @return void
     * @throws Exception
     */
    public function run(UserRepository $repository): void
    {
        foreach (self::DATA as $datum) {
            $this->command->info("Created or updated \"{$datum['email']}\" with \"{$datum['password']}\" password");

            $datum['password'] = empty($datum['password'])
                ? str_random()
                : Hash::make($datum['password']);

            try {
                $user = $repository->findByEmail($datum['email']);

                $updateData = $datum;
                unset($updateData['email'], $updateData['password']);

                $repository->update($user, $updateData);

                $this->command->info("Updated user with \"{$datum['email']}\" email");
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $repository->create($datum);
            }
        }
    }
}
