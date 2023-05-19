<?php
/**
 * PHP version 8.2.5
 *
 * @files
 * Description
 *
 * @category Libraries
 * @package  Registry
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */

declare(strict_types=1);

namespace Libraries;

use Command\UsersCommand;
use Libraries\Controllers\AppController;
use Libraries\DataMapper\ModelCollection;
use Libraries\DataMapper\ModelMapper;
use Libraries\Errors\AppException;
use Libraries\Route\RouteCollection;
use Libraries\Requests\Request;
use Models\Users;

/**
 * Registry class
 * Description
 *
 * @category Libraries
 * @package  Registry
 * @author   SHDTD <sales@zazil.ru>
 * @license  https://opensource.org/license/mit/ MIT
 * @link     https://github.com/shdtd/Skeleton
 */
class Registry
{

    /**
     * Description
     *
     * @var Registry $instance
     */
    private static Registry $instance;

    /**
     * Description
     *
     * @var Request $request
     */
    private Request $request;

    /**
     * Description
     *
     * @var Config $config
     */
    private Config $config;

    /**
     * Description
     *
     * @var Config $commands
     */
    private Config $commands;

    /**
     * Description
     *
     * @var \PDO $pdo
     */
    private \PDO $pdo;

    /**
     * Description
     *
     * @var AppController $appController
     */
    private AppController $appController;

    /**
     * Description
     *
     * @var RouteCollection $routeCollection
     */
    private RouteCollection $routeCollection;

    /**
     * Description
     *
     * @var ComponentDescriptor $descriptor
     */
    private ComponentDescriptor $descriptor;

    /**
     * Description
     *
     * @var Users $usersMapper
     */
    private Users $usersMapper;

    /**
     * Description
     *
     * @var array<string,ModelCollection> $modelCollections
     */
    private array $modelCollections;

    /**
     * Description
     *
     * @var object $access
     */
    private object $access;

    /**
     * Description
     *
     * @var string $requestMethod
     */
    private string $requestMethod;

    /**
     * Description
     *
     * @var string $requestUri
     */
    private string $requestUri;

    /**
     * Description
     *
     * @var string $appPath
     */
    private string $appPath;

    /**
     * Description
     *
     * @var string $templatesPath
     */
    private string $templatesPath;

    /**
     * Constructor
     */
    private function __construct()
    {
    }

    /**
     * Get instance
     *
     * @return self
     */
    public static function instance(): self
    {
        if (isset(self::$instance) === false) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * AppPath
     *
     * @return string
     */
    public function getAppPath(): string
    {
        if (isset($this->appPath) === false) {
            $this->appPath = realpath(__DIR__ . '/../../..');
        }

        return $this->appPath;
    }

    /**
     * TemplatesPath
     *
     * @return string
     */
    public function getTemplatesPath(): string
    {
        if (isset($this->templatesPath) === false) {
            $this->templatesPath = $this->appPath . '/backend/view/templates';
        }

        return $this->templatesPath;
    }

    /**
     * AppController
     *
     * @return AppController
     */
    public function getAppController(): AppController
    {
        if (isset($this->appController) === false) {
            $this->appController = new AppController();
        }

        return $this->appController;
    }

    /**
     * RouteCollection
     *
     * @return RouteCollection
     */
    public function getRouteCollection(): RouteCollection
    {
        if (isset($this->routeCollection) === false) {
            $this->routeCollection = new RouteCollection();
        }

        return $this->routeCollection;
    }

    /**
     * ModelCollections
     *
     * @param ModelMapper $model Description.
     *
     * @return ModelCollection
     */
    public function getModelCollection(ModelMapper $model): ModelCollection
    {
        if (isset($this->modelCollections[$model::class]) === false) {
            $this->modelCollections[$model::class] = new ModelCollection($model);
        }

        return $this->modelCollections[$model::class];
    }

    /**
     * Request Method
     *
     * @param string $requestMethod Description.
     *
     * @return void
     */
    public function setRequestMethod(string $requestMethod): void
    {
        $this->requestMethod = $requestMethod;
    }

    /**
     * Request Method
     *
     * @throws AppException Is not set "Request Method" before get.
     * @return string
     */
    public function getRequestMethod(): string
    {
        if (isset($this->requestMethod) === false) {
            throw new AppException('Параметр Request Method не определен.');
        }

        return $this->requestMethod;
    }

    /**
     * Request URI
     *
     * @param string $requestUri Description.
     *
     * @return void
     */
    public function setRequestUri(string $requestUri): void
    {
        $this->requestUri = $requestUri;
    }

    /**
     * Request URI
     *
     * @throws AppException Is not set "Request URI" before get.
     * @return string
     */
    public function getRequestUri(): string
    {
        if (isset($this->requestUri) === false) {
            throw new AppException('Параметр Request URI не определен.');
        }

        return $this->requestUri;
    }

    /**
     * Descriptor
     *
     * @param ComponentDescriptor $descriptor Description.
     *
     * @return void
     */
    public function setDescriptor(ComponentDescriptor $descriptor): void
    {
        $this->descriptor = $descriptor;
    }

    /**
     * Descriptor
     *
     * @throws AppException Is not set "Descriptor" before get.
     * @return ComponentDescriptor
     */
    public function getDescriptor(): ComponentDescriptor
    {
        if (isset($this->descriptor) === false) {
            throw new AppException('Параметр Descriptor не определен.');
        }

        return $this->descriptor;
    }

    /**
     * Request
     *
     * @throws AppException Is not set "Request" before get.
     * @return Request
     */
    public function getRequest(): Request
    {
        if (isset($this->request) === false) {
            throw new AppException('Параметр Request не определен.');
        }

        return $this->request;
    }

    /**
     * Request
     *
     * @param Request $request Description.
     *
     * @return void
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * Config
     *
     * @throws AppException Is not set "Config" before get.
     * @return Config
     */
    public function getConfig(): Config
    {
        if (isset($this->config) === false) {
            throw new AppException('Параметр Config не определен.');
        }

        return $this->config;
    }

    /**
     * Config
     *
     * @param Config $config Description.
     *
     * @return void
     */
    public function setConfig(Config $config): void
    {
        $this->config = $config;
    }

    /**
     * PDO
     *
     * @throws AppException Is not set "PDO" before get.
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        if (isset($this->pdo) === false) {
            throw new AppException('Параметр PDO не определен.');
        }

        return $this->pdo;
    }

    /**
     * PDO
     *
     * @param \PDO $pdo PHP Data Objects.
     *
     * @return void
     */
    public function setPdo(\PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * Commands
     *
     * @throws AppException Is not set "Commands" before get.
     * @return Config
     */
    public function getCommands(): Config
    {
        if (isset($this->commands) === false) {
            throw new AppException('Параметр Commands не определен.');
        }

        return $this->commands;
    }

    /**
     * Commands
     *
     * @param Config $commands Description.
     *
     * @return void
     */
    public function setCommands(Config $commands): void
    {
        $this->commands = $commands;
    }

    /**
     * UserMapper
     *
     * @return Users
     */
    public function getUserMapper(): Users
    {
        if (isset($this->usersMapper) === false) {
            $this->usersMapper = new Users();
        }

        return $this->usersMapper;
    }

    /**
     * Access class
     *
     * @return object
     */
    public function getAccess()
    {
        if (isset($this->access) === false) {
            $this->access = new class {
                /**
                 * Sets private content
                 *
                 * @return void
                 */
                public function lock(): void
                {
                    if (UsersCommand::apiCheckToken() === false) {
                        die('Access denied');
                    }
                }
            };
        }

        return $this->access;
    }
}
