<?php
class Elite_Vafsitemap_Block_Vehicles extends Mage_Core_Block_Template implements Elite_Vaf_Configurable
{
    protected $perpage = 50;
    protected $page = 1;
    
    /** @var Zend_Config */
    protected $config;  
    
    function _construct()
    {
        $this->page = isset( $_GET['page'] ) ? (int)$_GET['page'] : 1;
    }
    
    function getConfig()
    {
        if( !$this->config instanceof Zend_Config )
        {
            $this->config = Elite_Vaf_Helper_Data::getInstance()->getConfig();
        }    
        return $this->config;
    }
    
    function setConfig( Zend_Config $config )
    {
        $this->config = $config;
    }
    
    function getVehicleUrl($vehicle)
    {
		$rewrite = new Elite_Vafsitemap_Model_Url_Rewrite;
		return $rewrite->getProductListingUrl($vehicle);
    }
    
    /** @todo move/rename this to definition finder -> find all in use() method */
    function getDefinitions($perPage=false,$offset=false)
    {
        $return = array();
        $vehicleFinder = new Elite_Vaf_Model_Vehicle_Finder( $this->getSchema() );
        foreach( $this->doGetDefinitions($perPage,$offset) as $vehicleStdClass )
        {
        	$leafId = $this->getSchema()->getLeafLevel() . '_id';
            
            if( !$vehicleStdClass->$leafId )
        	{
				continue;
        	}
            $vehicle = $vehicleFinder->findByLeaf( $vehicleStdClass->$leafId );
            array_push( $return, $vehicle );
        }
        return $return;
    }
    
    function doGetDefinitions($perPage,$offset)
    {
        $db = $this->getReadAdapter();
        $leafId = $this->getSchema()->getLeafLevel() . '_id';
        $select = $db->select()
            ->from( 'elite_mapping', sprintf( 'distinct(%s)', $leafId ) )
            ->group( $leafId );
        
        if($perPage||$offset)
        {
            $select->limit( $perPage, $offset );
		}
		
        $result = $select->query( Zend_Db::FETCH_OBJ );
        $return = array();
        while( $row = $result->fetchObject() )
        {
            array_push( $return, $row );
        }
        return $return;
    }
    
    protected function offset()
    {
        return ( $this->page * $this->perpage ) - $this->perpage;
    }
     
    /** @return integer total # of definitions in the sitemap */
    function count()
    {
        return $this->sitemap()->vehicleCount();
    }
    
    function start()
    {
        return 1 + $this->page*$this->perpage - $this->perpage;
    }
    
    function end()
    {
        $end = $this->start() + $this->perpage;
        if( $end > $this->count() )
        {
            $end = $this->count();
        }
        else
        {
            $end = $end - 1;
        }
        return $end;
    }
    
    function prev()
    {
        return $this->page - 1;
    }
    
    function next()
    {
        return $this->page + 1;
    }
    
    function sitemap()
    {
        return new Elite_Vafsitemap_Model_Sitemap_Product_Html();
    }
    
    protected function getSchema()
    {
        $schema = new Elite_Vaf_Model_Schema();
        $schema->setConfig( $this->getCOnfig() ); /** @todo potential linux bug needs regression test */
        return $schema;
    }
    
    /** @return Zend_Db_Statement_Interface */
    protected function query( $sql, $bind = array() )
    {
        return $this->getReadAdapter()->query( $sql, $bind );
    }
    
    /** @return Zend_Db_Adapter_Abstract */
    protected function getReadAdapter()
    {
        return Elite_Vaf_Helper_Data::getInstance()->getReadAdapter();
    }
    
}
