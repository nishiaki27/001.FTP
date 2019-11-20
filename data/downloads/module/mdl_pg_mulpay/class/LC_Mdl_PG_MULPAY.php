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
/**
 * PGマルチペイメントサービス決済モジュール
 *
 */

require_once MODULE_REALDIR . 'mdl_pg_mulpay/inc/include.php';

/*
 * 決済モジュールがサポートする決済方法
 *
 * この値は、テーブルdtb_paymentのmemo01カラムに設定する。
 */
define('MDL_PG_MULPAY_PAYMENT_CREDIT',	'1');
define('MDL_PG_MULPAY_PAYMENT_CONVENI', '2');
define('MDL_PG_MULPAY_PAYMENT_SUICA',	'3');
define('MDL_PG_MULPAY_PAYMENT_EDY',		'4');
define('MDL_PG_MULPAY_PAYMENT_ATM',		'5');
define('MDL_PG_MULPAY_PAYMENT_NETBANK',	'6');
define('MDL_PG_MULPAY_PAYMENT_PAYPAL',	'7');
define('MDL_PG_MULPAY_PAYMENT_NETID',	'8');
define('MDL_PG_MULPAY_PAYMENT_WEBMONEY','9');
define('MDL_PG_MULPAY_PAYMENT_AU',	'10');
define('MDL_PG_MULPAY_PAYMENT_DOCOMO',  '11');
define('MDL_PG_MULPAY_PAYMENT_TOKEN',   '98');
define('MDL_PG_MULPAY_PAYMENT_NOT_MODULE',	'-1');
define('MDL_PG_MULPAY_PAYMENT_OTHER_MODULE','-2');
define('MDL_PG_MULPAY_PAYMENT_ERROR','-3');

// $_SESSIONのキー
define('MDL_PG_MULPAY','MDL_PG_MULPAY');

/* 
 *  決済方法ごとに、PGから購入者へのメール送信の有無を制御する。
 *
 *  デフォルトは全てoffとする。
 *  edy,suicaはMailAddressパラメータ必須なので制御不可とする。
 */
if (file_exists(MDL_PG_MULPAY_PATH . 'conf/mdl_pg_mulpay_config.php') === TRUE) {
    require_once(MDL_PG_MULPAY_PATH . 'conf/mdl_pg_mulpay_config.php');
}
$mdl_pg_mulpay_config_defaults = array(
                                       'MDL_PG_MULPAY_CONF_PGMAIL_ATM' => false,
                                       'MDL_PG_MULPAY_CONF_PGMAIL_NETBANK' => false,
                                       'MDL_PG_MULPAY_CONF_PGMAIL_PAYPAL' => false,
                                       'MDL_PG_MULPAY_CONF_PGMAIL_CONVENI' => false,
                                       'MDL_PG_MULPAY_CONF_PGMAIL_WEBMONEY' => false,
                                       'MDL_PG_MULPAY_CONF_PGMAIL_NETID' => false,

                                       'MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_LAWSON' => false,
                                       'MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_FAMILYMART' => false,
                                       'MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_SUNKUS' => false,
                                       'MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_CIRCLEK' => false,
                                       'MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_MINISTOP' => false,
                                       'MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_DAILYYAMAZAKI' => false,
                                       'MDL_PG_MULPAY_CONF_PGMAIL_CONVENI_SEVENELEVEN' => false,

                                       //2.3.4以前では、定数USE_POINTが存在しない。
                                       'USE_POINT' => false,
                                       );
foreach ($mdl_pg_mulpay_config_defaults as $key => $val) {
    if (!defined($key)) {
        define($key, $val);
    }
}

/*
 * モジュール設定情報
 */
class LC_Mdl_PG_MULPAY {
    /** サブデータを保持する変数 */
    var $subData = null;

    /** モジュール情報 */
    var $moduleInfo = array(
        'paymentName' => 'クレジット決済',
        'moduleName'  => 'PGマルチペイメントサービス決済モジュール',
        'moduleCode'  => 'MDL_PG_MULPAY',
        'moduleVersion' => MDL_PG_MULPAY_VERSION, // バージョン情報を埋め込む
    );

