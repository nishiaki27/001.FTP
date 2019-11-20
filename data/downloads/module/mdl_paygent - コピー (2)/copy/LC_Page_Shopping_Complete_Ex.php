<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright (c) 2006 PAYGENT Co.,Ltd. All rights reserved.
 *
 * https://www.paygent.co.jp/
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

/**
 * ご注文完了 のページクラス(拡張).
 *
 * LC_Page_Shopping_Complete をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Shopping_Complete_Ex.php 20764 2011-03-22 06:26:40Z nanasess $
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
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $order_id = isset($_SESSION['paygent_order_id']) ? $_SESSION['paygent_order_id'] : '';
        if ($order_id !== '') {
            // その他情報の取得
            $arrResults = $objQuery->getAll("SELECT memo02, memo05 FROM dtb_order WHERE order_id = ? ", array($order_id));

            if (count($arrResults) > 0) {
                if (isset($arrResults[0]["memo02"]) || isset($arrResults[0]["memo05"])) {
                    // 完了画面で表示する決済内容
                    $arrOther = unserialize($arrResults[0]["memo02"]);
                    // 完了画面から送信する決済内容
                    $arrModuleParam = unserialize($arrResults[0]["memo05"]);

                    // データを編集
                    foreach($arrOther as $key => $val){
                        // URLの場合にはリンクつきで表示させる
                        if (preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $val["value"])) {
                            $arrOther[$key]["value"] = "<a href='#' onClick=\"window.open('". $val["value"] . "'); \" >" . $val["value"] ."</a>";
                        }
                    }

                    $this->arrOther = $arrOther;
                    $this->arrModuleParam = $arrModuleParam;
                }
            }
        }

        unset($_SESSION['paygent_order_id']);

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
