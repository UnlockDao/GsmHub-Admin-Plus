<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
require_once "PaypalNVPConstants.php";
class PaypalNVP extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->api_username = 'tungkick777_api1.yahoo.com';
        $this->api_signature = 'AgX0Je7wr6408gPQ2DQxyTbLKqQZAv5-1tTusGrkHaS.4K6GTVre1X7C';
        $this->api_endpoint = "https://api-3t.paypal.com/nvp";
        $this->api_password = 'W7FUNPVDGFVWRXWD';
        $this->subject = SUBJECT;
        $this->proxy_host = PROXY_HOST;
        $this->proxy_port = PROXY_PORT;
        $this->version = VERSION;
    }
    public function fetchPaypalNVPTransactionDetails()
    {
        $transactionID = urlencode('3749134269253160G');
        $nvpStr = "&TRANSACTIONID=" . $transactionID;
        $resArray = $this->hash_call("GetTransactionDetails", $nvpStr);
        return $resArray;
    }
    public function fetchPaypalNVPTransactionSearchResults($startDate, $receiver)
    {
        $nvpStr = "";
        if (isset($startDate) && $startDate != "") {
            $nvpStr = "&STARTDATE=" . $startDate;
        }
        if (isset($endDate) && $endDate != "") {
            $end_time = strtotime($endDate);
            $iso_end = date("Y-m-d\\TH:i:s\\Z", $end_time);
            $nvpStr .= "&ENDDATE=" . $iso_end;
        }
        if (isset($receiver) && $receiver != "") {
            $nvpStr .= "&RECEIVER=" . $receiver;
        }
        $resArray = $this->hash_call("TransactionSearch", $nvpStr);
        return $resArray;
    }
    public function fetchPaypalNVPReversedTransactions($startDate = "", $type = "REFUND")
    {
        $nvpStr = "&TRANSACTIONCLASS=" . $type . "&STARTDATE=" . $startDate;
        $resArray = $this->hash_call("TransactionSearch", $nvpStr);
        return $resArray;
    }
    public function nvpHeader()
    {
        global $API_Endpoint;
        global $version;
        global $API_UserName;
        global $API_Password;
        global $API_Signature;
        global $nvp_Header;
        global $subject;
        global $AUTH_token;
        global $AUTH_signature;
        global $AUTH_timestamp;
        $nvpHeaderStr = "";
        $API_Password = $this->api_password;
        $subject = $this->subject;
        if (defined("AUTH_MODE")) {
            $AuthMode = "AUTH_MODE";
        } else {
            if (!empty($this->api_username) && !empty($API_Password) && !empty($this->api_signature) && !empty($subject)) {
                $AuthMode = "THIRDPARTY";
            } else {
                if (!empty($this->api_username) && !empty($API_Password) && !empty($this->api_signature)) {
                    $AuthMode = "3TOKEN";
                } else {
                    if (!empty($AUTH_token) && !empty($this->api_signature) && !empty($AUTH_timestamp)) {
                        $AuthMode = "PERMISSION";
                    } else {
                        if (!empty($subject)) {
                            $AuthMode = "FIRSTPARTY";
                        } else {
                            $AuthMode = "";
                        }
                    }
                }
            }
        }
        switch ($AuthMode) {
            case "3TOKEN":
                $nvpHeaderStr = "&PWD=" . urlencode($API_Password) . "&USER=" . urlencode($this->api_username) . "&SIGNATURE=" . urlencode($this->api_signature);
                break;
            case "FIRSTPARTY":
                $nvpHeaderStr = "&SUBJECT=" . urlencode($subject);
                break;
            case "THIRDPARTY":
                $nvpHeaderStr = "&PWD=" . urlencode($API_Password) . "&USER=" . urlencode($this->api_username) . "&SIGNATURE=" . urlencode($this->api_signature) . "&SUBJECT=" . urlencode($subject);
                break;
            case "PERMISSION":
                $nvpHeaderStr = formAutorization($AUTH_token, $AUTH_signature, $AUTH_timestamp);
                break;
            default:
                $nvpHeaderStr = "";
                break;
        }
        return $nvpHeaderStr;
    }
    public function deformatNVP($nvpstr)
    {
        $intial = 0;
        $nvpArray = array();
        while (strlen($nvpstr)) {
            $keypos = strpos($nvpstr, "=");
            $valuepos = strpos($nvpstr, "&") ? strpos($nvpstr, "&") : strlen($nvpstr);
            $keyval = substr($nvpstr, $intial, $keypos);
            $valval = substr($nvpstr, $keypos + 1, $valuepos - $keypos - 1);
            $nvpArray[urldecode($keyval)] = urldecode($valval);
            $nvpstr = substr($nvpstr, $valuepos + 1, strlen($nvpstr));
        }
        return $nvpArray;
    }
    public function hash_call($methodName, $nvpStr)
    {
        global $API_Endpoint;
        global $version;
        global $API_UserName;
        global $API_Password;
        global $API_Signature;
        global $nvp_Header;
        global $subject;
        global $AUTH_token;
        global $AUTH_signature;
        global $AUTH_timestamp;
        $API_Password = $this->api_password;
        $subject = $this->subject;
        $version = $this->version;
        $nvpheader = $this->nvpHeader();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        if (!empty($AUTH_token) && !empty($AUTH_signature) && !empty($AUTH_timestamp)) {
            $headers_array[] = "X-PP-AUTHORIZATION: " . $nvpheader;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_array);
            curl_setopt($ch, CURLOPT_HEADER, false);
        } else {
            $nvpStr = $nvpheader . $nvpStr;
        }
        if (USE_PROXY) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxy_host . ":" . $this->proxy_port);
        }
        if (strlen(str_replace("VERSION=", "", strtoupper($nvpStr))) == strlen($nvpStr)) {
            $nvpStr = "&VERSION=" . urlencode($version) . $nvpStr;
        }
        $nvpreq = "METHOD=" . urlencode($methodName) . $nvpStr;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        $response = curl_exec($ch);
        $nvpResArray = $this->deformatNVP($response);
        $nvpReqArray = $this->deformatNVP($nvpreq);
        $_SESSION["nvpReqArray"] = $nvpReqArray;
        if (curl_errno($ch)) {
            $_SESSION["curl_error_no"] = curl_errno($ch);
            $_SESSION["curl_error_msg"] = curl_error($ch);
            $location = "APIError.php";
            header("Location: " . $location);
        } else {
            curl_close($ch);
        }
        return $nvpResArray;
    }
}

?>