<?php

declare(strict_types=1);

/**
 * Import Request Data DTO.
 *
 * Spatie Data DTO for import request validation and hydration.
 * Captures all parameters needed to initiate an import operation:
 * entity identifier, uploaded file, dry-run flag, and optional
 * CSV separator overrides.
 *
 * @category Data
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Contracts\ImportManagerInterface
 * @see \Pixielity\ImportExport\Controllers\ImportExportController
 */

namespace Pixielity\ImportExport\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

/**
 * Import Request Data DTO.
 *
 * Extends Spatie Data for automatic request validation and casting.
 * The `entity` and `file` fields are required; `dryRun` defaults
 * to false; CSV separator fields are optional overrides.
 *
 * Usage:
 *   ```php
 *   $request = ImportRequestData::from([
 *       'entity' => 'users',
 *       'file'   => $uploadedFile,
 *       'dryRun' => true,
 *   ]);
 *   $result = $importManager->dryRun($request);
 *   ```
 */
class ImportRequestData extends Data
{
    /**
     * Create a new ImportRequestData instance.
     *
     * @param  string        $entity               The entity identifier (e.g., 'users', 'products').
     * @param  UploadedFile  $file                 The uploaded import file.
     * @param  bool          $dryRun               Whether to run in dry-run mode (validate only, no persistence).
     * @param  string|null   $fieldSeparator       CSV field separator override; null = config default.
     * @param  string|null   $multiValueSeparator  Multi-value separator override; null = config default.
     * @param  string|null   $enclosure            Field enclosure override; null = config default.
     */
    public function __construct(
        /** 
         * @var string Entity identifier. 
         */
        #[Required, StringType]
        public string $entity,
        /** 
         * @var UploadedFile The uploaded import file. 
         */
        #[Required]
        public UploadedFile $file,
        /** 
         * @var bool Dry-run mode flag. 
         */
        public bool $dryRun = false,
        /** 
         * @var string|null CSV field separator override. 
         */
        public ?string $fieldSeparator = null,
        /** 
         * @var string|null Multi-value separator override. 
         */
        public ?string $multiValueSeparator = null,
        /** 
         * @var string|null Field enclosure override. 
         */
        public ?string $enclosure = null,
    ) {
    }
}
