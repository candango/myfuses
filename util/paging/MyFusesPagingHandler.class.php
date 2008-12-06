<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * MyFusesPagingHandler - MyFusesPagingHandler.class.php
 *
 * Handles Pagination
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the Lgroupicense is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * The Original Code is Iflux Paging Handler class part of Iflux Framework .
 * 
 * The Initial Developer of the Original Code is Flávio Gonçalves Garcia.
 * Portions created by Flávio Gonçalves Garcia are Copyright (C) 2004 - 2005.
 * All Rights Reserved.
 * 
 * Contributor(s): Flávio Gonçalves Garcia.
 *                 Luiz Fernando Siroteau Serique Júnior
 *
 * @category   iflux
 * @package    iflux.util
 * @author     Flávio Gonçalves Garcia <fpiraz@gmail.com>
 * @copyright  Copyright (c) 2004 - 2005 iFLUX Group <http://www.iflux.org/>
 * @license    http://www.mozilla.org/MPL/MPL-1.1.html  MPL 1.1
 * @version    CVS: $Id: IfluxPagingHandler.class.php,v 1.1 2008/01/24 13:03:43 86559109100 Exp $
 * @link       *
 * @see        *
 * @since      File available since Release 0.0.1
 * @deprecated *
 */
class MyFusesPagingHandler {
    
    private $_queryCount;
    private $_regsPerPage;
    private $_pagesPerBlocks;
    private $_currentPage;
    private $_urlVariable;
    
    private $_urlQueryString;
    private $_href;
    
    private $_pageCount;
    private $_regsInLastPage;
    
    private $_blockCount;
    private $_pagesInLastBlock;
    
    
    private $_currentFirtsReg;
    private $_currentLastReg;
    
    private $_currentBlock;
    
    private $_currentFirtsPage;
    private $_currentLastPage;
    
    
    public static function doPagination( $queryCount, $regsPerPage, $pagesPerBlocks, $currentPage = 1, $urlVariable = 'pag' ) {
       
        if ( is_null( $currentPage ) ){
            $currentPage = 1;
        }
        
        // setting initial properties 
        $this->_queryCount = $queryCount;
        $this->_regsPerPage = $regsPerPage;
        $this->_pagesPerBlocks = $pagesPerBlocks;
        $this->_currentPage = $currentPage;
        $this->_urlVariable = $urlVariable;
        
        // TODO more href handler needed
        // definining href
        if ( !( strpos(  $_SERVER[ 'QUERY_STRING' ], $urlVariable ) === false ) ) {
            $qStrPos = 0;
            $vurlQueryString = explode( "&" , $_SERVER[ 'QUERY_STRING' ] );
            for( $i=0; $i < count( $vurlQueryString ); $i++ ) {
                if ( !( strpos( $vurlQueryString[ $i ], $urlVariable ) === false ) ) {
                    $qStrPos = $i;
                    $i = count( $vurlQueryString );
                }
            }
            $this->_urlQueryString = str_replace( $vurlQueryString[ $qStrPos ] . "&" , "" , $_SERVER['QUERY_STRING'] ) ;
        }
        else {
            $this->_urlQueryString = $_SERVER['QUERY_STRING'];
        }
        
        while( substr( $this->_urlQueryString, strlen( $this->_urlQueryString ) - 1, 1 ) == '&'  ) {
            $this->_urlQueryString = substr( $this->_urlQueryString, 0 , strlen( $this->_urlQueryString ) - 1 );
        }
        
        if ( ( substr( $this->_urlQueryString, strlen( $this->_urlQueryString ) - 1, 1 ) == '&' ) || ( strlen( $this->_urlQueryString ) == 0 ) ) {
            $this->_urlQueryString .= $this->_urlVariable . '=' ;
        }
        else{
            $this->_urlQueryString .=  '&' . $this->_urlVariable . '=' ;
        }
        
        $this->_href = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?" . $this->_urlQueryString;
        
        // setting page count
        if( $queryCount == 0 ){
            $this->_pageCount = 1;
        }
        else {
            $this->_pageCount = (int) ( $this->_queryCount / $this->_regsPerPage ) + 
                ( ( $this->_queryCount % $this->_regsPerPage ) > 0 ? 1 : 0 );
        }
        
        if ( $this->_pageCount < $this->_currentPage || $this->_currentPage == 0 ) {
            $this->_currentPage = $this->_pageCount; 
        }
        
        // setting registers in last page
        $this->_regsInLastPage = ( int ) 
            ( ( $this->_queryCount % $this->_regsPerPage ) > 0 ? ( $this->_queryCount % $this->_regsPerPage ) : 
                $this->_regsPerPage );
        
        // setting block count
        $this->_blockCount = (int) ( $this->_pageCount / $this->_pagesPerBlocks ) + 
            ( ( $this->_pageCount % $this->_pagesPerBlocks ) > 0 ? 1 : 0 );
        
        // setting block count
        if ( $this->_pageCount <  $this->_pagesPerBlocks ) {
            $this->_pagesInLastBlock = $this->_pageCount;
        }
        else {
            $this->_pagesInLastBlock = (int) 
                ( ( $this->_pageCount % $this->_pagesPerBlocks ) > 0 ? ( $this->_pageCount % $this->_pagesPerBlocks ) : 
                    $this->_pagesPerBlocks );
        }
        
        // setting current first register
        $this->_currentFirtsReg = ( ( $this->_currentPage * $this->_regsPerPage ) + 1 ) - $this->_regsPerPage;
        // setting current last register
        if ( $this->_currentPage == $this->_pageCount ) {
            $this->_currentLastReg = $this->_currentFirtsReg + ( $this->_regsInLastPage - 1 );
        }
        else {
            $this->_currentLastReg = ( $this->_currentPage * $this->_regsPerPage );
        }
        
        
        // setting current block
        if ( $this->_currentPage <= $this->_pagesPerBlocks ) {
            $this->_currentBlock = 1;
        }
        else {
            $this->_currentBlock = (int) ( $this->_currentPage / $this->_pagesPerBlocks ) + 
                ( ( $this->_currentPage % $this->_pagesPerBlocks ) > 0 ? 1 : 0 );
        }
        
        // setting current first page
        $this->_currentFirtsPage = ( ( $this->_currentBlock * $this->_pagesPerBlocks ) + 1 ) - $this->_pagesPerBlocks;
        // setting current last register
        if ( $this->_currentBlock == $this->_blockCount ) {
            $this->_currentLastPage = $this->_currentFirtsPage + ( $this->_pagesInLastBlock - 1 );
        }
        else {
            $this->_currentLastPage = ( $this->_currentBlock * $this->_pagesPerBlocks );
        }
        
    }
    
    
    
