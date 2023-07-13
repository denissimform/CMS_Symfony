<?php

namespace App\Factory;

use App\Entity\ModesOfConversation;
use App\Repository\ModesOfConversationRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ModesOfConversation>
 *
 * @method        ModesOfConversation|Proxy                     create(array|callable $attributes = [])
 * @method static ModesOfConversation|Proxy                     createOne(array $attributes = [])
 * @method static ModesOfConversation|Proxy                     find(object|array|mixed $criteria)
 * @method static ModesOfConversation|Proxy                     findOrCreate(array $attributes)
 * @method static ModesOfConversation|Proxy                     first(string $sortedField = 'id')
 * @method static ModesOfConversation|Proxy                     last(string $sortedField = 'id')
 * @method static ModesOfConversation|Proxy                     random(array $attributes = [])
 * @method static ModesOfConversation|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ModesOfConversationRepository|RepositoryProxy repository()
 * @method static ModesOfConversation[]|Proxy[]                 all()
 * @method static ModesOfConversation[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ModesOfConversation[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static ModesOfConversation[]|Proxy[]                 findBy(array $attributes)
 * @method static ModesOfConversation[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ModesOfConversation[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ModesOfConversationFactory extends ModelFactory
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
            'name' => self::faker()->text(20),
            'updatedAt' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ModesOfConversation $modesOfConversation): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ModesOfConversation::class;
    }
}
