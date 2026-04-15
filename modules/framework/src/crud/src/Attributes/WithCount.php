<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * WithCount Attribute for Repository Classes.
 *
 * Declares default withCount relationships that are applied to every
 * query executed by the repository.
 *
 * @since 2.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class WithCount
{
    /** 
 * @var array<int, string> 
 */
    public array $relations;

    /**
     * @param  array<int, string>|string  ...$relations  Relation names to count.
     */
    public function __construct(array|string ...$relations)
    {
        $this->relations = is_array($relations[0] ?? null) ? $relations[0] : $relations;
    }
}
