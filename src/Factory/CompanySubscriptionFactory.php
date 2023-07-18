<?php

namespace App\Factory;

use App\Entity\CompanySubscription;
use App\Repository\CompanySubscriptionRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<CompanySubscription>
 *
 * @method        CompanySubscription|Proxy create(array|callable $attributes = [])
 * @method static CompanySubscription|Proxy createOne(array $attributes = [])
 * @method static CompanySubscription|Proxy find(object|array|mixed $criteria)
 * @method static CompanySubscription|Proxy findOrCreate(array $attributes)
 * @method static CompanySubscription|Proxy first(string $sortedField = 'id')
 * @method static CompanySubscription|Proxy last(string $sortedField = 'id')
 * @method static CompanySubscription|Proxy random(array $attributes = [])
 * @method static CompanySubscription|Proxy randomOrCreate(array $attributes = [])
 * @method static CompanySubscriptionRepository|RepositoryProxy repository()
 * @method static CompanySubscription[]|Proxy[] all()
 * @method static CompanySubscription[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static CompanySubscription[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static CompanySubscription[]|Proxy[] findBy(array $attributes)
 * @method static CompanySubscription[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CompanySubscription[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class CompanySubscriptionFactory extends ModelFactory
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
            'expiresAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-1 years', '+1 years')),
            'status' => "current",
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(CompanySubscription $companySubscription): void {})
        ;
    }

    protected static function getClass(): string
    {
        return CompanySubscription::class;
    }
}
