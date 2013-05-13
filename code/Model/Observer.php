<?php
/**
 */
class Jarlssen_CmsLayoutHandles_Model_Observer
{
    /**
     * This captures the CMS edit page in the backend before it is rendered and adds the page type field.
     *
     * @param Varien_Event_Observer $observer
     * @return Jarlssen_CmsLayoutHandles_Model_Observer
     */
    public function prepareForm( Varien_Event_Observer $observer ) {

        $_form = $observer->getEvent()->getForm();
        $_page = Mage::registry( 'cms_page' );

        /**
         * Create a fieldset to put our page type selector in.  Maybe this is overkill but we may to expand this later
         * to add other input fields related to page types (tagging, for example).
         */
        $_fieldset = $_form->addFieldset(
            'pagetype_fieldset',
            array(
                'legend'    => Mage::helper( 'cmslayouthandles')->__( 'Page Type' ),
                'class'     => 'fieldset-wide',
            )
        );

        /**
         * Pull our page types from our backend source class.
         */
        $_optionsModel = Mage::getSingleton( 'cmslayouthandles/source_pagetype' );

        /**
         * Add it to our form's brand new fieldset.
         */
        $_fieldset->addField( 'page_type', 'select', array(
            'name'      => 'page_type',
            'title'     => Mage::helper( 'cmslayouthandles' )->__( 'Page Type' ),
            'label'     => Mage::helper( 'cmslayouthandles' )->__( 'Page Type' ),
            'options'   => $_optionsModel->toFieldOptionArray( ),
            'required'  => false,
        ));

        return $this;
    }

    /**
     * Called before a page is rendered -- we need to remove pre-defined "default" layout handles for our non-standard
     * page types because they always take priority and destroy all of our hard work (meaning, our custom layout handles in
     * layout.xml files).
     *
     * @param Varien_Event_Observer $observer
     * @return Jarlssen_TagCMS_Model_Observer
     */
    public function onCmsRenderPage( Varien_Event_Observer $observer ) {
        /**
         * Our arguments from the method.  We need both.  Here is why:
         *  - Action:  In order to fetch layout and strip out bad handles.
         *  - CmsPage:  We need to know the type so we only do this application to non-standard CMS pages.
         */
        $action = $observer->getEvent()->getControllerAction();
        $cmsPage = $observer->getEvent()->getPage();

        /**
         * Obtain this list from Mage_Page/config.xml -- it defines all root page templates/layouts.
         */
        $removeHandles = Mage::getModel( 'page/config' )->getPageLayoutHandles();

        /**
         * From the page, get the page type.  If it is <> standard (0), then we should strip out the auto-added
         * handles from the $removeHandles list because they are not only extraneous but they also interfere with
         * what we are doing.
         */
        if( $cmsPage->getPageType() != Jarlssen_CmsLayoutHandles_Model_Source_Pagetype::STANDARD ) {
            foreach( $removeHandles as $handle ) {
                $action->getLayout()->getUpdate()->removeHandle( $handle );
            }
        }

        return $this;
    }
}
