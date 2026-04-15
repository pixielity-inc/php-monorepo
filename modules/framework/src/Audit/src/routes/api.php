<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Pixielity\Audit\Controllers\AuditController;

Route::prefix('api/audit')->middleware('auth:sanctum')->group(function (): void {
    Route::get('recent', [AuditController::class, 'recent']);
    Route::get('me', [AuditController::class, 'me']);
    Route::get('user/{userId}', [AuditController::class, 'forUser']);
    Route::get('diff/{auditId}', [AuditController::class, 'diff']);
    Route::get('{type}/{id}', [AuditController::class, 'forSubject']);
});
