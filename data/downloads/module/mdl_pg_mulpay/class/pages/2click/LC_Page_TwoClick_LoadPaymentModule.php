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
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';

require_once MODULE_REALDIR . 'mdl_pg_mulpay/class/LC_Mdl_PG_MULPAY.php';

/**
 * 決済モジュールの呼び出しを行うクラス.
 *
 * 決済フローの妥当性検証は, トランザクションID等を使用して, 決済モジュール側で
 * 行う必要がある.
 *
 * @package Page
 * @author Kentaro Ohkouchi
 * @version $Id: LC_Page_TwoClick_LoadPaymentModule.php 2449 2011-05-23 08:53:36Z takashi $
 */
class LC_Page_TwoClick_LoadPaymentModule extends LC_Page_Ex {

    // }}}
    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {

        $payment_id = $this->getPaymentId();
        if ($payment_id === false) {
            LC_Mdl_PG_MULPAY::printLog("TwoClick_LoadModule PAGE_ERROR");
            SC_Utils_Ex::sfDispSiteError(PAGE_ERROR, "", true);
            return;
        }

        $module_path = $this->getModulePath($payment_id);
        if ($module_path === false) {
            LC_Mdl_PG_MULPAY::printLog("TwoClick_LoadModule モジュールファイルの取得に失敗しました。");
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                                      "モジュールファイルの取得に失敗しました。<br />この手続きは無効となりました。");
            return;
        }
        require_once $module_path;
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /**
     * 支払IDをキーにして, 決済モジュールのパスを取得する.
     *
     * 決済モジュールが取得できた場合は, require 可能な決済モジュールのパスを返す.
     * 支払IDが無効な場合, 取得したパスにファイルが存在しない場合は false
     *
     * @param integer $payment_id 支払ID
     * @return string|boolean 成功した場合は決済モジュールのパス;
     *                        失敗した場合 false
     */
    function getModulePath($payment_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $sql = <<< __EOS__
            SELECT module_path
              FROM dtb_payment
             WHERE payment_id = ?
__EOS__;
        $module_path = $objQuery->getOne($sql, array($payment_id));
        if (file_exists($module_path)) {
            return $module_path;
        }
        return false;
    }

    /**
     * 支払ID を取得する.
     *
     * 以下の順序で支払IDを取得する.
     *
     * 1. $_SESSION['payment_id']
     * 2. $_POST['payment_id']
     * 3. $_GET['payment_id']
     *
     * 支払IDが取得できない場合は false を返す.
     *
     * @access private
     * @return integer|boolean 支払IDの取得に成功した場合は支払IDを返す;
     *                         失敗した場合は, false を返す.
     */
    function getPaymentId() {
        if (isset($_SESSION['payment_id'])
            && !SC_Utils_Ex::isBlank($_SESSION['payment_id'])
            && SC_Utils_Ex::sfIsInt($_SESSION['payment_id'])) {
            return $_SESSION['payment_id'];
        }

        if (isset($_POST['payment_id'])
            && !SC_Utils_Ex::isBlank($_POST['payment_id'])
            && SC_Utils_Ex::sfIsInt($_POST['payment_id'])) {
            return $_POST['payment_id'];
        }

        if (isset($_GET['payment_id'])
            && !SC_Utils_Ex::isBlank($_GET['payment_id'])
            && SC_Utils_Ex::sfIsInt($_GET['payment_id'])) {
            return $_GET['payment_id'];
        }
        return false;
    }

    /**
     * 決済モジュールから遷移する場合があるため, トークンチェックしない.
     */
    function doValidToken() {
        // nothing.
    }
}
?>
