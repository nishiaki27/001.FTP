<?php
/*
 * This file is part of EC-CUBE PAYMENT MODULE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.net/product/payment/
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
require_once CLASS_REALDIR . 'pages/admin/order/LC_Page_Admin_Order_Edit.php';

require_once MODULE_REALDIR . 'mdl_pg_mulpay/class/utils/LC_Mdl_PG_MULPAY_Export.php';

/**
 * 受注修正 のページクラス(拡張).
 *
 * LC_Page_Admin_Order_Edit をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Order_Edit_Ex_2.11.5.php 3911 2012-02-29 17:03:40Z takashi $
 */
class LC_Page_Admin_Order_Edit_Ex extends LC_Page_Admin_Order_Edit {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();

        LC_Mdl_PG_MULPAY_Export::customizePageAdminOrderEditInit($this);
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        parent::process();
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    function action() {
        parent::action();

        LC_Mdl_PG_MULPAY_Export::customizePageAdminOrderEditAction($this);
    }

    function lfInitParam(&$objFormParam) {
        // 検索条件のパラメータを初期化
        parent::lfInitParam($objFormParam);

        LC_Mdl_PG_MULPAY_Export::customizePageAdminOrderEditInitParam($objFormParam);
    }

    function lfCheckError(&$objFormParam) {
    	$arrErr = parent::lfCheckError($objFormParam);

        return LC_Mdl_PG_MULPAY_Export::customizePageAdminOrderEditCheckError($this, $objFormParam, $arrErr);
    }

    function doRegister($order_id, &$objPurchase, &$objFormParam, &$message, &$arrValuesBefore) {
        $result = LC_Mdl_PG_MULPAY_Export::customizePageAdminOrderEditDoRegister(
                      $this, $order_id, $objPurchase, $objFormParam, $message);
        if ($result < 0) {
            return $result;
        }

        return parent::doRegister($order_id, $objPurchase, $objFormParam, $message, $arrValuesBefore);
    }
}
?>
