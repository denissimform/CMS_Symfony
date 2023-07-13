<?php

namespace App\Factory;

use App\Entity\Bills;
use App\Repository\BillsRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Bills>
 *
 * @method        Bills|Proxy                     create(array|callable $attributes = [])
 * @method static Bills|Proxy                     createOne(array $attributes = [])
 * @method static Bills|Proxy                     find(object|array|mixed $criteria)
 * @method static Bills|Proxy                     findOrCreate(array $attributes)
 * @method static Bills|Proxy                     first(string $sortedField = 'id')
 * @method static Bills|Proxy                     last(string $sortedField = 'id')
 * @method static Bills|Proxy                     random(array $attributes = [])
 * @method static Bills|Proxy                     randomOrCreate(array $attributes = [])
 * @method static BillsRepository|RepositoryProxy repository()
 * @method static Bills[]|Proxy[]                 all()
 * @method static Bills[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Bills[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Bills[]|Proxy[]                 findBy(array $attributes)
 * @method static Bills[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Bills[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class BillsFactory extends ModelFactory
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
            'amount' => self::faker()->randomNumber(),
            'createdAt' => self::faker()->dateTime(),
            'isActive' => self::faker()->boolean(),
            'status' => self::faker()->text(20),
            'updatedAt' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Bills $bills): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Bills::class;
    }
}
