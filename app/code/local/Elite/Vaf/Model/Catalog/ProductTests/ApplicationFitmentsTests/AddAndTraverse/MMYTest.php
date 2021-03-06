<?php
/**
 * Vehicle Fits
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to sales@vehiclefits.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Vehicle Fits to newer
 * versions in the future. If you wish to customize Vehicle Fits for your
 * needs please refer to http://www.vehiclefits.com for more information.

 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Elite_Vaf_Model_Catalog_ProductTests_ApplicationFitmentsTests_AddAndTraverse_MMYTest extends Elite_Vaf_Model_Catalog_ProductTests_TestCase
{
    function testAddMultipleMake()
    {
        $product = $this->getProduct(1);

        $vehicle1 = $this->createMMY('Make', 'Model1');
        $vehicle2 = $this->createMMY('Make', 'Model2');

        $result = $product->doAddFit( $vehicle1->getLevel('make') );    
        
        $this->assertEquals( $vehicle1->getValue('model'), $result[0]->getValue('model'), 'should add multiple models for a make' );
        $this->assertEquals( $vehicle2->getValue('model'), $result[1]->getValue('model'), 'should add multiple models for a make' );
    }
    
    function testAddModel()
    {
        $product = $this->getProduct(1);

        $vehicle1 = $this->createMMY('Make', 'Model1');
        $vehicle2 = $this->createMMY('Make', 'Model2');

        $result = $product->doAddFit( $vehicle2->getLevel('model') );    
        
        $this->assertEquals( $vehicle2->getValue('model'), $result[0]->getValue('model'), 'should add model' );
    }

}