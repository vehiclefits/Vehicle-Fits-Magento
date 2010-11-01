<?php
class Elite_Vaf_Model_Level_FinderTests_ListAllTests_YMMTest extends Elite_Vaf_TestCase
{
    protected function doSetUp()
    {
        $this->switchSchema('year,make,model');
    }
    
    function testFindsDistinctResults()
    {
        $vehicle1 = $this->createVehicle(array('year'=>2000, 'make'=>'Honda', 'model'=>'Civic'));
        $vehicle2 = $this->createVehicle(array('year'=>2001, 'make'=>'Honda', 'model'=>'Civic'));
        
        $this->insertMappingMMY( $vehicle1 );
        $this->insertMappingMMY( $vehicle2 );
        
        $actual = $this->levelFinder()->listAll('make', $vehicle1->getValue('year'));
        $this->assertEquals( 1, count($actual), 'should find distinct makes');
    }
        
    function testFindsDistinctResults2()
    {
        $vehicle1 = $this->createVehicle(array('year'=>2000, 'make'=>'Honda', 'model'=>'Civic'));
        $vehicle2 = $this->createVehicle(array('year'=>2000, 'make'=>'Honda', 'model'=>'Accord'));
        
        $this->insertMappingMMY( $vehicle1 );
        $this->insertMappingMMY( $vehicle2 );
        
        $actual = $this->levelFinder()->listAll('make', $vehicle1->getValue('year'));
        $this->assertEquals( 1, count($actual), 'should find distinct makes');
    }
}