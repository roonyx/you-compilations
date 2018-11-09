<?php
/**
 * File: TagsTableSeeder.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

use Illuminate\Database\Seeder;
use App\Repositories\Compilations\TagRepository;

/**
 * Class TagsTableSeeder
 */
class TagsTableSeeder extends Seeder
{
    /**
     * List of tags
     */
    protected const TAGS = [
        'PHP',
        'C++',
        'C#',
        'Laravel',
        'Symfony',
        'Meetup',
        'Programming',
        'Roonyx',
    ];

    /**
     * Run the database seeds.
     *
     * @param TagRepository $repository
     * @return void
     */
    public function run(TagRepository $repository): void
    {
        foreach (static::TAGS as $tag) {
            $exists = $repository->isNameExist($tag);

            if ($exists == false) {
                try {
                    $repository->create(['name' => $tag]);
                } catch (Exception $exception) {
                    $this->command->error("Error when creating tag with name: {$tag}.");
                }
            }
        }
    }
}