    /**
     * テーブル拡張設定.拡張したいテーブル情報を配列で記述する.
     * $updateTable = array(
     *     array(
     *       'name' => 'テーブル名',
     *       'cols' => array(
     *          array('name' => 'カラム名', 'type' => '型名'),
     *          array('name' => 'カラム名', 'type' => '型名'),
     *       ),
     *     ),
     *     array(
     *       ...
     *     ),
     *     array(
     *       ...
     *     ),
     * );
     */
    var $updateTable = array(
        // dtb_paymentの更新
        array(
            'name' => 'dtb_payment',
            'cols'  => array(
                array('name' => 'module_code', 'type' => 'text'),
            ),
        ),
    );

    /**
     * LC_Mdl_PG_MULPAY::install()を呼んだ際にdtb_moduleのsub_dataカラムへ登録される値
     * シリアライズされて保存される.
     *
     * master_settings => 初期データなど
     * user_settings => 設定情報など、ユーザの入力によるデータ
     */
    var $installSubData = array(
        // 初期データなどを保持する
        'master_settings' => array(
        ),
        // 設定情報など、ユーザの入力によるデータを保持する
        'user_settings' => array(
        ),
    );

    /**
     * LC_Mdl_PG_MULPAYのインスタンスを取得する
     *
     * @return LC_Mdl_PG_MULPAY
     */
    function &getInstance() {
        static $_objLC_Mdl_GMOPG;
        if (empty($_objLC_Mdl_GMOPG)) {
            $_objLC_Mdl_GMOPG = new LC_Mdl_PG_MULPAY();
        }
        $_objLC_Mdl_GMOPG->init();
        return $_objLC_Mdl_GMOPG;
    }

    /**
     * 初期化処理.
     */
    function init() {
        foreach ($this->moduleInfo as $k => $v) {
            $this->$k = $v;
        }
    }

    /**
     * モジュール表示用名称を取得する
     *
     * @return string
     */
    function getName() {
        return $this->moduleName;
    }

    /**
     * 支払い方法名(決済モジュールの場合のみ)
     *
     * @return string
     */
    function getPaymentName() {
        return $this->paymentName;
    }

    /**
     * モジュールコードを取得する
     *
     * @param boolean $toLower trueの場合は小文字へ変換する.デフォルトはfalse.
     * @return string
     */
    function getCode($toLower = false) {
        $moduleCode = $this->moduleCode;
        return $toLower ? strtolower($moduleCode) : $moduleCode;
    }

    /**
     * モジュールバージョンを取得する
     *
     * @return string
     */
    function getVersion() {
        return $this->moduleVersion;
    }

    /**
     * サブデータを取得する.
     *
     * @return mixed|null
     */
    function getSubData() {
        if (isset($this->subData)) return $this->subData;

        $moduleCode = $this->getCode(true);
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $ret = $objQuery->get(
			'sub_data', 'dtb_module', 'module_code = ?', array($moduleCode)
        );

        if (isset($ret)) {
            $this->subData = unserialize($ret);
            return $this->subData;
        }
        return null;
    }

    /**
     * サブデータをDBへ登録する
     * $keyがnullの時は全データを上書きする
     *
     * @param mixed $data
     * @param string $key
     */
    function registerSubData($data, $key = null) {
        $subData = $this->getSubData();

        if (is_null($key)) {
            $subData = $data;
        } else {
            $subData[$key] = $data;
        }

        $arrUpdate = array('sub_data' => serialize($subData));
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->update('dtb_module', $arrUpdate, 'module_code = ?', array($this->getCode(true)));

        $this->subData = $subData;
    }

    function getUserSettings($key = null) {
        $subData = $this->getSubData();
        $returnData = null;

        if (is_null($key)) {
            $returnData = isset($subData['user_settings'])
                ? $subData['user_settings']
                : null;
        } else {
            $returnData = isset($subData['user_settings'][$key])
                ? $subData['user_settings'][$key]
                : null;
        }

        return $returnData;
    }

    function registerUserSettings($data) {
        $this->registerSubData($data, 'user_settings');
    }

