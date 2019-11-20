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
require_once(MDL_PG_MULPAY_CLASS_REALDIR . 'client/LC_Mdl_PG_MULPAY_Client_Entry.php');
/**
 * iD決済 EntryTranを実行する
 *
 */
class LC_Mdl_PG_MULPAY_Client_Entry_Netid extends LC_Mdl_PG_MULPAY_Client_Entry {
    /**
     * EntryTran名を取得する
     *
     * @return stirng EntryTran名
     */
    function getEntryTranName() {
        return 'EntryTranNetid.idPass';
    }

    /**
     * リクエストパラメータを取得する
     * 
     * @param array $arrData 受注情報
     * @return array リクエストパラメータ
     */
    function getSendRequestParam($arrData) {
        $arrSendData = parent::getSendRequestParam($arrData);

        $objPG =& LC_Mdl_PG_MULPAY::getInstance();
        $arrSendData['JobCd'] = $objPG->getJobCd('netid_jobcd');
        $arrSendData['RetURL'] = $this->getModuleRetUrl(array('netid_order_id' => $arrData['order_id'],
                                                              'mode' => 'center'));

        return $arrSendData;
    }
}
?>
