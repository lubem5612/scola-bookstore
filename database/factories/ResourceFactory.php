<?php

namespace Transave\ScolaBookstore\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Transave\ScolaBookstore\Http\Models\Author;
use Transave\ScolaBookstore\Http\Models\Resource;

class ResourceFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Resource::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'author_id' => Author::factory(),
            'title' => $this->faker->title,
            'subtitle' => $this->faker->sentence,
            'preface' => $this->faker->sentence,
            'source' => $this->faker->title,
            'page_url' => $this->faker->url,
            'pages' => $this->faker->randomDigit(),
            'contributors' => json_encode(['authors' => $this->faker->name]),
            'abstract' => $this->faker->sentence(15, true),
            'content' => $this->faker->sentence(20),
            'ISBN' => $this->faker->word,
            'publication_info' => json_encode([
                'date' => $this->faker->dateTime,
                'publisher' => $this->faker->name,
                'place' => $this->faker->city
            ]),
            'report_info' => json_encode([
                'report_number' => $this->faker->randomDigit(),
                'organization' => $this->faker->title,
                'funding' => $this->faker->address,
                'license' => $this->faker->word
            ]),
            'institutional_affiliations' => json_encode(['affiliate' => $this->faker->address]),
            'editors' => json_encode(['editors' => $this->faker->name]),
            'dedicatees' => json_encode(['dedicatees' => $this->faker->name]),
            'journal_info' => json_encode([
                'volume' => $this->faker->title,
                'page_start' => $this->faker->randomDigit(),
                'page_end' => $this->faker->randomDigit(),
                'editorial' => $this->faker->sentence(20)
            ]),
            'editorial_board_members' => json_encode(['members' => $this->faker->sentence]),
            'edition' => $this->faker->title,
            'keywords' => $this->faker->word,
            'summary' => $this->faker->sentence(5),
            'overview' => $this->faker->sentence(3),
            'conference' => json_encode([
                'name' => $this->faker->name,
                'date' => $this->faker->dateTime,
                'location' => $this->faker->address
            ]),
            'file_path' =>  UploadedFile::fake()->create('file.pdf', 2000, 'application/pdf'),
            'cover_image' => UploadedFile::fake()->image('photo.jpg'),
            'price' => $this->faker->randomFloat(2, 10000, 20000),
            'percentage_share' => $this->faker->randomFloat(1, 10, 90),
        ];

    }

}