    /**
     * ログを出力.
     *
     * @param string $msg
     * @param mixed $data
     */
    function printDLog($msg, $nest = false) {
        if (!defined('MDL_PG_MULPAY_DEBUG')) {
            return;
        }
        if (!MDL_PG_MULPAY_DEBUG) {
            return;
        }
        $arrBackTrace = debug_backtrace();
        if ($nest) {
            $target = 3;
        } else {
            $target = 1;
        }
        $header = '{' . $arrBackTrace[$target]['file'] . '(' . $arrBackTrace[$target]['line'] .'): ';
        $header .= $arrBackTrace[$target]['class'] . $arrBackTrace[$target]['type'] . $arrBackTrace[$target]['function'] . '(): ';
        $header .= 'u=' . $_SESSION['site']['uniqid'];
        $order_id = '';
        if (isset($_SESSION['order_id'])) {
            $order_id = $_SESSION['order_id'];
        } else if (isset($_SESSION['mdl_pg_mulpay_complete_order_id'])) {
            $order_id = $_SESSION['mdl_pg_mulpay_complete_order_id'];
        }
        $header .= ' o=' . $order_id;
        $objCustomer = new SC_Customer_Ex();
        $userId = $objCustomer->getValue('customer_id');
        if ($userId) { $header .= " c=$userId"; }

        $header .= '} ';

        if (is_array($msg)) {
            $keys = array('SiteID', 'SitePass', 'ShopID', 'ShopPass');
            foreach ($keys as $key) {
                if (isset($msg[$key])) {
                    $msg[$key] = preg_replace("/./", "*", $msg[$key]);
                }
            }
            if (isset($msg['session'])) {
                $r = unserialize($msg['session']);
                if ($r !== FALSE) {
                    $msg['session'] = $r;
                }
            }
            if (isset($msg['memo06'])) {
                $r = unserialize($msg['memo06']);
                if ($r !== FALSE) {
                    $msg['memo06'] = $r;
                }
            }
            $msg = print_r($msg, true);
        }
        $msg = $header . "\n" . $msg . " (r={$_SERVER[HTTP_REFERER]})";

        $path = DATA_REALDIR . 'logs/mdl_pg_mulpay_' . date('Ymd')  . '.log';
        GC_Utils_Ex::gfPrintLog($msg, $path);
    }

    function printLog($msg, $raw = false) {
        $order_id = '';
        if (isset($_SESSION['order_id'])) {
            $order_id = $_SESSION['order_id'];
        } else if (isset($_SESSION['mdl_pg_mulpay_complete_order_id'])) {
            $order_id = $_SESSION['mdl_pg_mulpay_complete_order_id'];
        }
        $prefix = "o=$order_id ";

        LC_Mdl_PG_MULPAY::printLogImpl($msg, $prefix, $raw);
    }

    function printLogU($msg, $uniqid) {
        $prefix = "U=$uniqid ";
        LC_Mdl_PG_MULPAY::printLogImpl($msg, $prefix, $raw);
    }

    function printLogImpl($msg, $prefix = '', $raw = false) {
        LC_Mdl_PG_MULPAY::printDLog($msg, true);
        // パスワード等をマスクする
        if (!$raw && is_array($msg)) {
            $keys = array('SiteID', 'SitePass', 'ShopID', 'ShopPass');
            foreach ($keys as $key) {
                if (isset($msg[$key])) {
                    $msg[$key] = ereg_replace(".", "*", $msg[$key]);
                }
            }

            $msg = print_r($msg, true);
        }

        $objCustomer = new SC_Customer_Ex();
        $userId = $objCustomer->getValue('customer_id');
        if ($userId) { $msg = "u=$userId ".$msg; }

        if ($prefix) { $msg = $prefix.$msg; }

        $path = DATA_REALDIR . 'logs/mdl_pg_mulpay.log';
        GC_Utils_Ex::gfPrintLog($msg, $path);
    }

    /**
     * デバッグログを出力.
     *
     * @param string $msg
     * @param mixed $data
     */
    function printDebugLog($msg, $data = null) {
        if (DEBUG_MODE === true) {
			$this->printLog($msg, $data);
        }
    }

