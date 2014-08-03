<?php

class Rjs_StyleIcons_Block_Adminhtml_Entry_Edit extends Mage_Adminhtml_Block_Widget
{
    /**
     * Edit Block model
     *
     * @var bool
     */
    protected $_editMode = false;

    /**
     * Preparing block layout
     *
     * @return Mage_Adminhtml_Block_Newsletter_Template_Edit
     */
    protected function _prepareLayout()
    {
        // Load Wysiwyg on demand and Prepare layout
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled() && ($block = $this->getLayout()->getBlock('head'))) {
            $block->setCanLoadTinyMce(true);
        }

        $this->setChild('back_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('styleicons')->__('Back'),
                    'onclick'   => "window.location.href = '" . $this->getUrl('*/*') . "'",
                    'class'     => 'back'
                ))
        );

        $this->setChild('reset_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('styleicons')->__('Reset'),
                    'onclick'   => 'window.location.href = window.location.href'
                ))
        );

        $this->setChild('save_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('styleicons')->__('Save Entry'),
                    'onclick'   => 'formControl.save();',
                    'class'     => 'save'
                ))
        );

        $this->setChild('delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('styleicons')->__('Delete Entry'),
                    'onclick'   => 'formControl.delete();',
                    'class'     => 'delete'
                ))
        );

        return parent::_prepareLayout();
    }

    /**
     * Retrieve Back Button HTML
     *
     * @return string
     */
    public function getBackButtonHtml()
    {
        return $this->getChildHtml('back_button');
    }

    /**
     * Retrieve Reset Button HTML
     *
     * @return string
     */
    public function getResetButtonHtml()
    {
        return $this->getChildHtml('reset_button');
    }

    /**
     * Retrieve Save Button HTML
     *
     * @return string
     */
    public function getSaveButtonHtml()
    {
        return $this->getChildHtml('save_button');
    }

    /**
     * Return edit flag for block
     *
     * @return boolean
     */
    public function getEditMode()
    {
        return $this->_editMode;
    }

    /**
     * Return header text for form
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->getEditMode()) {
            return Mage::helper('styleicons')->__('Edit Entry');
        }

        return  Mage::helper('styleicons')->__('New Entry');
    }

    /**
     * Return form block HTML
     *
     * @return string
     */
    public function getForm()
    {
        return $this->getLayout()
            ->createBlock('styleicons/adminhtml_entry_edit_form')
            ->toHtml();
    }

    /**
     * Return action url for form
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save');
    }

    /**
     * Return delete url for customer group
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', array('id' => $this->getRequest()->getParam('id')));
    }

}
