#!/bin/bash

# Fix Missing Routes Script for Silencio Gym Management System
# This script automatically fixes common missing route issues

set -e

echo "🔧 Starting automatic route fixes..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

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

# Common missing routes and their fixes
declare -A ROUTE_FIXES=(
    ["dashboard.stats"]="Route::get('/dashboard/stats', [DashboardController::class, 'getDashboardStats'])->name('dashboard.stats');"
    ["employee.dashboard.stats"]="Route::get('/employee/dashboard/stats', [EmployeeController::class, 'getDashboardStats'])->name('employee.dashboard.stats');"
    ["employee.analytics.dashboard-stats"]="Route::get('/employee/analytics/dashboard-stats', [AnalyticsController::class, 'getDashboardStats'])->name('employee.analytics.dashboard-stats');"
    ["employee.analytics.weekly-attendance"]="Route::get('/employee/analytics/weekly-attendance', [AnalyticsController::class, 'weeklyAttendance'])->name('employee.analytics.weekly-attendance');"
    ["employee.analytics.weekly-revenue"]="Route::get('/employee/analytics/weekly-revenue', [AnalyticsController::class, 'weeklyRevenue'])->name('employee.analytics.weekly-revenue');"
    ["employee.analytics.monthly-revenue"]="Route::get('/employee/analytics/monthly-revenue', [AnalyticsController::class, 'monthlyRevenue'])->name('employee.analytics.monthly-revenue');"
    ["employee.rfid.start"]="Route::post('/employee/rfid/start', [RfidController::class, 'startRfidReader'])->name('employee.rfid.start');"
    ["employee.rfid.stop"]="Route::post('/employee/rfid/stop', [RfidController::class, 'stopRfidReader'])->name('employee.rfid.stop');"
    ["employee.rfid.status"]="Route::get('/employee/rfid/status', [RfidController::class, 'getRfidStatus'])->name('employee.rfid.status');"
    ["employee.membership.manage-member"]="Route::get('/employee/membership/manage-member', [EmployeeController::class, 'manageMember'])->name('employee.membership.manage-member');"
    ["employee.membership.process-payment"]="Route::post('/employee/membership/process-payment', [EmployeeController::class, 'processPayment'])->name('employee.membership.process-payment');"
    ["employee.membership.plans.index"]="Route::get('/employee/membership/plans', [EmployeeController::class, 'plans'])->name('employee.membership.plans.index');"
    ["employee.membership.payments"]="Route::get('/employee/membership/payments', [EmployeeController::class, 'payments'])->name('employee.membership.payments');"
    ["employee.membership.payments.export_csv"]="Route::get('/employee/membership/payments/export/csv', [EmployeeController::class, 'exportPaymentsCsv'])->name('employee.membership.payments.export_csv');"
    ["employee.membership.payments.print"]="Route::get('/employee/membership/payments/{id}/print', [EmployeeController::class, 'printPayment'])->name('employee.membership.payments.print');"
    ["employee.membership-plans.all"]="Route::get('/employee/membership-plans/all', [EmployeeController::class, 'getAllPlans'])->name('employee.membership-plans.all');"
    ["employee.membership-plans.plan-types"]="Route::get('/employee/membership-plans/plan-types', [EmployeeController::class, 'getPlanTypes'])->name('employee.membership-plans.plan-types');"
    ["employee.membership-plans.duration-types"]="Route::get('/employee/membership-plans/duration-types', [EmployeeController::class, 'getDurationTypes'])->name('employee.membership-plans.duration-types');"
    ["employee.members.index"]="Route::get('/employee/members', [EmployeeController::class, 'members'])->name('employee.members.index');"
    ["employee.members.create"]="Route::get('/employee/members/create', [EmployeeController::class, 'createMember'])->name('employee.members.create');"
    ["employee.members.store"]="Route::post('/employee/members', [EmployeeController::class, 'storeMember'])->name('employee.members.store');"
    ["employee.members.edit"]="Route::get('/employee/members/{id}/edit', [EmployeeController::class, 'editMember'])->name('employee.members.edit');"
    ["employee.members.update"]="Route::put('/employee/members/{id}', [EmployeeController::class, 'updateMember'])->name('employee.members.update');"
    ["employee.members.show"]="Route::get('/employee/members/{id}', [EmployeeController::class, 'showMember'])->name('employee.members.show');"
    ["employee.members.profile"]="Route::get('/employee/members/{id}/profile', [EmployeeController::class, 'memberProfile'])->name('employee.members.profile');"
    ["employee.accounts.update"]="Route::put('/employee/accounts/update', [EmployeeController::class, 'updateAccount'])->name('employee.accounts.update');"
    ["member.membership-plans"]="Route::get('/member/membership-plans', [MemberDashboardController::class, 'membershipPlans'])->name('member.membership-plans');"
    ["member.membership-pricing"]="Route::get('/member/membership-pricing', [MemberDashboardController::class, 'membershipPricing'])->name('member.membership-pricing');"
    ["member.membership-plans.stream"]="Route::get('/member/membership-plans/stream', [MemberDashboardController::class, 'membershipPlansStream'])->name('member.membership-plans.stream');"
    ["membership.manage-member"]="Route::get('/membership/manage-member', [MembershipController::class, 'manageMember'])->name('membership.manage-member');"
    ["membership.process-payment"]="Route::post('/membership/process-payment', [MembershipController::class, 'processPayment'])->name('membership.process-payment');"
    ["membership.plans.index"]="Route::get('/membership/plans', [MembershipController::class, 'index'])->name('membership.plans.index');"
    ["membership.payments"]="Route::get('/membership/payments', [PaymentController::class, 'index'])->name('membership.payments');"
    ["membership.payments.export_csv"]="Route::get('/membership/payments/export/csv', [PaymentController::class, 'exportCsv'])->name('membership.payments.export_csv');"
    ["membership.payments.print"]="Route::get('/membership/payments/{id}/print', [PaymentController::class, 'print'])->name('membership.payments.print');"
    ["membership-plans.all"]="Route::get('/membership-plans/all', [MembershipController::class, 'getAllPlans'])->name('membership-plans.all');"
    ["membership-plans.store"]="Route::post('/membership-plans/store', [MembershipController::class, 'store'])->name('membership-plans.store');"
    ["membership-plans.update-duration-types"]="Route::post('/membership-plans/update-duration-types', [MembershipController::class, 'updateDurationTypes'])->name('membership-plans.update-duration-types');"
    ["terms"]="Route::get('/terms', function () { return view('terms'); })->name('terms');"
    ["uid-pool.index"]="Route::get('/uid-pool', [UidPoolController::class, 'index'])->name('uid-pool.index');"
    ["uid-pool.refresh"]="Route::post('/uid-pool/refresh', [UidPoolController::class, 'refresh'])->name('uid-pool.refresh');"
)

