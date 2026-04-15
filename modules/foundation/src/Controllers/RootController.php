<?php

namespace Pixielity\Foundation\Controllers;

use Pixielity\Foundation\Enums\HttpStatusCode;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Attributes\Get;
use Pixielity\Routing\Attributes\Middleware;
use Pixielity\Routing\BaseController;

/**
 * Home Controller.
 *
 * Handles the root route of the application. This controller is primarily used
 * for testing error pages and exception handling.
 */
#[AsController]
#[Middleware('web')]
class RootController extends BaseController
{
    /**
     * Root route - redirects to 401 test.
     */
    #[Get(uri: '/', name: 'root')]
    public function root(): void
    {
        abort(HttpStatusCode::UNAUTHORIZED(), (string) __('foundation::errors.401_message'));
    }
}
