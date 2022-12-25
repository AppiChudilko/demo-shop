<?php

namespace Server\Core;

if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

/**
 * Server
 */
class Server
{

    public $timeStampNow;
    public $timeStampUTCNow;
    public $dateTimeNow;
    public $dateNow;
    public $timeNow;

    protected $config;

    /**
     * Server constructor.
     */
    function __construct()
    {
        $this->timeStampNow = time();
        $this->timeStampUTCNow = $this->timeStampNow + (3600 * $this->getClientUTC());
        $this->dateTimeNow = gmdate('Y-m-d H:i:s', $this->timeStampNow);
        $this->dateNow = gmdate('Y-m-d', $this->timeStampNow);
        $this->timeNow = gmdate('H:i:s', $this->timeStampNow);
        //$this->requestLog();

        $this->config = new Config;
        $this->config = $this->config->getAppiAllConfig()->getObjectResult();
    }

    /**
     * @param $url
     * @return string
     */
    public function getUrlPath($url) {
        $url = parse_url($url);
        return str_replace('/', '', $url['path']);
    }

    /**
     * Mehtod. Set UTC user;
     * @param $utc
     * @return bool
     */
    public function setClientUTC($utc) {
        setcookie("UTC", $utc, 0x6FFFFFFF, "/", $_SERVER['HTTP_HOST'] . "");
        return true;
    }

    /**
     * Mehtod. Get UTC user;
     */
    public function getClientUTC() {
        if(isset($_COOKIE['UTC']))
            return $_COOKIE['UTC'];
        return 0;
    }

    /**
     * Mehtod. Get time stamp;
     */
    public function timeStampNow() {
        return $this->timeStampNow;
    }

    /**
     * Mehtod. Get time stamp;
     */
    public function timeStampUTCNow() {
        return $this->timeStampUTCNow;
    }

    /**
     * Mehtod. Get date time;
     */
    public function dateTimeNow() {
        return $this->dateTimeNow;
    }

    /**
     * Mehtod. Get date;
     */
    public function dateNow() {
        return $this->dateNow;
    }

    /**
     * Mehtod. Get time;
     */
    public function timeNow() {
        return $this->timeNow;
    }

    /**
     * Mehtod. Get version framework;
     */
    public function getVersionFW() {
        return EnumConst::VERSION;
    }

    /**
     * Mehtod. Get console log;
     */
    public function consoleLog($text) {
        echo '<script type="text/javascript">console.log("' . $text . '")</script>';
    }

    /**
     * Mehtod. Replace quotes;
     */
    public function replaceQuotes($text) {
        //$lit = ["'"];
        //$sp = ['"'];
        //return str_replace($lit, $sp, $text);
    }

