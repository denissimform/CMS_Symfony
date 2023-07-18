<?php

namespace App\Factory;

use App\Entity\Skills;
use App\Repository\SkillsRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Skills>
 *
 * @method        Skills|Proxy                     create(array|callable $attributes = [])
 * @method static Skills|Proxy                     createOne(array $attributes = [])
 * @method static Skills|Proxy                     find(object|array|mixed $criteria)
 * @method static Skills|Proxy                     findOrCreate(array $attributes)
 * @method static Skills|Proxy                     first(string $sortedField = 'id')
 * @method static Skills|Proxy                     last(string $sortedField = 'id')
 * @method static Skills|Proxy                     random(array $attributes = [])
 * @method static Skills|Proxy                     randomOrCreate(array $attributes = [])
 * @method static SkillsRepository|RepositoryProxy repository()
 * @method static Skills[]|Proxy[]                 all()
 * @method static Skills[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Skills[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Skills[]|Proxy[]                 findBy(array $attributes)
 * @method static Skills[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Skills[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class SkillsFactory extends ModelFactory
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
            'isActive' => self::faker()->boolean(),
            'isDeleted' => self::faker()->boolean(),
            'name' => self::faker()->text(40),
            'updatedAt' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Skills $skills): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Skills::class;
    }
}
