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

/**
 * ハッシュ生成（ﾘﾝｸﾀｲﾌﾟ決済ﾊｯｼｭ区分：EC-CUBE用）
 *
 */
function setPaygentHash($arrSend, $hash_key) {
    // create hash hex string
    $default = array(
        'payment_class'=>'',
        'hash_key'=>$hash_key,
        'paygent_mark'=>'paygent2006',
        'trading_id'=>'',
        'id'=>'',
        'payment_type'=>'',
        'seq_merchant_id'=>'',
        'payment_term_day'=>'',
        'use_card_conf_number'=>'',
        'fix_params'=>'',
        'inform_url'=>'',
        'payment_term_min'=>'',
        'customer_id'=>'',
        'threedsecure_ryaku'=>'',
    );
	$org_str = '';
    foreach ($default as $key=>$value) {
    	$org_str .= isset($arrSend[$key]) ? $arrSend[$key]:$value;
    }
    if (function_exists("hash")) {
        $hash_str = hash("sha256", $org_str);
    } elseif (function_exists("mhash")) {
        $hash_str = bin2hex(mhash(MHASH_SHA256, $org_str));
    } else {
        return;
    }

    // create random string
    $rand_char = array('a','b','c','d','e','f','A','B','C','D','E','F','0','1','2','3','4','5','6','7','8','9');
    for ($i = 0; ($i < 20 && rand(1,10) != 10); $i++) {
        $rand_str .= $rand_char[rand(0, count($rand_char)-1)];
    }

    return $hash_str. $rand_str;
}
?>
