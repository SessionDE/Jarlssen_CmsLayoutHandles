<?php
/**
 */
class Jarlssen_CmsLayoutHandles_Model_Source_Pagetype
{
    /**
     * These constants define the possible set of page types we support.  Leave '0' alone as the standard type;  the rest can be customized as desired.
     */
    const STANDARD = 0;
    const ARTIST   = 1;
    const ALBUM    = 2;
    const GENRE    = 3;

    /**
     * This will be the prefix for our custom layout handles -- these are the handles we actually USE in our layout XML files to structure them.  I generally recommend using
     * all caps here to avoid any possible conflict with controllers in other modules, but it's your shop, so feel free to throw caution to the wind and drive with the
     * caps lock key off.
     */
    const LAYOUT_HANDLE_PREFIX = 'CMS_TYPE_';

    /**
     * This will convert our user-unfriendly integer representations into labels we can localize into any of the commonly spoken languages on Earth.
     *
     * @return array
     */
    public function toFieldOptionArray() {

        $_options = array(
            self::STANDARD     =>  Mage::helper( 'cmslayouthandles' )->__( 'Magento Standard Page' ),
            self::ARTIST       =>  Mage::helper( 'cmslayouthandles' )->__( 'Artist Page' ),
            self::ALBUM        =>  Mage::helper( 'cmslayouthandles' )->__( 'Album Page' ),
            self::GENRE        =>  Mage::helper( 'cmslayouthandles' )->__( 'Genre Page' ),
        );

        return $_options;
    }

    /**
     * This method will simply return the complete layout handle for a given page type as a string.
     *
     * @param int $pageType The page type (as integer); see above const definitions.
     * @return string
     */
    public function getLayoutUpdateHandle( $pageType ) {
        switch( $pageType ) {
            case self::ARTIST:
                return self::LAYOUT_HANDLE_PREFIX . 'ARTIST';
                break;
            case self::ALBUM:
                return self::LAYOUT_HANDLE_PREFIX . 'ALBUM';
                break;
            case self::GENRE:
                return self::LAYOUT_HANDLE_PREFIX . 'GENRE';
                break;
            case self::STANDARD:
            default:
                return false;
                break;
        }
    }
}