    /**
     * インストール処理
     *
     * @param boolean $force true時、上書き登録を行う
     */
    function install($force = false) {
        // カラムの更新
        $this->updateTable();

        $subData = $this->getSubData();
        if (is_null($subData) || $force) {
            $this->registerSubdata(
                $this->installSubData['master_settings'],
                'master_settings'
            );
        }
    }

    /**
     * カラムの更新を行う.
     *
     */
    function updateTable() {
        $objDB = new SC_Helper_DB_Ex();
        foreach ($this->updateTable as $table) {
            foreach($table['cols'] as $col) {
                $objDB->sfColumnExists(
                    $table['name'], $col['name'], $col['type'], "", $add = true
                );
            }
        }
    }

    /**
     * 3Dセキュアが有効かどうかを判定する
     * 管理画面設定で3Dセキュア認証を有効にしている
     * かつ ブラウザのUserAgentがモバイルでない場合にtrueを返す
     *
     * @return boolean
     */
    function isEnable3DSecure() {
        $is3DSecure = $this->getUserSettings('use3d');
        if ($is3DSecure && !SC_MobileUserAgent::isMobile()) {
            return true;
        }
        return false;
    }

    /**
     * 会員IDが有効かどうかを判定する
     *
     * @return boolean
     */
    function isEnableCustomerRegist() {
        $objCustomer = new SC_Customer();
        $loggedIn = $objCustomer->isLoginSuccess(true);
        $useCustomerRegist = $this->getUserSettings('use_customer_reg');
        if ($loggedIn && $useCustomerRegist) {
            return true;
        }
        return false;
    }
    
    /**
     * セキュリティコードが有効かどうかを判定する
     *
     * @return boolean
     */
    function isEnableSecurityCode() {
        $useSecurityCode = $this->getUserSettings('use_securitycd');
        if ($useSecurityCode) {
            return true;
        }
        return false;
    }

    /**
     * カードステータス連携機能が有効かどうかチェックする。
     *
     * @return boolean
     */
    function isEnableCardStatusChangeFunction() {
        if ($this->getUserSettings('credit_CardStatusChangeFunction')) {
            return true;
        }
        return false;
    }

    /**
     * 2クリック決済機能が有効かどうかチェックする。
     *
     * @return boolean
     */
    function isEnable2click() {
        // 無効なコードは設定されない。
        if ($this->getUserSettings('2click_LicenseKey')) {
            return true;
        }
        return false;
    }

    /**
     * PayPal決済機能が有効かどうかチェックする。
     *
     * @return boolean
     */
    function isEnablePaypal() {
        if ($this->getUserSettings('use_paypal')) {
            return true;
        }
        return false;
    }

    /**
     * iD決済機能が有効かどうかチェックする。
     *
     * @return boolean
     */
    function isEnableNetid() {
        if ($this->getUserSettings('use_netid')) {
            return true;
        }
        return false;
    }

    /**
     * au決済機能が有効かどうかチェックする。
     *
     * @return boolean
     */
    function isEnableAu() {
        if ($this->getUserSettings('use_au')) {
            return true;
        }
        return false;
    }

    /**
     * ドコモケータイ払い機能が有効かどうかチェックする。
     *
     * @return boolean
     */
    function isEnableDocomo() {
        if ($this->getUserSettings('use_docomo')) {
            return true;
        }
        return false;
    }

    /**
     * 入力回数制限機能が有効かどうかチェックする。
     *
     * @return boolean
     */
    function isEnableUseLimit() {
        if ($this->getUserSettings('use_limit')) {
            return true;
        }

        return false;
    }

