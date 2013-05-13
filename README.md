CmsLayoutHandles
================

Add custom layout handles by CMS page type.

Example (in layout XML file):
=============================

<CMS_TYPE_ARTIST>

  <!-- Restructure the page like a boss -->
	<reference name="root">
            <action method="addBodyClass"><classname>cms-artist-page</classname></action>
            <action method="setTemplate"><template>page/2columns-right_ap.phtml</template></action>
            <action method="setIsHandle"><applied>1</applied></action>
            <remove name="breadcrumbs"/>
            <block type="core/template" name="right" as="right" template="cms/artist_page_sidebar.phtml"/>
            <block type="core/template" name="similar_artists" as="other_artists" template="catalog/product/view/similar_artists.phtml"/>
	</reference>
	
	<!-- Add some exclusive JS or CSS for this page type only; make other page types jealous. -->
	<reference name="head">
    	<action method="addItem">
                <type>skin_css</type>
                <name>css/jquery.isotope.css</name>
                <params />
            </action>
    </reference>
    
</CMS_TYPE_ARTIST>
