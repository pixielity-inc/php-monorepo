<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Attributes\Meta;
use Pixielity\Enum\Enum;

/**
 * Order Status Enum.
 *
 * Represents the various states an order can be in throughout its lifecycle.
 * This enum provides labeled and described status values for order management
 * and tracking purposes.
 *
 * ## Features:
 * - Labeled status values for display purposes
 * - Detailed descriptions for each status
 * - Type-safe status handling
 * - Easy conversion to arrays and options
 *
 * ## Usage:
 * ```php
 * // Get status value
 * $status = OrderStatus::PENDING->value; // 'pending'
 *
 * // Get label
 * $label = OrderStatus::PENDING->label(); // 'Order Pending'
 *
 * // Get description
 * $description = OrderStatus::PENDING->description(); // 'Order has been placed...'
 *
 * // Get all status names
 * $names = OrderStatus::names(); // ['PENDING', 'PROCESSING', 'SHIPPED', ...]
 *
 * // Get all status values
 * $values = OrderStatus::values(); // ['pending', 'processing', 'shipped', ...]
 *
 * // Get options for select dropdown
 * $options = OrderStatus::options(); // ['PENDING' => 'pending', ...]
 *
 * // Compare statuses
 * if ($order->status->is(OrderStatus::DELIVERED)) {
 *     // Order has been delivered
 * }
 *
 * // Check if status is in a set
 * if ($order->status->in([OrderStatus::SHIPPED, OrderStatus::DELIVERED])) {
 *     // Order is either shipped or delivered
 * }
 * ```
 *
 * @author  Pixielity Development Team
 *
 * @since   1.0.0
 */
#[Meta([Description::class, Label::class])]
enum OrderStatus: string
{
    use Enum;

    /**
     * Pending Order Status.
     *
     * Indicates that an order has been placed and is awaiting processing.
     * This is the initial state of a new order.
     */
    #[Label('Order Pending')]
    #[Description('Order has been placed and is awaiting processing')]
    case PENDING = 'pending';

    /**
     * Processing Order Status.
     *
     * Indicates that the order is currently being processed, which may include
     * payment verification, inventory allocation, and preparation for shipment.
     */
    #[Label('Order Processing')]
    #[Description('Order is currently being processed')]
    case PROCESSING = 'processing';

    /**
     * Shipped Order Status.
     *
     * Indicates that the order has been shipped and is in transit to the customer.
     * Tracking information is typically available at this stage.
     */
    #[Label('Order Shipped')]
    #[Description('Order has been shipped to the customer')]
    case SHIPPED = 'shipped';

    /**
     * Delivered Order Status.
     *
     * Indicates that the order has been successfully delivered to the customer
     * and the transaction is complete.
     */
    #[Label('Order Delivered')]
    #[Description('Order has been successfully delivered')]
    case DELIVERED = 'delivered';

    /**
     * Cancelled Order Status.
     *
     * Indicates that the order has been cancelled either by the customer,
     * the system, or an administrator. No further processing will occur.
     */
    #[Label('Order Cancelled')]
    #[Description('Order has been cancelled by the customer or system')]
    case CANCELLED = 'cancelled';
}
