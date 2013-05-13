<?php
/**
 */
require_once 'Mage/Cms/controllers/PageController.php';

class Jarlssen_CmsLayoutHandles_PageController extends Mage_Cms_PageController
{
    /**
     * @var string Layout handle name for custom CMS page type.
     */
    protected $_layoutHandle = null;

    /**
     * We overload/overwrite the CMS page controller (which is used only when viewing/rendering a CMS page).  We
     * do this so we can capture the CMS page, then get its PAGE TYPE.
     * From here, we adjust the layout by inserting the appropriate block/template into the layout for the given
     * type.
     */
    public function viewAction() {

        /**
         * First check to see if we have a standard page -- if so, just let it pass through and render like
         * normal.  Otherwise, we need to do more checking to apply a custom handle.
         */
        $pageId = $this->getRequest()->getParam( 'page_id', $this->getRequest()->getParam( 'id', false ));
        $cmsPage = Mage::getModel( 'cms/page' )->load( $pageId );

        /**
         * Standard page -- just render it and get out of here before anyone asks for ID.
         */
        if( $cmsPage->getPageType() == '0' ) {
            if( !Mage::helper( 'cms/page' )->renderPage( $this, $pageId )) {
                $this->_forward( 'noRoute' );
            }
            return;
        }

        /**
         * Handle non-standard page types.  Type list in source model.
         */
        $sourceModel = Mage::getSingleton( 'cmslayouthandles/source_pagetype' );
        $layoutUpdateHandle = $sourceModel->getLayoutUpdateHandle( $cmsPage->getPageType( ));

        /**
         * If we have one, add the custom layout handle based upon the type.  It will be applied and rendered
         * along with the others (cms_page, default, or any custom ones defined in the CMS page parameters)
         * in the standard rendering method [renderPage] below.
         */
        $this->_layoutHandle = $layoutUpdateHandle;

        /**
         * Store the CMS page object in the registry for convenience.  The custom layouts may contain blocks which
         * depend upon this.  Also, our observer.
         */
        Mage::register( 'cms_page', $cmsPage );

        if( !Mage::helper( 'cms/page' )->renderPage( $this, $pageId )) {
            $this->_forward( 'noRoute' );
        }

        return;
    }

    /**
     * Overload this to add our custom CMS type layout -- it has to be done this way instead of in the above action method --
     * why, I am uncertain, but this works.  I believe it has to do with the ORDER of the handles being rendered or maybe vegetables.
     *
     * @return Jarlssen_CmsLayoutHandles_PageController
     */
    public function addActionLayoutHandles() {
        parent::addActionLayoutHandles();
        if( $this->_layoutHandle ) {
            $this->getLayout()->getUpdate()->addHandle( $this->_layoutHandle );
        }
        return $this;
    }
}