<?php

namespace App\Factory;

use App\Entity\Tasks;
use App\Repository\TasksRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Tasks>
 *
 * @method        Tasks|Proxy                     create(array|callable $attributes = [])
 * @method static Tasks|Proxy                     createOne(array $attributes = [])
 * @method static Tasks|Proxy                     find(object|array|mixed $criteria)
 * @method static Tasks|Proxy                     findOrCreate(array $attributes)
 * @method static Tasks|Proxy                     first(string $sortedField = 'id')
 * @method static Tasks|Proxy                     last(string $sortedField = 'id')
 * @method static Tasks|Proxy                     random(array $attributes = [])
 * @method static Tasks|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TasksRepository|RepositoryProxy repository()
 * @method static Tasks[]|Proxy[]                 all()
 * @method static Tasks[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Tasks[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Tasks[]|Proxy[]                 findBy(array $attributes)
 * @method static Tasks[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Tasks[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class TasksFactory extends ModelFactory
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
            'description' => self::faker()->text(),
            'isActive' => self::faker()->boolean(),
            'priority' => self::faker()->text(),
            'severity' => self::faker()->text(),
            'status' => self::faker()->text(),
            'time' => null, // TODO add TIME type manually
            'title' => self::faker()->text(90),
            'type' => self::faker()->text(),
            'updatedAt' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Tasks $tasks): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Tasks::class;
    }
}
