#!/bin/bash

# Route Validation Script for Silencio Gym Management System
# This script validates all route references and generates reports

set -e

echo "🔍 Starting comprehensive route validation..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel project root directory"
    exit 1
fi

# Check if PHP is available
if ! command -v php &> /dev/null; then
    print_error "PHP is not installed or not in PATH"
    exit 1
fi

print_status "Running route validation command..."
php artisan routes:validate --report

if [ $? -eq 0 ]; then
    print_success "All routes are valid!"
else
    print_warning "Some route issues were found. Check the report above."
fi

print_status "Generating route map..."
php artisan routes:map

print_status "Checking for common route issues..."

# Check for missing route files
MISSING_ROUTES=()

# Check if all referenced routes exist
echo "Checking Blade templates for route references..."
grep -r "route(" resources/views/ --include="*.blade.php" | grep -o "route(['\"][^'\"]*['\"]" | sed "s/route(['\"]//g" | sed "s/['\"]//g" | sort | uniq > /tmp/referenced_routes.txt

echo "Checking JavaScript files for route references..."
grep -r "route(" resources/js/ public/js/ --include="*.js" --include="*.vue" --include="*.ts" 2>/dev/null | grep -o "route(['\"][^'\"]*['\"]" | sed "s/route(['\"]//g" | sed "s/['\"]//g" | sort | uniq >> /tmp/referenced_routes.txt

# Get all defined routes
php artisan route:list --name-only | sort > /tmp/defined_routes.txt

# Find missing routes
comm -23 <(sort /tmp/referenced_routes.txt | uniq) <(sort /tmp/defined_routes.txt) > /tmp/missing_routes.txt

if [ -s /tmp/missing_routes.txt ]; then
    print_error "Missing routes found:"
    cat /tmp/missing_routes.txt | while read route; do
        echo "  - $route"
    done
    MISSING_ROUTES=($(cat /tmp/missing_routes.txt))
else
    print_success "No missing routes found!"
fi

# Find unused routes
comm -13 <(sort /tmp/referenced_routes.txt | uniq) <(sort /tmp/defined_routes.txt) | grep -v -E "(sanctum|ignition|telescope|horizon)" > /tmp/unused_routes.txt

if [ -s /tmp/unused_routes.txt ]; then
    print_warning "Potentially unused routes found:"
    cat /tmp/unused_routes.txt | while read route; do
        echo "  - $route"
    done
fi

# Generate summary report
echo ""
echo "📊 Route Validation Summary"
echo "=========================="
echo "Referenced routes: $(wc -l < /tmp/referenced_routes.txt)"
echo "Defined routes: $(wc -l < /tmp/defined_routes.txt)"
echo "Missing routes: $(wc -l < /tmp/missing_routes.txt)"
echo "Unused routes: $(wc -l < /tmp/unused_routes.txt)"

# Clean up temporary files
rm -f /tmp/referenced_routes.txt /tmp/defined_routes.txt /tmp/missing_routes.txt /tmp/unused_routes.txt

# Exit with error code if missing routes found
if [ ${#MISSING_ROUTES[@]} -gt 0 ]; then
    print_error "Route validation failed. Please fix the missing routes above."
    exit 1
else
    print_success "Route validation completed successfully!"
    exit 0
fi
