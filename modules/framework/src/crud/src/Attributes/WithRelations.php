<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * WithRelations Attribute for Repository Classes.
 *
 * Declares default eager-loaded relationships that are applied to every
 * query executed by the repository. Follows the same pattern as Laravel's
 * #[Touches] attribute for declaring relation arrays.
 *
 * @since 2.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class WithRelations
{
    /** 
 * @var array<int, string> 
 */
    public array $relations;

    /**
     * @param  array<int, string>|string  ...$relations  Relation names to eager load.
     */
    public function __construct(array|string ...$relations)
    {
        $this->relations = is_array($relations[0] ?? null) ? $relations[0] : $relations;
    }
}
