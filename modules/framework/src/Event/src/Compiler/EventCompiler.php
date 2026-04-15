<?php

declare(strict_types=1);

/**
 * Event Compiler.
 *
 * Discovers all domain events (#[AsEvent]), subscribers (#[Subscriber]),
 * and their method bindings (#[On]). Builds an event registry mapping
 * events to their handlers.
 *
 * @category Compiler
 *
 * @since    1.0.0
 */

namespace Pixielity\Event\Compiler;

use Pixielity\Compiler\Attributes\AsCompiler;
use Pixielity\Compiler\Contracts\CompilerContext;
use Pixielity\Compiler\Contracts\CompilerInterface;
use Pixielity\Compiler\Contracts\CompilerResult;
use Pixielity\Compiler\Enums\CompilerPhase;
use Pixielity\Discovery\Facades\Discovery;
use Pixielity\Event\Attributes\AsEvent;
use Pixielity\Event\Attributes\On;
use Pixielity\Event\Attributes\Subscriber;

/**
 * Discovers events and subscribers, builds the event registry.
 */
#[AsCompiler(priority: 25, phase: CompilerPhase::REGISTRY, description: 'Discover domain events and subscribers')]
class EventCompiler implements CompilerInterface
{
    /**
     * {@inheritDoc}
     */
    public function compile(CompilerContext $context): CompilerResult
    {
        // Discover events
        $events = Discovery::attribute(AsEvent::class)->get();
        $eventCount = $events->count();

        // Discover subscribers
        $subscribers = Discovery::attribute(Subscriber::class)->get();
        $subscriberCount = $subscribers->count();

        // Build event → handlers map by scanning #[On] methods on each subscriber
        $eventMap = [];
        $handlerCount = 0;

        $subscribers->each(function (array $metadata, string $subscriberClass) use (&$eventMap, &$handlerCount): void {
            $forClass = Discovery::forClass($subscriberClass);

            foreach ($forClass->methodsAttributes as $methodName => $attrs) {
                foreach ($attrs as $attr) {
                    if ($attr instanceof On) {
                        $eventMap[$attr->event][] = [
                            'subscriber' => $subscriberClass,
                            'method' => $methodName,
                            'queue' => $attr->queue,
                            'connection' => $attr->connection,
                            'delay' => $attr->delay,
                            'afterCommit' => $attr->afterCommit,
                        ];
                        $handlerCount++;
                    }
                }
            }
        });

        // Store in context
        $context->set('events.map', $eventMap);
        $context->set('events.count', $eventCount);
        $context->set('events.subscribers_count', $subscriberCount);
        $context->set('events.handlers_count', $handlerCount);

        return CompilerResult::success(
            message: "Discovered {$eventCount} events, {$subscriberCount} subscribers, {$handlerCount} handlers",
            metrics: [
                'events' => $eventCount,
                'subscribers' => $subscriberCount,
                'handlers' => $handlerCount,
            ],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return 'Event Registry';
    }
}
