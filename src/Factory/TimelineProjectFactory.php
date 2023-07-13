<?php

namespace App\Factory;

use App\Entity\TimelineProject;
use App\Repository\TimelineProjectRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<TimelineProject>
 *
 * @method        TimelineProject|Proxy                     create(array|callable $attributes = [])
 * @method static TimelineProject|Proxy                     createOne(array $attributes = [])
 * @method static TimelineProject|Proxy                     find(object|array|mixed $criteria)
 * @method static TimelineProject|Proxy                     findOrCreate(array $attributes)
 * @method static TimelineProject|Proxy                     first(string $sortedField = 'id')
 * @method static TimelineProject|Proxy                     last(string $sortedField = 'id')
 * @method static TimelineProject|Proxy                     random(array $attributes = [])
 * @method static TimelineProject|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TimelineProjectRepository|RepositoryProxy repository()
 * @method static TimelineProject[]|Proxy[]                 all()
 * @method static TimelineProject[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static TimelineProject[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static TimelineProject[]|Proxy[]                 findBy(array $attributes)
 * @method static TimelineProject[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static TimelineProject[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class TimelineProjectFactory extends ModelFactory
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
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(TimelineProject $timelineProject): void {})
        ;
    }

    protected static function getClass(): string
    {
        return TimelineProject::class;
    }
}
