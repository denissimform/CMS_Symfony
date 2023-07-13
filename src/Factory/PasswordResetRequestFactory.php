<?php

namespace App\Factory;

use App\Entity\PasswordResetRequest;
use App\Repository\PasswordResetRequestRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<PasswordResetRequest>
 *
 * @method        PasswordResetRequest|Proxy                     create(array|callable $attributes = [])
 * @method static PasswordResetRequest|Proxy                     createOne(array $attributes = [])
 * @method static PasswordResetRequest|Proxy                     find(object|array|mixed $criteria)
 * @method static PasswordResetRequest|Proxy                     findOrCreate(array $attributes)
 * @method static PasswordResetRequest|Proxy                     first(string $sortedField = 'id')
 * @method static PasswordResetRequest|Proxy                     last(string $sortedField = 'id')
 * @method static PasswordResetRequest|Proxy                     random(array $attributes = [])
 * @method static PasswordResetRequest|Proxy                     randomOrCreate(array $attributes = [])
 * @method static PasswordResetRequestRepository|RepositoryProxy repository()
 * @method static PasswordResetRequest[]|Proxy[]                 all()
 * @method static PasswordResetRequest[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static PasswordResetRequest[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static PasswordResetRequest[]|Proxy[]                 findBy(array $attributes)
 * @method static PasswordResetRequest[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static PasswordResetRequest[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class PasswordResetRequestFactory extends ModelFactory
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
            'expiresAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'token' => self::faker()->text(255),
            'updatedAt' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(PasswordResetRequest $passwordResetRequest): void {})
        ;
    }

    protected static function getClass(): string
    {
        return PasswordResetRequest::class;
    }
}
