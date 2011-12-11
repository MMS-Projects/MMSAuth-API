<?php
class MMSAuth_API {
    const APP_CODE = '---';
    const APP_PASS = '---';
    const CURL_USERAGENT = 'MMSAuth_API_Client/0.1';
    static private $_cURLSession = null;
    static public $requestInfo = array();
    static public $totalRequestTime = 0;
    static public $totalRequests = 0;
    static public function callAPI($uRI) {
        $uRL = sprintf('http://auth.mms-projects.net/api/%s=%s/%s', self::APP_CODE, self::APP_PASS,
            $uRI);
        return unserialize(self::_doRequest($uRL));
    }
    static private function _doRequest($uRL) {
        if (!self::$_cURLSession) {
            self::$_cURLSession = curl_init();
            $requestHeaders = array('Accept: application/vnd.mmsauth.phps');
            curl_setopt(self::$_cURLSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(self::$_cURLSession, CURLOPT_USERAGENT, self::CURL_USERAGENT);
            curl_setopt(self::$_cURLSession, CURLOPT_HTTPHEADER, $requestHeaders);
        }
        curl_setopt(self::$_cURLSession, CURLOPT_URL, $uRL);
        $returnData = curl_exec(self::$_cURLSession);
        self::_saveRequestInfo();
        return $returnData;
    }
    static private function _saveRequestInfo() {
        self::$requestInfo[] = curl_getinfo(self::$_cURLSession);
        self::$totalRequestTime += curl_getinfo(self::$_cURLSession, CURLINFO_TOTAL_TIME);
        ++self::$totalRequests;
    }
}
?>
