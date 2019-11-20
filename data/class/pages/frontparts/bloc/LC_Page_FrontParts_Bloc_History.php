<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2010 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

// {{{ requires
require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc.php';

/**
 * 商品閲覧履歴 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id:LC_Page_FrontParts_Bloc_History.php 15532 2007-08-31 14:39:46Z nanasess $
 */
class LC_Page_FrontParts_Bloc_History extends LC_Page_FrontParts_Bloc {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $bloc_file = 'history.tpl';
        $this->setTplMainpage($bloc_file);
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        if (defined("MOBILE_SITE") && MOBILE_SITE) {
            $objView = new SC_MobileView();
        } else {
            $objView = new SC_SiteView();
        }
        $objSiteInfo = $objView->objSiteInfo;

        // 基本情報を渡す
/*
        $objSiteInfo = new SC_SiteInfo();
        $this->arrInfo = $objSiteInfo->data;
*/

        // 商品閲覧履歴表示
        $this->arrHistoryProducts = $this->lfGetHistory();

        $objView->assignobj($this);
        $objView->display($this->tpl_mainpage);
    }

    /**
     * モバイルページを初期化する.
     *
     * @return void
     */
    function mobileInit() {
         $this->tpl_mainpage = MOBILE_TEMPLATE_DIR . "frontparts/"
            . BLOC_DIR . 'arrival.tpl';
    }

    /**
     * Page のプロセス(モバイル).
     *
     * @return void
     */
    function mobileProcess() {
        $this->process();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    // 商品閲覧履歴検索
    function lfGetHistory(){
        $objQuery = new SC_Query();

        $cnt = 0;
        // ページを再読み込み後に表示
        if (isset($_COOKIE['product'])) {

            $objProduct = new SC_Product_Ex();
            foreach ($_COOKIE['product'] as $name => $value) {

                // DBから一覧表示用商品情報取得

//                $arrRet = $objQuery->select("*", "dtb_products", "product_id =".$value);
//                $arrHistoryProducts[$cnt] = $arrRet[0];

                // 商品詳細を取得
                $this->arrProduct = $objProduct->getDetail($value);
                $arrHistoryProducts[$cnt] = $this->arrProduct;

                $cnt = $cnt+1;
            }
        }

        return $arrHistoryProducts;
    }
}
?>
