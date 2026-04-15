<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Exceptions;

/**
 * Class NodeNotFoundException.
 *
 * Exception thrown when a node is not found.
 *
 * This custom exception is specifically used to handle situations where a
 * node expected by the mail service is not available, enabling more
 * meaningful error handling and debugging in the framework.
 */
class NodeNotFoundException extends Exception {}
