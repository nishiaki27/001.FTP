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
require_once MODULE_REALDIR . "mdl_pg_mulpay/class/pages/2click/LC_Page_TwoClick_LoadPaymentModule.php";

/**
 * 決済モジュールの呼び出しを行うクラス(拡張).
 *
 * LC_Page_TwoClick_LoadPaymentModule をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author Kentaro Ohkouchi
 * @version $Id: LC_Page_TwoClick_LoadPaymentModule_Ex.php 2449 2011-05-23 08:53:36Z takashi $
 */
class LC_Page_TwoClick_LoadPaymentModule_Ex extends LC_Page_TwoClick_LoadPaymentModule {

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
}
?>
