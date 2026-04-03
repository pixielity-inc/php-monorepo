<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Solutions;

use Pixielity\Foundation\Contracts\SolutionInterface;
use Spatie\ErrorSolutions\Contracts\BaseSolution as SpatieBaseSolution;

/**
 * Class Solution.
 *
 * Provides functionality to manage a solution's title, description, and documentation links.
 */
class Solution extends SpatieBaseSolution implements SolutionInterface
{
    /**
     * Set the solution's data based on the provided associative array.
     *
     * @param  array<string, mixed>  $data  An associative array containing solution data (title, description, and links).
     */
    public function setData(array $data = []): static
    {
        // Set the title, defaulting to the current title if not provided
        $this->setSolutionTitle($data[self::TITLE] ?? $this->title);

        // Set description if it exists in the data
        if (isset($data[self::DESCRIPTION])) {
            $this->setSolutionDescription($data[self::DESCRIPTION]);
        }

        // Set documentation links if they exist in the data
        if (isset($data[self::LINKS])) {
            $this->setDocumentationLinks($data[self::LINKS]);
        }

        // Return the current instance to allow method chaining
        return $this;
    }
}
