<?php

namespace App\Factory;

use App\Entity\Documents;
use App\Repository\DocumentsRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Documents>
 *
 * @method        Documents|Proxy                     create(array|callable $attributes = [])
 * @method static Documents|Proxy                     createOne(array $attributes = [])
 * @method static Documents|Proxy                     find(object|array|mixed $criteria)
 * @method static Documents|Proxy                     findOrCreate(array $attributes)
 * @method static Documents|Proxy                     first(string $sortedField = 'id')
 * @method static Documents|Proxy                     last(string $sortedField = 'id')
 * @method static Documents|Proxy                     random(array $attributes = [])
 * @method static Documents|Proxy                     randomOrCreate(array $attributes = [])
 * @method static DocumentsRepository|RepositoryProxy repository()
 * @method static Documents[]|Proxy[]                 all()
 * @method static Documents[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Documents[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Documents[]|Proxy[]                 findBy(array $attributes)
 * @method static Documents[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Documents[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class DocumentsFactory extends ModelFactory
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
            'filename' => self::faker()->text(100),
            'path' => self::faker()->text(255),
            'referenceId' => self::faker()->randomNumber(),
            'referenceType' => self::faker()->text(),
            'updatedAt' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Documents $documents): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Documents::class;
    }
}