    /**
     * ファイルをコピーする
     *
     * @return boolean
     */
    function getCustomizeFiles($enableChangeStatus, $enable2Click) {
        // 必須ファイル
        $baseFiles = array(
            // 新規ファイル
            array(
                "src" => "receive.php",
                "dst" => HTML_REALDIR . 'pg_mulpay/receive.php'
            ),
            array(
                "src" => "gmo_id_on.gif.php",
                "dst" => USER_REALDIR . 'gmo_id_on.gif'
            ),
            array(
                "src" => "gmo_id.gif.php",
                "dst" => USER_REALDIR . 'gmo_id.gif'
            ),
            array(
                "src" => "code_visa.gif.php",
                "dst" => USER_REALDIR . 'code_visa.gif'
            ),
            array(
                "src" => "code_amex.gif.php",
                "dst" => USER_REALDIR . 'code_amex.gif'
            ),
            // 上書き
            array(
                "src" => "LC_Page_Shopping_Complete_Ex.php",
                "dst" => DATA_REALDIR . 'class_extends/page_extends/shopping/LC_Page_Shopping_Complete_Ex.php'
            ),
            array(
                "src" => "SC_Helper_Purchase_Ex.php",
                "dst" => DATA_REALDIR . 'class_extends/helper_extends/SC_Helper_Purchase_Ex.php'
            ),
            array(
                "src" => "SC_Utils_Ex.php",
                "dst" => DATA_REALDIR . 'class_extends/util_extends/SC_Utils_Ex.php'
            ),

            // 2.0.0, 2.0.1でカスタマイズしたファイルを復元
            array(
                "src" => "LC_Page_Ex.php",
                "dst" => DATA_REALDIR . 'class_extends/page_extends/LC_Page_Ex.php'
            ),
            array(
                "src" => "LC_Page_Shopping_Confirm_Ex.php",
                "dst" => DATA_REALDIR . 'class_extends/page_extends/shopping/LC_Page_Shopping_Confirm_Ex.php'
            ),
            array(
                "src" => "LC_Page_Shopping_LoadPaymentModule_Ex.php",
                "dst" => DATA_REALDIR . 'class_extends/page_extends/shopping/LC_Page_Shopping_LoadPaymentModule_Ex.php'
            ),
        );

        // 決済状況変更機能
        $statusFiles = array(
             // 新規追加
             array(
                   "src" => "gmopg_docomo_status.php",
                   "dst" => HTML_REALDIR . ADMIN_DIR . "order/gmopg_docomo_status.php"
                   ),
             array(
                   "src" => "gmopg_au_status.php",
                   "dst" => HTML_REALDIR . ADMIN_DIR . "order/gmopg_au_status.php"
                   ),
             array(
                   "src" => "gmopg_netid_status.php",
                   "dst" => HTML_REALDIR . ADMIN_DIR . "order/gmopg_netid_status.php"
                   ),
             array(
                   "src" => "gmopg_paypal_status.php",
                   "dst" => HTML_REALDIR . ADMIN_DIR . "order/gmopg_paypal_status.php"
                   ),
             array(
                   "src" => "gmopg_credit_status.php",
                   "dst" => HTML_REALDIR . ADMIN_DIR . "order/gmopg_credit_status.php"
                   ),
             array(
                   "src" => "gmopg_use_limit_unlock.php",
                   "dst" => HTML_REALDIR . ADMIN_DIR . "order/gmopg_use_limit_unlock.php"
                   ),

             // 上書き
             array(
                   "src" => "LC_Page_Admin_Order_Edit_Ex.php",
                   "dst" => DATA_REALDIR . "class_extends/page_extends/admin/order/LC_Page_Admin_Order_Edit_Ex.php"
                   ),
             array(
                   "src" => "LC_Page_Admin_Ex.php",
                   "dst" => DATA_REALDIR . "class_extends/page_extends/admin/LC_Page_Admin_Ex.php"
                   ),
             array(
                   "src" => "subnavi.tpl",
                   "dst" => DATA_REALDIR . "Smarty/templates/admin/order/subnavi.tpl"
                   ),
        );

        // 2クリック決済
        $twoClickFiles = array(
            // 上書き
            array(
                  "src" => "SC_CartSession_Ex.php",
                  "dst" => DATA_REALDIR . "class_extends/SC_CartSession_Ex.php"
                  ),
            array(
                  "src" => "LC_Page_Cart_Ex.php",
                  "dst" => DATA_REALDIR . "class_extends/page_extends/cart/LC_Page_Cart_Ex.php"
                  ),
            array(
                  "src" => "LC_Page_Mypage_DeliveryAddr_Ex.php",
                  "dst" => DATA_REALDIR . "class_extends/page_extends/mypage/LC_Page_Mypage_DeliveryAddr_Ex.php"
                  ),
            array(
                  "src" => "LC_Page_Shopping_Payment_Ex.php",
                  "dst" => DATA_REALDIR . "class_extends/page_extends/shopping/LC_Page_Shopping_Payment_Ex.php"
                  ),

            // 新規コピー
            array(
                  "src" => "2click/html/twoClick/index.php",
                  "dst" => HTML_REALDIR . "twoClick/index.php"
                  ),
            array(
                  "src" => "2click/html/twoClick/deliv.php",
                  "dst" => HTML_REALDIR . "twoClick/deliv.php"
                  ),
            array(
                  "src" => "2click/html/twoClick/payment.php",
                  "dst" => HTML_REALDIR . "twoClick/payment.php"
                  ),
            array(
                  "src" => "2click/html/twoClick/point.php",
                  "dst" => HTML_REALDIR . "twoClick/point.php"
                  ),
            array(
                  "src" => "2click/html/twoClick/confirm.php",
                  "dst" => HTML_REALDIR . "twoClick/confirm.php"
                  ),
            array(
                  "src" => "2click/html/twoClick/load_payment_module.php",
                  "dst" => HTML_REALDIR . "twoClick/load_payment_module.php"
                  ),
            array(
                  "src" => "2click/html/twoClick/multiple.php",
                  "dst" => HTML_REALDIR . "twoClick/multiple.php"
                  ),

            array(
                  "src" => "2click/data/Smarty/templates/default/twoClick/cart_index.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/default/twoClick/cart_index.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/default/twoClick/confirm.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/default/twoClick/confirm.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/default/twoClick/deliv.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/default/twoClick/deliv.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/default/twoClick/multiple.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/default/twoClick/multiple.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/default/twoClick/payment.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/default/twoClick/payment.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/default/twoClick/point.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/default/twoClick/point.tpl"
                  ),

            array(
                  "src" => "2click/data/Smarty/templates/sphone/twoClick/cart_index.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/sphone/twoClick/cart_index.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/sphone/twoClick/confirm.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/sphone/twoClick/confirm.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/sphone/twoClick/deliv.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/sphone/twoClick/deliv.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/sphone/twoClick/multiple.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/sphone/twoClick/multiple.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/sphone/twoClick/payment.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/sphone/twoClick/payment.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/sphone/twoClick/point.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/sphone/twoClick/point.tpl"
                  ),

            array(
                  "src" => "2click/data/Smarty/templates/mobile/twoClick/cart_index.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/mobile/twoClick/cart_index.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/mobile/twoClick/confirm.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/mobile/twoClick/confirm.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/mobile/twoClick/deliv.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/mobile/twoClick/deliv.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/mobile/twoClick/multiple.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/mobile/twoClick/multiple.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/mobile/twoClick/payment.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/mobile/twoClick/payment.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/mobile/twoClick/point.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/mobile/twoClick/point.tpl"
                  ),
            array(
                  "src" => "2click/data/Smarty/templates/mobile/twoClick/select_deliv.tpl",
                  "dst" => DATA_REALDIR . "Smarty/templates/mobile/twoClick/select_deliv.tpl"
                  ),

            // 画像ファイル
            array(
                  "src" => "btn_determine.jpg",
                  "dst" => HTML_REALDIR . "user_data/packages/default/img/button/btn_determine.jpg"
                  ),
            array(
                  "src" => "btn_determine_on.jpg",
                  "dst" => HTML_REALDIR . "user_data/packages/default/img/button/btn_determine_on.jpg"
                  ),
            array(
                  "src" => "btn_2click.jpg",
                  "dst" => HTML_REALDIR . "user_data/packages/default/img/button/btn_2click.jpg"
                  ),
            array(
                  "src" => "btn_2click_on.jpg",
                  "dst" => HTML_REALDIR . "user_data/packages/default/img/button/btn_2click_on.jpg"
                  ),
        );

        $files = $baseFiles;
        if ($enableChangeStatus) {
            $files = array_merge($files, $statusFiles);
        }
        if ($enable2Click) {
            $files = array_merge($files, $twoClickFiles);
        }
        return $files;
    }

