<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\DatabaseService;
use Illuminate\Support\Facades\DB;

class DatabaseServiceTest extends TestCase
{
    /**
     * Test database service methods
     */
    public function test_database_service_methods()
    {
        // Test driver detection
        $driver = DatabaseService::getDriver();
        $this->assertIsString($driver);
        $this->assertContains($driver, ['sqlite', 'pgsql', 'mysql']);

        // Test month extraction
        $monthQuery = DatabaseService::getMonthFromDate('created_at');
        $this->assertIsString($monthQuery);
        
        // Test year extraction
        $yearQuery = DatabaseService::getYearFromDate('created_at');
        $this->assertIsString($yearQuery);
        
        // Test current year condition
        $yearCondition = DatabaseService::getCurrentYearCondition('created_at');
        $this->assertIsString($yearCondition);
        
        // Test date format
        $dateFormat = DatabaseService::getDateFormat('created_at', '%Y-%m');
        $this->assertIsString($dateFormat);
        
        // Test last months condition
        $lastMonths = DatabaseService::getLastMonthsCondition('created_at', 12);
        $this->assertIsString($lastMonths);
    }
    
    /**
     * Test that queries don't throw SQL errors
     */
    public function test_queries_work_without_errors()
    {
        // This test will run actual queries to ensure they don't throw errors
        try {
            // Test month extraction in a real query
            $result = DB::table('users')
                ->selectRaw(DatabaseService::getMonthFromDate('created_at') . ' as month')
                ->limit(1)
                ->get();
            
            $this->assertTrue(true); // Query succeeded
        } catch (\Exception $e) {
            $this->fail('Month extraction query failed: ' . $e->getMessage());
        }
        
        try {
            // Test year condition in a real query
            $result = DB::table('users')
                ->whereRaw(DatabaseService::getCurrentYearCondition('created_at'))
                ->count();
            
            $this->assertIsInt($result);
        } catch (\Exception $e) {
            $this->fail('Year condition query failed: ' . $e->getMessage());
        }
        
        try {
            // Test last months condition
            $result = DB::table('users')
                ->whereRaw(DatabaseService::getLastMonthsCondition('created_at', 6))
                ->count();
            
            $this->assertIsInt($result);
        } catch (\Exception $e) {
            $this->fail('Last months condition query failed: ' . $e->getMessage());
        }
    }
}