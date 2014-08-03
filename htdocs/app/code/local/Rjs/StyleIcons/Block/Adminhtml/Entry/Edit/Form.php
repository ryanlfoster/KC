<?php

class Rjs_StyleIcons_Block_Adminhtml_Entry_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Define Form settings
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieve object
     */
    public function getModel()
    {
        return Mage::registry('_current_entry');
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return Mage_Adminhtml_Block_Newsletter_Template_Edit_Form
     */
    protected function _prepareForm()
    {
        $model  = $this->getModel();
        $comp   = Mage::getModel('styleicons/competition')->getCollection()->addFieldToFilter('active',1)->getFirstItem();

        $form   = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getData('action'),
            'method'    => 'post'
            ));

        $fieldset   = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('styleicons')->__('Style Icon - Entry Information'),
            'class'     => 'fieldset-wide'
            ));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name'      => 'id',
                'value'     => $model->getId(),
                ));
        }

        $fieldset->addField('competition', 'hidden', array(
            'name'      => 'competition',
            'value'     => $comp->getId(),
            ));

        $fieldset->addField('competition_name', 'text', array(
            'name'      => 'competition_name',
            'label'     => Mage::helper('styleicons')->__('Competition'),
            'value'     => $comp->getName(),
            'readonly' => true,
            'required'  => true,
            'note' => 'Set using active competition, cannot be edited.'
            ));

        $fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => Mage::helper('styleicons')->__('Name'),
            'title'     => Mage::helper('styleicons')->__('Name'),
            'required'  => true,
            'value'     => $model->getName(),
            ));

        $fieldset->addField('image', 'image', array(
            'name'      => 'image',
            'label'     => Mage::helper('styleicons')->__('Image'),
            'title'     => Mage::helper('styleicons')->__('Image')
            ));

        $fieldset->addField('description', 'textarea', array(
            'name'      => 'description',
            'label'     => Mage::helper('styleicons')->__('Description'),
            'title'     => Mage::helper('styleicons')->__('Description'),
            'required'  => true,
            'value'     => $model->getDescription(),
            ));

        $form->setAction($this->getUrl('*/*/save'));
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