    function getLink( $page ) {
        if( $page == $this->_currentPage ) {
            return $page;
        }
        else{
            return '<a href="' . $this->_href. $page . '&" >' . $page . '</a>';
        }
    }
    
    function getQueryCount() {
        return $this->_queryCount;
    }
    
    function getRegsPerPage(){
        return $this->_regsPerPage;
    }
    
    function getPagesPerBlocks(){
        return $this->_pagesPerBlocks;
    }
    
    function getCurrentPage(){
        return $this->_currentPage;
    }
    
    function getUrlVariable(){
        return $this->_urlVariable;
    }
    
    function getQueryString(){
        return $this->_urlQueryString;
    }
    
    function getHref(){
        return $this->_href;
    }
    
    function getPageCount(){
        return $this->_pageCount;
    }
    
    function getRegsInLastPage(){
        return $this->_regsInLastPage;
    }
    
    function getBlockCount(){
        return $this->_blockCount;
    }
    
    function getPagesInLastBlock(){
        return $this->_pagesInLastBlock;
    }
    
    function getCurrentFirtsReg(){
        return $this->_currentFirtsReg;
    }
    
    function getCurrentLastReg(){
        return $this->_currentLastReg;
    }
    
    function getCurrentBlock(){
        return $this->_currentBlock;
    }
    
    function getCurrentFirtsPage(){
        return $this->_currentFirtsPage;
    }
    
    function getCurrentLastPage(){
        return $this->_currentLastPage;
    }
    
    function getLinkArray() {
        $linkArray = array();
        for ( $i = ( $this->_currentFirtsPage - 1 ); $i < $this->_currentLastPage; $i++ ) {
            
            $linkArray[ count( $linkArray ) ] = $this->getLink( $i + 1 );
            
        }
        return $linkArray;
    }
    
    function getFirstPageLink( $label ) {
        if ( $this->_currentPage == 1 ) {
            return $label;
        }
        else{
            return '<a href="' . $this->_href. '1&" >' . $label . '</a>';
        }
    }
    
    function getPreviousBlockLink( $label ) {
        if ( $this->_currentBlock == 1 ) {
            return $label;
        }
        else{
            return '<a href="' . $this->_href. ( $this->_currentFirtsPage - 1 ) . '&" >' . $label . '</a>';
        }
    }
    
    function getLastPageLink( $label ) {
        if ( $this->_currentPage == $this->_pageCount ) {
            return $label;
        }
        else{
            return '<a href="' . $this->_href. $this->_pageCount . '&" >' . $label . '</a>';
        }
    }
    
    function getNextBlockLink( $label ) {
        if ( $this->_currentBlock == $this->_blockCount ) {
            return $label;
        }
        else{
            return '<a href="' . $this->_href. ( $this->_currentLastPage + 1 ) . '&" >' . $label . '</a>';
        }
    }
    
}