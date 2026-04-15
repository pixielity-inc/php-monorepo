<?php

declare(strict_types=1);

namespace Pixielity\Routing\Concerns;

use Pixielity\Crud\Attributes\UseData;
use Pixielity\Support\Reflection;
use Spatie\LaravelData\Data;

/**
 * Interacts With Data Transformation Trait.
 *
 * Provides automatic input/output transformation using Spatie Laravel Data DTOs.
 * Reads the `#[UseData]` attribute from the controller class via reflection
 * and provides methods for resolving, validating, and transforming data.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Crud\Attributes\UseData;
 * use App\Data\UserData;
 *
 * #[UseData(UserData::class)]
 * class UserController extends Controller
 * {
 *     public function store(Request $request)
 *     {
 *         $dto = $this->transformInput($request->all());
 *         $user = $this->service()->create($dto->toArray());
 *
 *         return $this->created($this->transformOutput($user));
 *     }
 * }
 * ```
 *
 * @category   Concerns
 *
 * @since      2.0.0
 */
trait InteractsWithDataTransformation
{
    /**
     * Cached Data class name resolved from the #[UseData] attribute.
     *
     * @var class-string<Data>|null
     */
    private ?string $resolvedDataClass = null;

    /**
     * Resolve the Data DTO class from the #[UseData] attribute.
     *
     * Reads the `#[UseData]` attribute from the current controller class
     * and returns the configured Spatie Data class name. The result is
     * cached for subsequent calls.
     *
     * @return class-string<Data>|null The Data class name, or null if no attribute is present.
     */
    protected function resolveDataClass(): ?string
    {
        if ($this->resolvedDataClass !== null) {
            return $this->resolvedDataClass;
        }

        $attributes = Reflection::getAttributes($this, UseData::class);

        if ($attributes === []) {
            return null;
        }

        /** @var UseData $useData */
        $useData = $attributes[0]->newInstance();

        $this->resolvedDataClass = $useData->class;

        return $this->resolvedDataClass;
    }

    /**
     * Transform input data into a Data DTO.
     *
     * Validates and transforms the given input array into the Spatie Data
     * DTO class specified by the `#[UseData]` attribute. Uses `Data::from()`
     * which performs validation automatically.
     *
     * @param  array<string, mixed>  $input  Raw input data to transform.
     * @return Data|null The transformed Data DTO, or null if no #[UseData] attribute is present.
     */
    protected function transformInput(array $input): ?Data
    {
        $dataClass = $this->resolveDataClass();

        if ($dataClass === null) {
            return null;
        }

        /** @var Data $dataClass */
        return $dataClass::from($input);
    }

    /**
     * Transform output data into a Data DTO.
     *
     * Transforms the given model or data into the Spatie Data DTO class
     * specified by the `#[UseData]` attribute. Uses `Data::from()` which
     * handles Eloquent models, arrays, and other supported types.
     *
     * @param  mixed  $data  The model or data to transform.
     * @return Data|null The transformed Data DTO, or null if no #[UseData] attribute is present.
     */
    protected function transformOutput(mixed $data): ?Data
    {
        $dataClass = $this->resolveDataClass();

        if ($dataClass === null) {
            return null;
        }

        /** @var Data $dataClass */
        return $dataClass::from($data);
    }
}
