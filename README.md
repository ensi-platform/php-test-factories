# PHP Test factories

Define factories to generate any kind of object or even arrays for unit tests.

## Installation

You can install the package via composer:

`composer require ensi/test-factories --dev`

## Basic usage

Let's create a factory and extend abstract Factory.
All you need is to define `definition` and `make` methods.

```php
use Ensi\TestFactories\Factory;

class CustomerFactory extends Factory
{
    public ?int $id = null;
    public ?FileFactory $avatarFactory = null;
    public ?array $addressFactories = null;

    protected function definition(): array
    {
        return [
            'id' => $this->whenNotNull($this->id, $this->id),
            'user_id' => $this->faker->randomNumber(),
            'is_active' => $this->faker->boolean(),
            'date_start' => $this->faker->dateTime(),
            'avatar' => $this->avatarFactory?->make(),
            'addresses' => $this->executeNested($this->addressFactories, new FactoryMissingValue()),
        ];
    }

    public function make(array $extra = []): CustomerDTO
    {
        static::$index += 1;

        return new CustomerDTO($this->mergeDefinitionWithExtra($extra));
    }

    public function withId(?int $id = null): self
    {
        return $this->immutableSet('id', $id ?? $this->faker->randomNumber());
    }

    public function withAvatar(FileFactory $factory = null): self
    {
        return $this->immutableSet('avatarFactory', $factory ?? FileFactory::new());
    }

    public function includesAddresses(?array $factories = null): self
    {
        return $this->immutableSet('addressFactories', $factories ?? [CustomerAddressFactory::new()]);
    }

    public function active(): self
    {
        return $this->state([
            'is_active' => true,
            'date_start' => $this->faker->dateTimeBetween('-30 years', 'now'),
        ]);
    }
}

// Now we can use Factory like that
$customerData1 = CustomerFactory::new()->make();
$customerData2 = CustomerFactory::new()->active()->make();
$customerData3 = CustomerFactory::new()->withId()->withAvatar(FileFactory::new()->someCustomMethod())->make();
```

As you can see the package uses `fakerphp/faker` to generate test data.

You can override any fields in `make` method:

```php
$customerData1 = CustomerFactory::new()->make(['user_id' => 2]);
```

If you target is an array, then you can use a helper method `makeArray`:

```php
    public function make(array $extra = []): array
    {
        return $this->makeArray($extra);
    }
```

It's recommended to use `$this->immutableSet` in state change methods to make sure previously created factories are not affected.

### Making several objects

```php
$customerDataObjects = CustomerFactory::new()->makeSeveral(3); // returns Illuminate\Support\Collection with 3 elements
```

## Additional Faker methods

```php
$this->faker->randomList(fn() => $this->faker->numerify(), 0, 10) // => ['123', ..., '456']
$this->faker->nullable() // equivalent for $this->faker->optional(), but work with boolean parameter or global static setting
$this->faker->exactly($value) // return $value. Example: $this->faker->nullable()->exactly(AnotherFactory::new()->make())
$this->faker->carbon() // return CarbonInterface
$this->faker->dateMore()
$this->faker->modelId() // return unsigned bit integer value
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

### Testing

1. composer install
2. npm i
3. composer test

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
