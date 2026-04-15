<?php

// ============================================================================
// CACHED INTERCEPTOR MAP — AUTO-GENERATED
// ============================================================================
//
// This file shows what the AopScannerCompiler persists to:
//   bootstrap/cache/interceptors.php
//
// It's a plain PHP array loaded via require() at boot time — no
// serialization overhead, opcache-friendly, zero reflection.
//
// You NEVER edit this file. It's regenerated on every:
//   php artisan di:compile
//
// The InterceptorRegistry::load() method does:
//   $data = require 'bootstrap/cache/interceptors.php';
//   $map = InterceptorMap::fromArray($data);
//
// ============================================================================

return [
    // =========================================================================
    // entries: [TargetClass => [methodName => [InterceptorEntry, ...]]]
    // =========================================================================
    'entries' => [

        // OriginalService has 3 intercepted methods
        'Pixielity\\Aop\\Examples\\ProxyGeneration\\OriginalService' => [

            // findBySlug → 1 interceptor (CacheInterceptor)
            'findBySlug' => [
                [
                    // Which interceptor class handles this interception
                    'interceptorClass' => 'Pixielity\\Aop\\Examples\\CachingInterceptor\\CacheInterceptor',

                    // Execution priority — lower values execute first (outermost wrapper)
                    // CacheInterceptor has default priority 100
                    'priority' => 100,

                    // Optional runtime condition — null means always execute
                    // If set, the engine calls ConditionInterface::evaluate() before running
                    'whenCondition' => null,

                    // Parameters extracted from the #[Cache(ttl: 3600)] attribute
                    // These become $args['__parameters'] in the interceptor's handle() method
                    // The interceptor reads them via $this->param('ttl', $args, 3600)
                    'parameters' => [
                        'ttl' => 3600,
                        'prefix' => null,
                        'store' => null,
                    ],
                ],
            ],

            // createProduct → 2 interceptors (Transaction + Audit), sorted by priority
            'createProduct' => [
                [
                    'interceptorClass' => 'Pixielity\\Aop\\Examples\\TransactionAndAudit\\TransactionInterceptor',
                    'priority' => 50,       // Runs FIRST (outermost — wraps everything in DB::transaction)
                    'whenCondition' => null,
                    'parameters' => [
                        'attempts' => 3,    // From #[Transaction(attempts: 3)]
                        'connection' => null,
                    ],
                ],
                [
                    'interceptorClass' => 'Pixielity\\Aop\\Examples\\TransactionAndAudit\\AuditInterceptor',
                    'priority' => 100,      // Runs SECOND (inner — logs after method completes)
                    'whenCondition' => null,
                    'parameters' => [
                        'action' => 'product.created',  // From #[Audit(action: 'product.created')]
                        'logResult' => true,
                    ],
                ],
            ],

            // deleteProduct → 2 interceptors (Transaction + Audit)
            'deleteProduct' => [
                [
                    'interceptorClass' => 'Pixielity\\Aop\\Examples\\TransactionAndAudit\\TransactionInterceptor',
                    'priority' => 50,
                    'whenCondition' => null,
                    'parameters' => [
                        'attempts' => 1,    // Default — no retry
                        'connection' => null,
                    ],
                ],
                [
                    'interceptorClass' => 'Pixielity\\Aop\\Examples\\TransactionAndAudit\\AuditInterceptor',
                    'priority' => 100,
                    'whenCondition' => null,
                    'parameters' => [
                        'action' => 'product.deleted',
                        'logResult' => false,  // From #[Audit(logResult: false)]
                    ],
                ],
            ],

            // NOTE: getStats is NOT listed here because it has no interceptor attributes.
            // The proxy generator skips it, and the proxy class doesn't override it.
        ],
    ],

    // =========================================================================
    // globalInterceptors: interceptors applied to ALL methods matching a pattern
    // =========================================================================
    //
    // Configured in config/aop.php under 'global_interceptors'.
    // Example: apply AuditInterceptor to all methods in all services.
    //
    // These are merged with per-method interceptors at build time.
    // Methods with #[IgnoreInterceptor] are excluded.
    //
    'globalInterceptors' => [
        // Example (commented out — configured in aop.php):
        // [
        //     'interceptor' => 'App\\Interceptors\\AuditInterceptor',
        //     'pattern' => 'App\\Services\\*',
        //     'priority' => 200,
        // ],
    ],
];
