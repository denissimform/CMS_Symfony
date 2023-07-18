<?php

namespace App\Factory;

use App\Entity\EmployeeSkills;
use App\Repository\EmployeeSkillsRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<EmployeeSkills>
 *
 * @method        EmployeeSkills|Proxy                     create(array|callable $attributes = [])
 * @method static EmployeeSkills|Proxy                     createOne(array $attributes = [])
 * @method static EmployeeSkills|Proxy                     find(object|array|mixed $criteria)
 * @method static EmployeeSkills|Proxy                     findOrCreate(array $attributes)
 * @method static EmployeeSkills|Proxy                     first(string $sortedField = 'id')
 * @method static EmployeeSkills|Proxy                     last(string $sortedField = 'id')
 * @method static EmployeeSkills|Proxy                     random(array $attributes = [])
 * @method static EmployeeSkills|Proxy                     randomOrCreate(array $attributes = [])
 * @method static EmployeeSkillsRepository|RepositoryProxy repository()
 * @method static EmployeeSkills[]|Proxy[]                 all()
 * @method static EmployeeSkills[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static EmployeeSkills[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static EmployeeSkills[]|Proxy[]                 findBy(array $attributes)
 * @method static EmployeeSkills[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static EmployeeSkills[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class EmployeeSkillsFactory extends ModelFactory
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
            'level' => self::faker()->text(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(EmployeeSkills $employeeSkills): void {})
        ;
    }

    protected static function getClass(): string
    {
        return EmployeeSkills::class;
    }
}
