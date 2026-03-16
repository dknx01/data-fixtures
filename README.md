# DKNX01/Data-fixtures
A PHP package for data fixtures with plain PDO connection.

## Requirements
* ext-pdo for PDO connections
* (optional, but recommended) [Faker](https://fakerphp.org)

## Install
2. run `composer require --dev dknx01/data-fixtures-phpunit`

## Usage
If you want to use data fixtures in you tests you can do it in multiple ways.
You can write a method to fill data in the database. or you can use a fixture and reuse it in multiple tests.

### With an existing PDO connection
If you already have a PDO connection you can use it directly.

```php
new Configuration(pdo: $pdo, databaseName: 'foo');
```
### Create a new PDO connection
```php
new Configuration(dsn: 'mysql:host=localhost;port=3307;dbname=testdb', user: 'foo', password: 'bar', options: []);
```

### Configuration:
The Configuration object has the following parameters:

| Parameter | Description                                      |
|-----------|--------------------------------------------------|
| pdo       | An existing PDO connection that will be used     |
| dsn       | The dsn of the PDO connection                    |
| user      | Databases user, if needed                        |
| password  | Databases users password, if needed              |
| options   | PDO options that will be used for the connection |

### Load and execute fixtures
Your class should use the `DataFixtureTrait`.
```php
/// your code
    $this->loadFixtures();
    $this->executeFixtures($configuration);
/// your code
```
If you do not want to use the trait, you can load and execute the fixtures this way:
```php 
// your code
    $fixtures = new FixtureCollection();
    $fixtures->add(new SimpleFixture());
    $handler = new FixtureHandler($configuration);
    $handler->handle($fixtures);
// your code
```
__loadFixtures(int $stackPosition = 1)__

This option should be used to define at which stack position the fixtures should be loaded from.
Mostly it will be "1" which means directly from the method/class the "loadFixtures" method is called from.
If you have more inherited classes, you can change the number.

### Writing a fixture
Each fixture must implement the `Dknx01\DataFixtures\Contract\FixtureInterface`.
Example:
``` php
<?php

namespace App\Tests\Fixtures;

use App\Entity\User;
use Dknx01\DataFixtures\Contract\FakerAware;
use Dknx01\DataFixtures\Contract\FixtureInterface;
use Faker\Generator;

readonly class UserFixture implements FixtureInterface, FakerAware
{
    private Generator $faker;

    public function __construct(private string $email = '')
    {
    }

    public function setFaker(Generator $faker): void
    {
        $this->faker = $faker;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail(!empty($this->email) ? $this->email : $this->faker->unique()->safeEmail());
        $user->setPassword('foo');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();
    }
}

#[DataFixture(new UserFixture('test@fooo.nlkdjlfs'))]
class ApiTest extends ApplicationTestCase {
 // your code
}

#[DataFixture(UserFixture::class)]
class ApiTest extends ApplicationTestCase {
    // your code
}
```
As you can see the fixture can have constructor arguments for individual data in different tests.
#### Data Fixture on method level
Data fixtures can be used on class level (see above) and on method level.
```php
#[DataFixture(UserFixture::class)]
#[DataFixture(new BlaFixture(
    name: 'Test123',
    fileName: 'Test123'
))]
public function testFoo(): void
{
    // cor code
}
```
#### Dependent Fixtures
Fixtures can depend on other fixtures.
```php
#[DependFixture(BarFixture::class)]
class FooFixture implements FixtureInterface, FakerAware
{
    // your code
}
```

#### Ordered Fixtures
Fixtures can have an order at which position they should load in the fixture loading process.

**If a position is added multiple times, the early will move one step ahead of the latest ones.**

Example:
```php
// FIXTURE 1
#[\Dknx01\DataFixtures\Attributes\OrderedFixture(1)]
// FIXTURE 2
#[\Dknx01\DataFixtures\Attributes\OrderedFixture(1)]
// Result:
[
    0 => 'FIXTURE 1',
    1 => 'FIXTURE 2'
]
```
**This attribute will overwrite the DependFixture attribute!**
```php
#[\Dknx01\DataFixtures\Attributes\OrderedFixture(0)]
class FooFixture implements FixtureInterface, FakerAware
{
    // your code
}
```
In this example the FooFixture is loaded at position 0 which means at the beginning of all fixtures.
### Faker
As you can see it is possible to use PHPFaker inside a fixture class.

If you implement the `FakerAware` interface a Faker instance is automatically injected into the data fixture.

### Examples

You can see some examples in the directory `examples` or you can run the script
```shell
php examples/Examples.php
```
