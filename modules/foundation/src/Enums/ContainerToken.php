<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing Laravel service container binding tokens.
 *
 * @method static APP() Returns the APP enum instance
 * @method static CONFIG() Returns the CONFIG enum instance
 * @method static DB() Returns the DB enum instance
 * @method static CACHE() Returns the CACHE enum instance
 * @method static EVENTS() Returns the EVENTS enum instance
 * @method static FILES() Returns the FILES enum instance
 * @method static LOG() Returns the LOG enum instance
 * @method static QUEUE() Returns the QUEUE enum instance
 * @method static REDIS() Returns the REDIS enum instance
 * @method static REQUEST() Returns the REQUEST enum instance
 * @method static ROUTER() Returns the ROUTER enum instance
 * @method static SESSION() Returns the SESSION enum instance
 * @method static URL() Returns the URL enum instance
 * @method static VALIDATOR() Returns the VALIDATOR enum instance
 * @method static VIEW() Returns the VIEW enum instance
 * @method static AUTH() Returns the AUTH enum instance
 * @method static HASH() Returns the HASH enum instance
 * @method static MAIL() Returns the MAIL enum instance
 * @method static NOTIFICATION() Returns the NOTIFICATION enum instance
 * @method static STORAGE() Returns the STORAGE enum instance
 * @method static TRANSLATOR() Returns the TRANSLATOR enum instance
 * @method static ENV() Returns the ENV enum instance
 * @method static EVENT() Returns the EVENT enum instance
 */
enum ContainerToken: string
{
    use Enum;

    /**
     * Application instance.
     */
    #[Label('App')]
    #[Description('The main application instance.')]
    case APP = 'app';

    /**
     * Configuration repository.
     */
    #[Label('Config')]
    #[Description('Configuration repository for accessing application settings.')]
    case CONFIG = 'config';

    /**
     * Database manager.
     */
    #[Label('Database')]
    #[Description('Database manager for database connections and queries.')]
    case DB = 'db';

    /**
     * Cache repository.
     */
    #[Label('Cache')]
    #[Description('Cache repository for storing and retrieving cached data.')]
    case CACHE = 'cache';

    /**
     * Event dispatcher.
     */
    #[Label('Events')]
    #[Description('Event dispatcher for firing and listening to events.')]
    case EVENTS = 'events';

    /**
     * Filesystem manager.
     */
    #[Label('Files')]
    #[Description('Filesystem manager for file operations.')]
    case FILES = 'files';

    /**
     * Log writer.
     */
    #[Label('Log')]
    #[Description('Log writer for application logging.')]
    case LOG = 'log';

    /**
     * Queue manager.
     */
    #[Label('Queue')]
    #[Description('Queue manager for dispatching and processing jobs.')]
    case QUEUE = 'queue';

    /**
     * Redis manager.
     */
    #[Label('Redis')]
    #[Description('Redis manager for Redis connections.')]
    case REDIS = 'redis';

    /**
     * HTTP request.
     */
    #[Label('Request')]
    #[Description('Current HTTP request instance.')]
    case REQUEST = 'request';

    /**
     * Router instance.
     */
    #[Label('Router')]
    #[Description('Router instance for route registration and dispatching.')]
    case ROUTER = 'router';

    /**
     * Session manager.
     */
    #[Label('Session')]
    #[Description('Session manager for session storage and retrieval.')]
    case SESSION = 'session';

    /**
     * URL generator.
     */
    #[Label('URL')]
    #[Description('URL generator for creating application URLs.')]
    case URL = 'url';

    /**
     * Validator factory.
     */
    #[Label('Validator')]
    #[Description('Validator factory for data validation.')]
    case VALIDATOR = 'validator';

    /**
     * View factory.
     */
    #[Label('View')]
    #[Description('View factory for rendering views.')]
    case VIEW = 'view';

    /**
     * Authentication manager.
     */
    #[Label('Auth')]
    #[Description('Authentication manager for user authentication.')]
    case AUTH = 'auth';

    /**
     * Hash manager.
     */
    #[Label('Hash')]
    #[Description('Hash manager for hashing and verifying values.')]
    case HASH = 'hash';

    /**
     * Mail manager.
     */
    #[Label('Mail')]
    #[Description('Mail manager for sending emails.')]
    case MAIL = 'mail';

    /**
     * Notification dispatcher.
     */
    #[Label('Notification')]
    #[Description('Notification dispatcher for sending notifications.')]
    case NOTIFICATION = 'notification';

    /**
     * Storage manager.
     */
    #[Label('Storage')]
    #[Description('Storage manager for file storage operations.')]
    case STORAGE = 'storage';

    /**
     * Translator instance.
     */
    #[Label('Translator')]
    #[Description('Translator instance for application translations.')]
    case TRANSLATOR = 'translator';

    /**
     * Environment.
     */
    #[Label('Environment')]
    #[Description('Environment instance for application environment.')]
    case ENV = 'env';

    /**
     * Event dispatcher.
     */
    #[Label('Event')]
    #[Description('Event dispatcher for application events.')]
    case EVENT = 'event';

    /**
     * Sentry.
     */
    #[Label('Sentry')]
    #[Description('Event dispatcher for application events.')]
    case SENTRY = 'sentry';

    /**
     * Pusher.
     */
    #[Label('Pusher')]
    #[Description('Event dispatcher for application events.')]
    case PUSHER = 'pusher';

    /**
     * Octane.
     */
    #[Label('Octane')]
    #[Description('Event dispatcher for application events.')]
    case OCTANE = 'octane';

    /**
     * Get the label for the enum case.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::APP => 'App',
            self::CONFIG => 'Config',
            self::DB => 'Database',
            self::CACHE => 'Cache',
            self::EVENTS => 'Events',
            self::FILES => 'Files',
            self::LOG => 'Log',
            self::QUEUE => 'Queue',
            self::REDIS => 'Redis',
            self::REQUEST => 'Request',
            self::ROUTER => 'Router',
            self::SESSION => 'Session',
            self::URL => 'URL',
            self::VALIDATOR => 'Validator',
            self::VIEW => 'View',
            self::AUTH => 'Auth',
            self::HASH => 'Hash',
            self::MAIL => 'Mail',
            self::NOTIFICATION => 'Notification',
            self::STORAGE => 'Storage',
            self::TRANSLATOR => 'Translator',
            self::ENV => 'Environment',
            self::EVENT => 'Event',
            self::SENTRY => 'Sentry',
            self::PUSHER => 'Pusher',
            self::OCTANE => 'Octane',
        };
    }
}