# Get missing routes
print_status "Identifying missing routes..."
php artisan routes:validate > /tmp/route_validation.log 2>&1 || true

# Extract missing routes from the log
MISSING_ROUTES=()
while IFS= read -r line; do
    if [[ $line == *"Route '"*"'"*"referenced"* ]]; then
        route_name=$(echo "$line" | grep -o "Route '[^']*'" | sed "s/Route '//g" | sed "s/'//g")
        if [ ! -z "$route_name" ]; then
            MISSING_ROUTES+=("$route_name")
        fi
    fi
done < /tmp/route_validation.log

if [ ${#MISSING_ROUTES[@]} -eq 0 ]; then
    print_success "No missing routes found!"
    exit 0
fi

print_status "Found ${#MISSING_ROUTES[@]} missing routes"

# Create backup of routes file
print_status "Creating backup of routes file..."
cp routes/web.php routes/web.php.backup.$(date +%Y%m%d_%H%M%S)

# Fix missing routes
FIXED_COUNT=0
for route in "${MISSING_ROUTES[@]}"; do
    if [ -n "${ROUTE_FIXES[$route]}" ]; then
        print_status "Fixing route: $route"
        
        # Add the route to the appropriate section in routes/web.php
        if [[ $route == employee.* ]]; then
            # Add to employee routes section
            sed -i "/\/\/ Employee Routes/a\\    ${ROUTE_FIXES[$route]}" routes/web.php
        elif [[ $route == member.* ]]; then
            # Add to member routes section
            sed -i "/\/\/ Member routes/a\\    ${ROUTE_FIXES[$route]}" routes/web.php
        elif [[ $route == membership.* ]]; then
            # Add to membership routes section
            sed -i "/\/\/ Membership routes/a\\    ${ROUTE_FIXES[$route]}" routes/web.php
        else
            # Add to general routes section
            sed -i "/\/\/ Public Authentication Routes/a\\${ROUTE_FIXES[$route]}" routes/web.php
        fi
        
        ((FIXED_COUNT++))
        print_success "Fixed route: $route"
    else
        print_warning "No automatic fix available for route: $route"
    fi
done

# Clear route cache
print_status "Clearing route cache..."
php artisan route:clear

# Validate again
print_status "Validating routes after fixes..."
if php artisan routes:validate > /dev/null 2>&1; then
    print_success "All routes are now valid!"
    print_success "Fixed $FIXED_COUNT out of ${#MISSING_ROUTES[@]} missing routes"
else
    print_warning "Some routes may still need manual fixes"
    print_warning "Fixed $FIXED_COUNT out of ${#MISSING_ROUTES[@]} missing routes"
fi

# Clean up
rm -f /tmp/route_validation.log

print_status "Route fixing completed!"