    // 再帰的にパスを作成する。(php4にはrecursiveがない)
    // http://www.php.net/manual/ja/function.mkdir.php
    function mkdirp($pathname, $mode) {
        is_dir(dirname($pathname)) || $this->mkdirp(dirname($pathname), $mode);
        return is_dir($pathname) || mkdir($pathname, $mode);
    }

    function copyFiles($files) {
        $failedCopyFile = array();

        foreach($files as $file) {
            $dst_file = $file['dst'];
            $src_file = MDL_PG_MULPAY_PATH . 'copy/' . $file['src'];
            // ECCUBE_VERSIONにマッチするファイルがあれば、そちらを利用する。
            $src_file_specific = $this->getVerSpecific($src_file);
            $src_file = file_exists($src_file_specific) ? $src_file_specific : $src_file;
            // ファイルがない、またはファイルはあるが異なる場合
            if(!file_exists($dst_file) || sha1_file($src_file) != sha1_file($dst_file)) {
                if(is_writable($dst_file) || is_writable(dirname($dst_file)) || $this->mkdirp(dirname($dst_file), 0777)) {
                    if (copy($src_file, $dst_file)) {
                        $this->printLog("copy: $src_file -> $dst_file");
                    } else {
                        $failedCopyFile[] = $dst_file;
                    }
                } else {
                    $failedCopyFile[] = $dst_file;
                }
            }
        }

        return $failedCopyFile;
    }

