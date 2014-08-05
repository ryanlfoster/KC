<?php   
class JJM_Styleguide_Block_Index extends Mage_Core_Block_Template {


    protected function _construct()
    {
        parent::_construct();

        // We get our collection through our model
        $this->_entities = Mage::getModel('styleguide/styleguide')->getCollection();

        // Instantiate a new Pager block
        $pager = new Mage_Page_Block_Html_Pager();


        // We set our limit (here an integer store in configuration).
        // /!\ The limit must be set before the collection
        $pager
            ->setLimit(7)
            ->setCollection($this->_entities);

        // Add our Pager block to our current list block
        $this->setChild('pager', $pager);
        $this->setTemplate('styleguide/index.phtml');
    }


}