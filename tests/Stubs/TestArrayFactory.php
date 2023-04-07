<?php

namespace Ensi\TestFactories\Tests\Stubs;

use Ensi\TestFactories\Factory;

class TestArrayFactory extends Factory
{
    public ?int $id = null;

    protected function definition(): array
    {
        return [
            'id' => $this->whenNotNull($this->id, $this->id),
            'user_id' => $this->faker->randomNumber(),
        ];
    }

    public function make(array $extra = [])
    {
        return $this->makeArray($extra);
    }
}
