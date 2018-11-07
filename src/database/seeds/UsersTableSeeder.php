<?php
/**
 * File: UsersTableSeeder.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

use Illuminate\Database\Seeder;
use Repositories\Users\UserRepository;

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
            'email' => 'pogarsky.vladimir@roonyx.team',
            'email_verified_at' => true,
            'password' => '12345',
        ],
        [
            'name' => 'Alexey Chubukov',
            'email' => 'chubukov.alexey@roonyx.team',
            'email_verified_at' => true,
            'password' => '12345',
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
            if (!isset($datum['password']) || '' === $datum['password']) {
                $datum['password'] = str_random();
            }

            try {
                $user = $repository->findByEmail($datum['email']);

                $updateData = $datum;
                unset($updateData['email'], $updateData['password']);

                $repository->update($user, $updateData);

                $this->command->info("Updated user with \"{$datum['email']}\" email");
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $repository->create($datum);
                $this->command->info("Created \"{$datum['email']}\" with \"{$datum['password']}\" password");
            }
        }
    }
}
