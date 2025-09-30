<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateRouteMapCommand extends Command
{
    protected $signature = 'routes:map {--output=storage/routes-map.json : Output file path}';
    protected $description = 'Generate comprehensive route reference map';

    public function handle()
    {
        $this->info('🗺️  Generating route map...');
        
        $routeMap = [
            'generated_at' => now()->toISOString(),
            'routes' => [],
            'categories' => [
                'authentication' => [],
                'dashboard' => [],
                'members' => [],
                'payments' => [],
                'analytics' => [],
                'rfid' => [],
                'employee' => [],
                'api' => [],
                'other' => []
            ]
        ];

        $routes = Route::getRoutes();
        
        foreach ($routes as $route) {
            if (!$route->getName()) {
                continue;
            }

            $routeInfo = [
                'name' => $route->getName(),
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'action' => $route->getActionName(),
                'middleware' => $route->gatherMiddleware(),
                'parameters' => $route->parameterNames(),
            ];

            $routeMap['routes'][] = $routeInfo;
            
            // Categorize routes
            $category = $this->categorizeRoute($route->getName());
            $routeMap['categories'][$category][] = $route->getName();
        }

        // Sort routes by name
        usort($routeMap['routes'], function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        // Generate documentation
        $this->generateDocumentation($routeMap);

        // Save JSON map
        $outputPath = base_path($this->option('output'));
        File::put($outputPath, json_encode($routeMap, JSON_PRETTY_PRINT));
        
        $this->info("✅ Route map saved to: {$outputPath}");
        $this->info("📚 Documentation generated: storage/route-documentation.md");
        
        return 0;
    }

    private function categorizeRoute($routeName)
    {
        if (Str::startsWith($routeName, ['login', 'logout', 'register', 'auth'])) {
            return 'authentication';
        }
        
        if (Str::startsWith($routeName, ['dashboard', 'analytics'])) {
            return 'dashboard';
        }
        
        if (Str::startsWith($routeName, ['member', 'members'])) {
            return 'members';
        }
        
        if (Str::startsWith($routeName, ['payment', 'membership'])) {
            return 'payments';
        }
        
        if (Str::startsWith($routeName, 'rfid')) {
            return 'rfid';
        }
        
        if (Str::startsWith($routeName, 'employee')) {
            return 'employee';
        }
        
        if (Str::startsWith($routeName, 'api')) {
            return 'api';
        }
        
        return 'other';
    }

    private function generateDocumentation($routeMap)
    {
        $doc = "# Route Documentation\n\n";
        $doc .= "Generated on: " . now()->format('Y-m-d H:i:s') . "\n\n";
        $doc .= "## Summary\n\n";
        $doc .= "- Total Routes: " . count($routeMap['routes']) . "\n";
        
        foreach ($routeMap['categories'] as $category => $routes) {
            if (!empty($routes)) {
                $doc .= "- {$category}: " . count($routes) . " routes\n";
            }
        }
        
        $doc .= "\n## Route Categories\n\n";
        
        foreach ($routeMap['categories'] as $category => $routes) {
            if (empty($routes)) {
                continue;
            }
            
            $doc .= "### " . ucfirst($category) . " Routes\n\n";
            foreach ($routes as $routeName) {
                $route = collect($routeMap['routes'])->firstWhere('name', $routeName);
                if ($route) {
                    $methods = implode('|', $route['methods']);
                    $doc .= "- `{$routeName}` - {$methods} `{$route['uri']}`\n";
                }
            }
            $doc .= "\n";
        }
        
        $doc .= "## All Routes\n\n";
        $doc .= "| Name | Methods | URI | Action |\n";
        $doc .= "|------|---------|-----|--------|\n";
        
        foreach ($routeMap['routes'] as $route) {
            $methods = implode(', ', $route['methods']);
            $doc .= "| `{$route['name']}` | {$methods} | `{$route['uri']}` | `{$route['action']}` |\n";
        }
        
        File::put(storage_path('route-documentation.md'), $doc);
    }
}
