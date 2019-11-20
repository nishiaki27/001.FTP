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
require_once CLASS_REALDIR . 'helper/SC_Helper_Purchase.php';

require_once MODULE_REALDIR . 'mdl_pg_mulpay/class/utils/LC_Mdl_PG_MULPAY_Export.php';

/**
 * 商品購入関連のヘルパークラス(拡張).
 *
 * LC_Helper_Purchase をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Helper
 * @author Kentaro Ohkouchi
 * @version $Id: SC_Helper_Purchase_Ex.php 2674 2011-06-30 09:26:02Z takashi $
 */
class SC_Helper_Purchase_Ex extends SC_Helper_Purchase {
    function getPaymentsByPrice($total, $deliv_id) {
        $arrPayment = parent::getPaymentsByPrice($total, $deliv_id);
        return LC_Mdl_PG_MULPAY_Export::customizeGetPaymentsByPrice($arrPayment);
    }

    function completeOrder($orderStatus = ORDER_NEW) {
        parent::completeOrder($orderStatus);
        LC_Mdl_PG_MULPAY_Export::customizeCompleteOrder($orderStatus);
    }
}
?>
