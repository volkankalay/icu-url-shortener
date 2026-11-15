<?php

namespace App\Services;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Cache;

final class ReservedShortCodes
{
    /** @var list<string>|null */
    private ?array $codes = null;

    public function __construct(
        private readonly Application $app,
        private readonly Router $router,
    ) {}

    /** @return list<string> */
    public function all(): array
    {
        return $this->codes ??= $this->load();
    }

    /** @return list<string> */
    private function load(): array
    {
        if (! $this->app->routesAreCached()) {
            return $this->fromRoutes();
        }

        $version = hash_file('sha256', $this->app->getCachedRoutesPath());

        return Cache::remember('links:reserved-routes:' . $version, 86400, $this->fromRoutes(...));
    }

    /** @return list<string> */
    private function fromRoutes(): array
    {
        $codes = [];

        foreach ($this->router->getRoutes() as $route) {
            $segment = explode('/', trim($route->uri(), '/'), 2)[0];
            if ($segment !== '' && ! str_contains($segment, '{')) {
                $codes[] = $segment;
            }
        }

        return array_values(array_unique($codes));
    }
}