    /**
     * Mehtod. Get referrer;
     */
    public  function getReferrer() {
        if (isset($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        }
        return false;
    }

    /**
     * Mehtod. Get client ip;
     */
    public function getClientIp() {
        if(isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        else {
            return "localhost";
        }
    }

    /**
     * Mehtod. Get Server URL;
     */
    public function getServerURL() {
        $url = "http://";
        $url .= $_SERVER["SERVER_NAME"]; // $_SERVER["HTTP_HOST"] is equivalent
        if ($_SERVER["SERVER_PORT"] != "80") $url .= ":".$_SERVER["SERVER_PORT"];
        return $url;
    }

    /**
     * Mehtod. Get full URL;
     */
    public function getCompleteURL() {
        return $this->getServerURL() . $_SERVER["REQUEST_URI"];
    }

    /**
     * Mehtod. HtmlSpecialChars, StrIpSlashes, AddcSlashes;
     */
    public function charsString($string, $isHtmlSpecialChars = true) {
        if($isHtmlSpecialChars)
            return addcslashes(htmlspecialchars(stripslashes($string)), '\'"\\');
        else
            return addcslashes(stripslashes($string), '\'"\\');
    }

    /**
     * @param $string
     * @return mixed
     */
    public function deleteAllSymbolsAndNumbers($string) {
        return preg_replace('/[^a-zA-Zа-яА-Я]/uix','',$string);
    }

    /**
     * @param $string
     * @return mixed
     */
    public function deleteAllSymbols($string) {
        return preg_replace('![^\w]*!uix','',$string);
    }

    /**
     * @param $string
     * @return mixed
     */
    public function deleteAllNumbers($string) {
        return preg_replace('/[\d]/', '', $string);
    }

    /**
     * @param $timeStamp
     * @return string
     */
    public function timeStampToDate($timeStamp) {
        return gmdate('m', $timeStamp) . '/' . gmdate('d', $timeStamp) . '/' . gmdate('Y', $timeStamp);
    }

    /**
     * @param $timeStamp
     * @return string
     */
    public function timeStampToTime($timeStamp) {
        return gmdate('H', $timeStamp) . ':' . gmdate('i', $timeStamp);
    }

    /**
     * @param $text
     * @return string
     */
    public function toTranslit($text) {
        //$rus = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'];
        //$lat = ['A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya'];
        //return str_replace($rus, $lat, $text);
    }

    /**
     * @param $text
     * @return string
     */
    public function toLink($text) {
        $text = $this->toTranslit($text);
        $text = str_replace(' ', '-', $text);
        $text = preg_replace ("/[^a-zA-Z0-9-\s]/",'',$text);
        return strtolower($text);
    }

    /**
     * Mehtod. Get server info;
     */
    public function serverInfo(){
        if (!@phpinfo()) echo 'No Php Info...';
        echo "<br><br>";
        $a=ini_get_all();
        $output="<table border=1 cellspacing=0 cellpadding=4 align=center>";
        $output.="<tr><th colspan=2>ini_get_all()</td></tr>";

        while(list($key, $value)=each($a)) {
            list($k, $v)= each($a[$key]);
            $output.="<tr><td align=right>$key</td><td>$v</td></tr>";
        }

        $output.="</table>";
        echo $output;
        echo "<br><br>";
        $output="<table border=1 cellspacing=0 cellpadding=4 align=center>";
        $output.="<tr><th colspan=2>\$_SERVER</td></tr>";

        foreach ($_SERVER as $k=>$v) {
            $output.="<tr><td align=right>$k</td><td>$v</td></tr>";
        }

        $output.="</table>";
        echo $output;
        echo "<br><br>";
        echo "<table border=1 cellspacing=0 cellpadding=4 align=center>";
        $safe_mode=trim(ini_get("safe_mode"));

        if ((strlen($safe_mode)==0)||($safe_mode==0)) $safe_mode=false;
        else $safe_mode=true;

        $is_windows_server = (substr(PHP_OS, 0, 3) === 'WIN');
        echo "<tr><td colspan=2>".php_uname();
        echo "<tr><td>safe_mode<td>".($safe_mode?"on":"off");

        if ($is_windows_server) echo "<tr><td>sisop<td>Windows<br>";
        else echo "<tr><td>sisop<td>Linux<br>";

        echo "</table><br><br><table border=1 cellspacing=0 cellpadding=4 align=center>";
        $display_errors=ini_get("display_errors");
        $ignore_user_abort = ignore_user_abort();
        $max_execution_time = ini_get("max_execution_time");
        $upload_max_filesize = ini_get("upload_max_filesize");
        $memory_limit=ini_get("memory_limit");
        $output_buffering=ini_get("output_buffering");
        $default_socket_timeout=ini_get("default_socket_timeout");
        $allow_url_fopen = ini_get("allow_url_fopen");
        $magic_quotes_gpc = ini_get("magic_quotes_gpc");
        ignore_user_abort(true);
        ini_set("display_errors",0);
        ini_set("max_execution_time",0);
        ini_set("upload_max_filesize","10M");
        ini_set("memory_limit","20M");
        ini_set("output_buffering",0);
        ini_set("default_socket_timeout",30);
        ini_set("allow_url_fopen",1);
        ini_set("magic_quotes_gpc",0);
        echo "<tr><td> <td>Get<td>Set<td>Get";
        echo "<tr><td>display_errors<td>$display_errors<td>0<td>".ini_get("display_errors");
        echo "<tr><td>ignore_user_abort<td>".($ignore_user_abort?"on":"off")."<td>on<td>".(ignore_user_abort()?"on":"off");
        echo "<tr><td>max_execution_time<td>$max_execution_time<td>0<td>".ini_get("max_execution_time");
        echo "<tr><td>upload_max_filesize<td>$upload_max_filesize<td>10M<td>".ini_get("upload_max_filesize");
        echo "<tr><td>memory_limit<td>$memory_limit<td>20M<td>".ini_get("memory_limit");
        echo "<tr><td>output_buffering<td>$output_buffering<td>0<td>".ini_get("output_buffering");
        echo "<tr><td>default_socket_timeout<td>$default_socket_timeout<td>30<td>".ini_get("default_socket_timeout");
        echo "<tr><td>allow_url_fopen<td>$allow_url_fopen<td>1<td>".ini_get("allow_url_fopen");
        echo "<tr><td>magic_quotes_gpc<td>$magic_quotes_gpc<td>0<td>".ini_get("magic_quotes_gpc");
        echo "</table><br><br>";
        echo "
	    <script language=\"Javascript\" type=\"text/javascript\">
	    <!--
	        window.moveTo((window.screen.width-800)/2,((window.screen.height-600)/2)-20);
	        window.focus();
	    //-->
	    </script>";
        echo "</body>\n</html>";
    }

    public function requestLog() {
        if(!empty($_REQUEST)) {
            $this->log("[".$this->getCompleteURL()."] ".json_encode($_REQUEST)."\n", "logs/request.log");
        }
    }

    public function log($msg, $dir) {
        if (!file_exists('logs')) {
            mkdir('logs', 0777, true);
        }
        //if ($this->config->isLog) {
        error_log("[".$this->dateTimeNow."] [".$this->getClientIp()."] ".$msg . "\n", 3, $dir);
        //}*/
    }

    public function error($msg, $errorCode = null) {
        $this->log($msg.". Error Code:".$errorCode.";\n", "logs/errors.log");
        return sprintf('Error: '.$msg, $errorCode);
    }
}