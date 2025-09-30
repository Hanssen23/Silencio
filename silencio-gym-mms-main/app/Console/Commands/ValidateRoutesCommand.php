<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ValidateRoutesCommand extends Command
{
    protected $signature = 'routes:validate {--fix : Attempt to fix missing routes}';
    protected $description = 'Validate all routes and variables used in views';

    public function handle()
    {
        $this->info('🔍 Starting comprehensive route and variable validation...');
        
        $issues = [];
        
        // 1. Check all route() calls in Blade templates
        $this->info('📋 Checking route() calls in Blade templates...');
        $routeIssues = $this->validateBladeRoutes();
        $issues = array_merge($issues, $routeIssues);
        
        // 2. Check all variables used in views
        $this->info('📊 Checking variables in views...');
        $variableIssues = $this->validateViewVariables();
        $issues = array_merge($issues, $variableIssues);
        
        // 3. Check controller methods exist
        $this->info('🎯 Checking controller methods...');
        $controllerIssues = $this->validateControllerMethods();
        $issues = array_merge($issues, $controllerIssues);
        
        // 4. Generate report
        $this->generateReport($issues);
        
        // 5. Attempt fixes if requested
        if ($this->option('fix')) {
            $this->attemptFixes($issues);
        }
        
        return 0;
    }
    
    private function validateBladeRoutes()
    {
        $issues = [];
        $viewFiles = $this->getBladeFiles();
        $definedRoutes = $this->getDefinedRoutes();
        
        foreach ($viewFiles as $file) {
            $content = File::get($file);
            
            // Find all route() calls
            preg_match_all('/route\([\'"]([^\'"]+)[\'"]/', $content, $matches);
            
            foreach ($matches[1] as $routeName) {
                if (!isset($definedRoutes[$routeName])) {
                    $issues[] = [
                        'type' => 'missing_route',
                        'file' => $file,
                        'route' => $routeName,
                        'severity' => 'error'
                    ];
                }
            }
        }
        
        return $issues;
    }
    
    private function validateViewVariables()
    {
        $issues = [];
        $viewFiles = $this->getBladeFiles();
        
        foreach ($viewFiles as $file) {
            $content = File::get($file);
            
            // Find all variable usage in Blade templates
            preg_match_all('/\{\{\s*\$([a-zA-Z_][a-zA-Z0-9_]*)/', $content, $matches);
            
            foreach ($matches[1] as $variable) {
                // Skip common Laravel variables
                if (in_array($variable, ['errors', 'auth', 'user', 'csrf_token', 'old'])) {
                    continue;
                }
                
                // Check if this view has a corresponding controller method
                $controllerMethod = $this->findControllerMethodForView($file);
                if ($controllerMethod && !$this->variableExistsInController($controllerMethod, $variable)) {
                    $issues[] = [
                        'type' => 'missing_variable',
                        'file' => $file,
                        'variable' => $variable,
                        'controller' => $controllerMethod,
                        'severity' => 'error'
                    ];
                }
            }
        }
        
        return $issues;
    }
    
    private function validateControllerMethods()
    {
        $issues = [];
        $routes = Route::getRoutes();
        
        foreach ($routes as $route) {
            $action = $route->getAction();
            
            if (isset($action['controller'])) {
                [$controller, $method] = explode('@', $action['controller']);
                
                if (class_exists($controller)) {
                    $reflection = new \ReflectionClass($controller);
                    
                    if (!$reflection->hasMethod($method)) {
                        $issues[] = [
                            'type' => 'missing_method',
                            'route' => $route->getName(),
                            'controller' => $controller,
                            'method' => $method,
                            'severity' => 'error'
                        ];
                    }
                } else {
                    $issues[] = [
                        'type' => 'missing_controller',
                        'route' => $route->getName(),
                        'controller' => $controller,
                        'severity' => 'error'
                    ];
                }
            }
        }
        
        return $issues;
    }
    
    private function getBladeFiles()
    {
        $viewPath = resource_path('views');
        return File::allFiles($viewPath);
    }
    
    private function getDefinedRoutes()
    {
        $routes = Route::getRoutes();
        $definedRoutes = [];
        
        foreach ($routes as $route) {
            if ($name = $route->getName()) {
                $definedRoutes[$name] = $route;
            }
        }
        
        return $definedRoutes;
    }
    
    private function findControllerMethodForView($viewFile)
    {
        // This is a simplified approach - in reality, you'd need to trace through routes
        $viewName = str_replace([resource_path('views/'), '.blade.php'], '', $viewFile);
        $viewName = str_replace('/', '.', $viewName);
        
        // Try to find matching route
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            $action = $route->getAction();
            if (isset($action['controller'])) {
                [$controller, $method] = explode('@', $action['controller']);
                return [$controller, $method];
            }
        }
        
        return null;
    }
    
    private function variableExistsInController($controllerMethod, $variable)
    {
        [$controller, $method] = $controllerMethod;
        
        if (!class_exists($controller)) {
            return false;
        }
        
        $reflection = new \ReflectionClass($controller);
        
        if (!$reflection->hasMethod($method)) {
            return false;
        }
        
        $methodReflection = $reflection->getMethod($method);
        $methodContent = file_get_contents($methodReflection->getFileName());
        
        // Check if variable is passed to view
        return strpos($methodContent, "compact('$variable')") !== false ||
               strpos($methodContent, "with('$variable'") !== false ||
               strpos($methodContent, "->with('$variable'") !== false;
    }
    
    private function generateReport($issues)
    {
        $this->info("\n📊 VALIDATION REPORT");
        $this->info("===================");
        
        if (empty($issues)) {
            $this->info("✅ No issues found! All routes and variables are properly defined.");
            return;
        }
        
        $errorCount = count(array_filter($issues, fn($issue) => $issue['severity'] === 'error'));
        $warningCount = count(array_filter($issues, fn($issue) => $issue['severity'] === 'warning'));
        
        $this->error("❌ Found {$errorCount} errors and {$warningCount} warnings:");
        
        foreach ($issues as $issue) {
            $icon = $issue['severity'] === 'error' ? '❌' : '⚠️';
            $this->line("{$icon} {$issue['type']}: {$issue['file']}");
            
            switch ($issue['type']) {
                case 'missing_route':
                    $this->line("   Missing route: {$issue['route']}");
                    break;
                case 'missing_variable':
                    $this->line("   Missing variable: \${$issue['variable']} in {$issue['controller'][0]}@{$issue['controller'][1]}");
                    break;
                case 'missing_method':
                    $this->line("   Missing method: {$issue['controller']}@{$issue['method']}");
                    break;
                case 'missing_controller':
                    $this->line("   Missing controller: {$issue['controller']}");
                    break;
            }
        }
        
        // Save report to file
        $reportPath = storage_path('logs/route-validation-report.json');
        File::put($reportPath, json_encode($issues, JSON_PRETTY_PRINT));
        $this->info("\n📄 Detailed report saved to: {$reportPath}");
    }
    
    private function attemptFixes($issues)
    {
        $this->info("\n🔧 Attempting to fix issues...");
        
        foreach ($issues as $issue) {
            switch ($issue['type']) {
                case 'missing_route':
                    $this->fixMissingRoute($issue);
                    break;
                case 'missing_variable':
                    $this->fixMissingVariable($issue);
                    break;
                case 'missing_method':
                    $this->fixMissingMethod($issue);
                    break;
            }
        }
        
        $this->info("✅ Fix attempts completed. Please review changes and test.");
    }
    
    private function fixMissingRoute($issue)
    {
        $this->warn("⚠️  Cannot auto-fix missing route: {$issue['route']}");
        $this->line("   Please add this route to routes/web.php");
    }
    
    private function fixMissingVariable($issue)
    {
        $this->warn("⚠️  Cannot auto-fix missing variable: \${$issue['variable']}");
        $this->line("   Please add this variable to {$issue['controller'][0]}@{$issue['controller'][1]}");
    }
    
    private function fixMissingMethod($issue)
    {
        $this->warn("⚠️  Cannot auto-fix missing method: {$issue['controller']}@{$issue['method']}");
        $this->line("   Please add this method to the controller");
    }
}