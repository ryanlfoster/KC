<?php

class Rjs_StyleIcons_Block_Adminhtml_Competition_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
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
        return Mage::registry('_current_competition');
    }

    /**
     */
    protected function _prepareForm()
    {
        $model = $this->getModel();

        $form   = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getData('action'),
            'method'    => 'post'
            ));

        $fieldset   = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('styleicons')->__('Style Icon - Competition Information'),
            'class'     => 'fieldset-wide'
            ));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name'      => 'id',
                'value'     => $model->getId(),
                ));
        }

        $fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'note'      => 'e.g. Summer/Winter/Autumn/Spring',
            'label'     => Mage::helper('styleicons')->__('Name'),
            'title'     => Mage::helper('styleicons')->__('Name'),
            'required'  => true,
            'value'     => $model->getName(),
            ));

        if ($model->getStartDate()) {
            $start = $model->getStartDate();
        } else {
            $start = null;
        }

        if ($model->getEndDate()) {
            $end = $model->getEndDate();
        } else {
            $end = null;
        }
        $fieldset->addField('start_date', 'date', array(
            'name'               => 'start_date',
            'label'              => Mage::helper('styleicons')->__('Start Date'),
            'after_element_html' => '<small>Pick Date</small>',
            'tabindex'           => 1,
            'image'              => $this->getSkinUrl('images/grid-cal.gif'),
            'format'             => 'yyyy-M-dd',
            'value'              => $start,
            'required'           => true
            ));

        $fieldset->addField('end_date', 'date', array(
            'name'               => 'end_date',
            'label'              => Mage::helper('styleicons')->__('End Date'),
            'after_element_html' => '<small>Pick Date</small>',
            'tabindex'           => 1,
            'image'              => $this->getSkinUrl('images/grid-cal.gif'),
            'format'             => 'yyyy-M-dd',
            'value'              => $end,
            'required'           => true
            ));

        $form->setAction($this->getUrl('*/*/save'));
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
