<?php

namespace App\Factory;

use App\Entity\Timesheets;
use App\Repository\TimesheetsRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Timesheets>
 *
 * @method        Timesheets|Proxy                     create(array|callable $attributes = [])
 * @method static Timesheets|Proxy                     createOne(array $attributes = [])
 * @method static Timesheets|Proxy                     find(object|array|mixed $criteria)
 * @method static Timesheets|Proxy                     findOrCreate(array $attributes)
 * @method static Timesheets|Proxy                     first(string $sortedField = 'id')
 * @method static Timesheets|Proxy                     last(string $sortedField = 'id')
 * @method static Timesheets|Proxy                     random(array $attributes = [])
 * @method static Timesheets|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TimesheetsRepository|RepositoryProxy repository()
 * @method static Timesheets[]|Proxy[]                 all()
 * @method static Timesheets[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Timesheets[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Timesheets[]|Proxy[]                 findBy(array $attributes)
 * @method static Timesheets[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Timesheets[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class TimesheetsFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'createdAt' => self::faker()->dateTime(),
            'hoursWorked' => null, // TODO add TIME type manually
            'isActive' => self::faker()->boolean(),
            'updatedAt' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Timesheets $timesheets): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Timesheets::class;
    }
}
