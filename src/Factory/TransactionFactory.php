<?php

namespace App\Factory;

use App\Entity\Company;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Transaction>
 *
 * @method        Transaction|Proxy                     create(array|callable $attributes = [])
 * @method static Transaction|Proxy                     createOne(array $attributes = [])
 * @method static Transaction|Proxy                     find(object|array|mixed $criteria)
 * @method static Transaction|Proxy                     findOrCreate(array $attributes)
 * @method static Transaction|Proxy                     first(string $sortedField = 'id')
 * @method static Transaction|Proxy                     last(string $sortedField = 'id')
 * @method static Transaction|Proxy                     random(array $attributes = [])
 * @method static Transaction|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TransactionRepository|RepositoryProxy repository()
 * @method static Transaction[]|Proxy[]                 all()
 * @method static Transaction[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Transaction[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Transaction[]|Proxy[]                 findBy(array $attributes)
 * @method static Transaction[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Transaction[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class TransactionFactory extends ModelFactory
{

    public const STATUS = [
        'INITIATED' => 'Initiated',
        'PENDING' => 'Pending',
        'CANCEL' => 'Cancelled',
        'COMPLETE' => 'Completed',
    ];

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
            'company' => CompanyFactory::random(), // TODO add App\Entity\company type manually
            'createdAt' => self::faker()->dateTime(),
            'status' => self::STATUS[array_rand(self::STATUS)],
            'subscription' => SubscriptionFactory::random(),
            'updatedAt' => self::faker()->dateTime(),
            'orderId' => self::faker()->randomNumber(),
            'amount' => self::faker()->numberBetween(100, 2000)
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Transaction $transaction): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Transaction::class;
    }
}
