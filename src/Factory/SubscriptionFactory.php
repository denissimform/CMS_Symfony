<?php

namespace App\Factory;

use App\Entity\Subscription;
use App\Repository\SubscriptionRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Subscription>
 *
 * @method        Subscription|Proxy                     create(array|callable $attributes = [])
 * @method static Subscription|Proxy                     createOne(array $attributes = [])
 * @method static Subscription|Proxy                     find(object|array|mixed $criteria)
 * @method static Subscription|Proxy                     findOrCreate(array $attributes)
 * @method static Subscription|Proxy                     first(string $sortedField = 'id')
 * @method static Subscription|Proxy                     last(string $sortedField = 'id')
 * @method static Subscription|Proxy                     random(array $attributes = [])
 * @method static Subscription|Proxy                     randomOrCreate(array $attributes = [])
 * @method static SubscriptionRepository|RepositoryProxy repository()
 * @method static Subscription[]|Proxy[]                 all()
 * @method static Subscription[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Subscription[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Subscription[]|Proxy[]                 findBy(array $attributes)
 * @method static Subscription[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Subscription[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class SubscriptionFactory extends ModelFactory
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
            'criteria_dept' => self::faker()->numberBetween(1, 12),
            'criteria_storage' => self::faker()->numberBetween(100, 1000),
            'criteria_user' => self::faker()->numberBetween(100, 200),
            'isActive' => self::faker()->boolean(),
            'type' => self::faker()->text(10),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Subscription $subscription): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Subscription::class;
    }
}
