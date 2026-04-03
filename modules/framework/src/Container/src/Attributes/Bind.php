<?php

namespace Pixielity\Container\Attributes;

use Attribute;
use BackedEnum;
use InvalidArgumentException;
use UnitEnum;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Bind
{
    /**
     * The environments the binding should apply for.
     *
     * @var non-empty-array<int, string>
     */
    public array $environments = [];

    /**
     * Create a new attribute instance.
     *
     * @param  class-string  $abstract
     * @param  non-empty-array<int, BackedEnum|UnitEnum|non-empty-string>|non-empty-string|UnitEnum  $environments
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        /**
         * The abstract interface or class to bind to.
         */
        public string $abstract,
        string|array|UnitEnum $environments = ['*'],
    ) {
        $environments = array_filter(is_array($environments) ? $environments : [$environments]);
        throw_if($environments === [], InvalidArgumentException::class, 'The environment property must be set and cannot be empty.');

        $this->environments = array_map(fn ($environment): int|string => match (true) {
            $environment instanceof BackedEnum => $environment->value,
            $environment instanceof UnitEnum => $environment->name,
            default => $environment,
        }, $environments);
    }
}
