<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ValidateRoutesCommand extends Command
{
    protected $signature = 'routes:validate {--fix : Automatically fix missing routes} {--report : Generate detailed report}';
    protected $description = 'Validate all route references in Blade templates and JavaScript files';

    private $missingRoutes = [];
    private $unusedRoutes = [];
    private $referencedRoutes = [];
    private $definedRoutes = [];

    public function handle()
    {
        $this->info('🔍 Starting route validation...');
        
        // Get all defined routes
        $this->getDefinedRoutes();
        
        // Scan for route references
        $this->scanBladeTemplates();
        $this->scanJavaScriptFiles();
        
        // Validate routes
        $this->validateRoutes();
        
        // Generate report
        if ($this->option('report')) {
            $this->generateReport();
        }
        
        // Show results
        $this->showResults();
        
        return $this->missingRoutes ? 1 : 0;
    }

    private function getDefinedRoutes()
    {
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            if ($route->getName()) {
                $this->definedRoutes[] = $route->getName();
            }
        }
        
        $this->info("📋 Found " . count($this->definedRoutes) . " defined routes");
    }

    private function scanBladeTemplates()
    {
        $bladeFiles = File::allFiles(resource_path('views'));
        
        foreach ($bladeFiles as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());
                $this->extractRouteReferences($content, $file->getRelativePathname());
            }
        }
    }

    private function scanJavaScriptFiles()
    {
        $jsFiles = collect([
            resource_path('js'),
            public_path('js'),
        ])->filter(function ($path) {
            return File::exists($path);
        })->flatMap(function ($path) {
            return File::allFiles($path);
        })->filter(function ($file) {
            return in_array($file->getExtension(), ['js', 'vue', 'ts']);
        });

        foreach ($jsFiles as $file) {
            $content = File::get($file->getPathname());
            $this->extractRouteReferences($content, $file->getRelativePathname());
        }
    }

    private function extractRouteReferences($content, $filename)
    {
        // Match route() calls in Blade templates
        preg_match_all('/route\([\'"]([^\'"]+)[\'"]/', $content, $matches);
        foreach ($matches[1] as $routeName) {
            $this->referencedRoutes[] = [
                'route' => $routeName,
                'file' => $filename,
                'type' => 'blade'
            ];
        }

        // Match fetch() calls with route references
        preg_match_all('/fetch\([\'"]{{ route\([\'"]([^\'"]+)[\'"]\) }}[\'"]/', $content, $matches);
        foreach ($matches[1] as $routeName) {
            $this->referencedRoutes[] = [
                'route' => $routeName,
                'file' => $filename,
                'type' => 'javascript'
            ];
        }

        // Match direct API calls
        preg_match_all('/fetch\([\'"]([^\'"]+)[\'"]/', $content, $matches);
        foreach ($matches[1] as $url) {
            if (Str::startsWith($url, '/') && !Str::startsWith($url, '/storage/')) {
                $this->referencedRoutes[] = [
                    'route' => $url,
                    'file' => $filename,
                    'type' => 'api'
                ];
            }
        }
    }

    private function validateRoutes()
    {
        foreach ($this->referencedRoutes as $reference) {
            $routeName = $reference['route'];
            
            if (!in_array($routeName, $this->definedRoutes)) {
                $this->missingRoutes[] = $reference;
            }
        }

        // Find unused routes
        foreach ($this->definedRoutes as $routeName) {
            $isUsed = false;
            foreach ($this->referencedRoutes as $reference) {
                if ($reference['route'] === $routeName) {
                    $isUsed = true;
                    break;
                }
            }
            
            if (!$isUsed && !Str::startsWith($routeName, ['sanctum.', 'ignition.', 'telescope.'])) {
                $this->unusedRoutes[] = $routeName;
            }
        }
    }

    private function generateReport()
    {
        $report = [
            'timestamp' => now()->toISOString(),
            'summary' => [
                'total_defined_routes' => count($this->definedRoutes),
                'total_referenced_routes' => count($this->referencedRoutes),
                'missing_routes' => count($this->missingRoutes),
                'unused_routes' => count($this->unusedRoutes),
            ],
            'missing_routes' => $this->missingRoutes,
            'unused_routes' => $this->unusedRoutes,
            'defined_routes' => $this->definedRoutes,
        ];

        $reportPath = storage_path('logs/route-validation-report.json');
        File::put($reportPath, json_encode($report, JSON_PRETTY_PRINT));
        
        $this->info("📊 Detailed report saved to: {$reportPath}");
    }

    private function showResults()
    {
        $this->newLine();
        
        if (empty($this->missingRoutes)) {
            $this->info('✅ All route references are valid!');
        } else {
            $this->error('❌ Found ' . count($this->missingRoutes) . ' missing route references:');
            $this->newLine();
            
            foreach ($this->missingRoutes as $missing) {
                $this->line("   • Route '{$missing['route']}' referenced in {$missing['file']} ({$missing['type']})");
            }
        }

        if (!empty($this->unusedRoutes)) {
            $this->newLine();
            $this->warn('⚠️  Found ' . count($this->unusedRoutes) . ' potentially unused routes:');
            foreach ($this->unusedRoutes as $unused) {
                $this->line("   • {$unused}");
            }
        }

        $this->newLine();
        $this->info("📈 Summary:");
        $this->line("   • Defined routes: " . count($this->definedRoutes));
        $this->line("   • Referenced routes: " . count($this->referencedRoutes));
        $this->line("   • Missing routes: " . count($this->missingRoutes));
        $this->line("   • Unused routes: " . count($this->unusedRoutes));
    }
}
