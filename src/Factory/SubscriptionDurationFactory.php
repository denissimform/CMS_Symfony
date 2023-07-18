<?php

namespace App\Factory;

use App\Entity\SubscriptionDuration;
use App\Repository\SubscriptionDurationRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<SubscriptionDuration>
 *
 * @method        SubscriptionDuration|Proxy                     create(array|callable $attributes = [])
 * @method static SubscriptionDuration|Proxy                     createOne(array $attributes = [])
 * @method static SubscriptionDuration|Proxy                     find(object|array|mixed $criteria)
 * @method static SubscriptionDuration|Proxy                     findOrCreate(array $attributes)
 * @method static SubscriptionDuration|Proxy                     first(string $sortedField = 'id')
 * @method static SubscriptionDuration|Proxy                     last(string $sortedField = 'id')
 * @method static SubscriptionDuration|Proxy                     random(array $attributes = [])
 * @method static SubscriptionDuration|Proxy                     randomOrCreate(array $attributes = [])
 * @method static SubscriptionDurationRepository|RepositoryProxy repository()
 * @method static SubscriptionDuration[]|Proxy[]                 all()
 * @method static SubscriptionDuration[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static SubscriptionDuration[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static SubscriptionDuration[]|Proxy[]                 findBy(array $attributes)
 * @method static SubscriptionDuration[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static SubscriptionDuration[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class SubscriptionDurationFactory extends ModelFactory
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
            'updatedAt' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(SubscriptionDuration $subscriptionDuration): void {})
        ;
    }

    protected static function getClass(): string
    {
        return SubscriptionDuration::class;
    }
}
