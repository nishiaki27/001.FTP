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
require_once CLASS_EX_REALDIR . 'page_extends/shopping/LC_Page_Shopping_Multiple_Ex.php';

/**
 * お届け先の複数指定 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_TwoClick_Multiple.php 2449 2011-05-23 08:53:36Z takashi $
 */
class LC_Page_TwoClick_Multiple extends LC_Page_Shopping_Multiple_Ex {

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();

        $this->tpl_mainpage = 'twoClick/multiple.tpl';
    }

    /**
     * Page のアクション.
     *
     * confirmのリダイレクト先を2クリック決済確認画面へ変更
     * 
     * @return void
     */
    function action() {
        $objSiteSess = new SC_SiteSession_Ex();
        $objCartSess = new SC_CartSession_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objCustomer = new SC_Customer_Ex();
        $objFormParam = new SC_FormParam_Ex();

        $this->tpl_uniqid = $objSiteSess->getUniqId();

        $this->addrs = $this->getDelivAddrs($objCustomer, $objPurchase,
                                            $this->tpl_uniqid);
        $this->tpl_addrmax = count($this->addrs);
        $this->lfInitParam($objFormParam);

        $objPurchase->verifyChangeCart($this->tpl_uniqid, $objCartSess);

        switch ($this->getMode()) {
            case 'confirm':
                $objFormParam->setParam($_POST);
                $this->arrErr = $this->lfCheckError($objFormParam);
                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    // フォームの情報を一時保存しておく
                    $_SESSION['multiple_temp'] = $objFormParam->getHashArray();
                    $this->saveMultipleShippings($this->tpl_uniqid, $objFormParam,
                                                 $objCustomer, $objPurchase,
                                                 $objCartSess);
                    $objSiteSess->setRegistFlag();
                    // 2クリック決済確認ページへ遷移する
                    SC_Response_Ex::sendRedirect('confirm.php');
                    exit;
                }
                break;

        default:
            $this->setParamToSplitItems($objFormParam, $objCartSess);
        }

        // 前のページから戻ってきた場合
        if ($_GET['from'] == 'multiple') {
            $objFormParam->setParam($_SESSION['multiple_temp']);
        }

        $this->arrForm = $objFormParam->getFormParamList();
    }
}
?>
