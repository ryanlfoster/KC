<?php

$this->startSetup();
$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_enabled', array(
    'group'         => 'Landing Grid',
    'input'         => 'select',
    'source'        => 'eav/entity_attribute_source_boolean',
    'label'         => 'Enabled',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_image_desktop_1', array(
    'group'         => 'Landing Grid',
    'type'    => 'varchar',
    'input'   => 'image',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Desktop Image 1',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_image_mobile_1', array(
    'group'         => 'Landing Grid',
    'type'    => 'varchar',
    'input'   => 'image',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Mobile Image 1',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_link_1', array(
    'group'         => 'Landing Grid',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Image 1 Link',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));


$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_image_desktop_2', array(
    'group'         => 'Landing Grid',
    'type'    => 'varchar',
    'input'   => 'image',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Desktop Image 2',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));


$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_image_mobile_2', array(
    'group'         => 'Landing Grid',
    'type'    => 'varchar',
    'input'   => 'image',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Mobile Image 2',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_link_2', array(
    'group'         => 'Landing Grid',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Image 2 Link',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_image_desktop_3', array(
    'group'         => 'Landing Grid',
    'type'    => 'varchar',
    'input'   => 'image',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Desktop Image 3',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));


$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_image_mobile_3', array(
    'group'         => 'Landing Grid',
    'type'    => 'varchar',
    'input'   => 'image',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Mobile Image 3',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_link_3', array(
    'group'         => 'Landing Grid',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Image 3 Link',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_image_desktop_4', array(
    'group'         => 'Landing Grid',
    'type'    => 'varchar',
    'input'   => 'image',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Desktop Image 4',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_image_mobile_4', array(
    'group'         => 'Landing Grid',
    'type'    => 'varchar',
    'input'   => 'image',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Mobile Image 4',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_link_4', array(
    'group'         => 'Landing Grid',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Image 4 Link',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_image_desktop_5', array(
    'group'         => 'Landing Grid',
    'type'    => 'varchar',
    'input'   => 'image',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Desktop Image 5',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));


$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_image_mobile_5', array(
    'group'         => 'Landing Grid',
    'type'    => 'varchar',
    'input'   => 'image',
    'backend' => 'catalog/category_attribute_backend_image',
    'label'         => 'Mobile Image 5',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'grid_link_5', array(
    'group'         => 'Landing Grid',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Image 5 Link',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

$this->endSetup();