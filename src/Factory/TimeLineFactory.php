<?php

namespace App\Factory;

use App\Entity\TimeLine;
use App\Repository\TimeLineRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<TimeLine>
 *
 * @method        TimeLine|Proxy                     create(array|callable $attributes = [])
 * @method static TimeLine|Proxy                     createOne(array $attributes = [])
 * @method static TimeLine|Proxy                     find(object|array|mixed $criteria)
 * @method static TimeLine|Proxy                     findOrCreate(array $attributes)
 * @method static TimeLine|Proxy                     first(string $sortedField = 'id')
 * @method static TimeLine|Proxy                     last(string $sortedField = 'id')
 * @method static TimeLine|Proxy                     random(array $attributes = [])
 * @method static TimeLine|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TimeLineRepository|RepositoryProxy repository()
 * @method static TimeLine[]|Proxy[]                 all()
 * @method static TimeLine[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static TimeLine[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static TimeLine[]|Proxy[]                 findBy(array $attributes)
 * @method static TimeLine[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static TimeLine[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class TimeLineFactory extends ModelFactory
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
            'conclusion' => self::faker()->text(),
            'createdAt' => self::faker()->dateTime(),
            'decription' => self::faker()->text(),
            'updatedAt' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(TimeLine $timeLine): void {})
        ;
    }

    protected static function getClass(): string
    {
        return TimeLine::class;
    }
}
