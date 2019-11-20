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
require_once CLASS_REALDIR . 'pages/shopping/LC_Page_Shopping_Complete.php';

require_once MODULE_REALDIR . 'mdl_pg_mulpay/class/utils/LC_Mdl_PG_MULPAY_Export.php';

/**
 * ご注文完了 のページクラス(拡張).
 *
 * LC_Page_Shopping_Complete をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Shopping_Complete_Ex.php 2449 2011-05-23 08:53:36Z takashi $
 */
class LC_Page_Shopping_Complete_Ex extends LC_Page_Shopping_Complete {

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
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        parent::action();

        LC_Mdl_PG_MULPAY_Export::customizeShoppingComplate($this);
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