    /**
     * 処理区分を取得する
     *
     * @return string
     */
    function getJobCd($key = 'jobcd') {
        $arrJobCd = $GLOBALS['arrJobCd'];

        $jobCdIndex = $this->getUserSettings($key);
        $jobCd = isset($arrJobCd[$jobCdIndex])
            ? $arrJobCd[$jobCdIndex]
            : 'AUTH';

        return $jobCd;
    }

    function formatISO8601($date) {
        $n = sscanf($date, '%4s%2s%2s%2s%2s%2s', $year, $month, $day, $hour, $min, $sec);
        return sprintf('%s-%s-%s %s:%s:%s', $year, $month, $day, $hour, $min, $sec);
    }

    function backupFiles($arrFiles, &$success, &$backup_dir) {
        $failedFiles = array();
        $bdir = MODULE_REALDIR.'mdl_pg_mulpay_backup/'.date('Y-m-d_His');
        if (LC_Mdl_PG_MULPAY::mkdirp($bdir, 0777)) {
            $backup_dir = $bdir;
        } else {
            $success = false;
            $backup_dir = '';
            return $failedFiles;
        }

        foreach ($arrFiles as $arrFile) {
            $src_path = $arrFile['dst'];

            // DATA_REALDIRの'html/../data/'に注意
            $tmp_path = preg_replace('/^'.preg_quote(DATA_REALDIR,'/').'/', 'data/', $src_path);
            if ($tmp_path == $src_path) {
                $tmp_path = preg_replace('/^'.preg_quote(HTML_REALDIR,'/').'/', 'html/', $src_path);
            }
            $dst_path = $bdir.'/'.$tmp_path;

            if (!file_exists($src_path)) {
                ; // 新規ファイルではバックアップ対象が存在しない
            } else if (is_writable($dst_path)
                       || is_writable(dirname($dst_path)) || $this->mkdirp(dirname($dst_path), 0777))
            {
                if (copy($src_path, $dst_path)) {
                    LC_Mdl_PG_MULPAY::printLog("バックアップ作成成功: $src_path -> $dst_path");
                } else {
                    $success = false;
                    $failedFiles[] = $src_path;
                    LC_Mdl_PG_MULPAY::printLog("バックアップ作成失敗: $src_path -> $dst_path");
                }
            } else {
                $success = false;
                $failedFiles[] = $src_path;
                LC_Mdl_PG_MULPAY::printLog("バックアップ作成失敗: $src_path -> $dst_path");
            }
        }

        if (count($failedFiles) == 0) {
            $success = true;
        }
        return $failedFiles;
    }

    function getVerSpecific($src) {
        $path_parts = pathinfo($src);
        $ver_specific_src = $path_parts['dirname'] . '/' . basename($src, '.'.$path_parts['extension']) . 
            '_' . ECCUBE_VERSION . '.' . $path_parts['extension'];
        return $ver_specific_src;
    }
}
?>
