<?php

namespace App\Factory;

use App\Entity\Request;
use App\Repository\RequestRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Request>
 *
 * @method        Request|Proxy                     create(array|callable $attributes = [])
 * @method static Request|Proxy                     createOne(array $attributes = [])
 * @method static Request|Proxy                     find(object|array|mixed $criteria)
 * @method static Request|Proxy                     findOrCreate(array $attributes)
 * @method static Request|Proxy                     first(string $sortedField = 'id')
 * @method static Request|Proxy                     last(string $sortedField = 'id')
 * @method static Request|Proxy                     random(array $attributes = [])
 * @method static Request|Proxy                     randomOrCreate(array $attributes = [])
 * @method static RequestRepository|RepositoryProxy repository()
 * @method static Request[]|Proxy[]                 all()
 * @method static Request[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Request[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Request[]|Proxy[]                 findBy(array $attributes)
 * @method static Request[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Request[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class RequestFactory extends ModelFactory
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
            'approvedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'isApproved' => self::faker()->boolean(),
            'reason' => self::faker()->text(),
            'requestAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Request $request): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Request::class;
    }
}
