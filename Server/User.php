<?php

namespace Server;

use Server\Core\EnumConst;
use Server\Core\QueryBuilder;
use Server\Core\Server;

if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

/**
 * User
 */
class User
{
    protected $qb;
    protected $server;

    function __construct(QueryBuilder $qb, $check = null, $param = null)
    {
        $this->qb = $qb;
        $this->server = new Server($qb);
    }

    public function sendMail($token, $email) {

        $to      = $email;
        $subject = 'Appi';
        $urlReg = 'http://' . $_SERVER['HTTP_HOST'] . '/final/register/token' . $token;
        $message = '
			<table width="100%" cellpadding="0" cellspacing="0" border="0" dir="ltr" data-width="700" style="font-size: 16px; background-color: rgb(255, 255, 255);">
				<thead>
					<tr>
					    <td align="left">
					    	<table cellspacing="0" cellpadding="0" align="left" border="0" class="wrapper" width="700" style="width: 700px; margin: 0px;">
					    		<tbody>
					    			<tr>
					    				<td>
					    					<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" data-editable="preheader" data-webinar="0">
					    						<tbody>
					    							<tr>
					    								<td align="center" valign="top" style="padding: 8px; font-family: Helvetica, Arial, sans-serif; color: rgb(38, 38, 38);">
					    									<div style="text-align: left;">
					    										<font size="30" style="font-size: 30px; color: rgb(70, 70, 70); font-weight: bold;">Благодарим за регистрацию ByAppi.com</font>
					    									</div>
					    								</td>
					    							</tr>
					    						</tbody>
					    					</table>
					    				</td>
					    			</tr>
					    		</tbody>
					    	</table>
					    </td>
					</tr>
				</thead>
				<tbody>
				<tr>
				    <td align="left" valign="top" style="margin:0;padding:0;">
				        <table align="left" border="0" cellspacing="0" cellpadding="0" width="700" bgcolor="#ffffff" class="wrapper" style="width: 700px; margin: 0px;">
				            <tbody>
				            	<tr>
				            		<td>
				            			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" data-editable="text">
				            				<tbody>
				            					<tr>
				            						<td align="left" valign="top" style="padding: 10px; font-family: Arial, Helvetica, sans-serif; color: rgb(38, 38, 38);">
				            							Вы успешно зарегестрировались<br><br>
			                							Для завершения регистрации пройдите по ссылке:<span style="font-family:Arial,Helvetica,sans-serif;color:#262626;font-size:14px"></span>
			                							<div style=""><a href="' . $urlReg . '" target="_blank" title="Подтвердите регистрацию" style="color: rgb(0, 185, 232); text-decoration: none;">' . $urlReg . '</a></div>
			                							<br><br>
			                							Если вы не выполняли данной операцией, то просто проигнорируйте это сообщения. Приносим вам свои извинения. 
			                						</td>
			                					</tr>
			                				</tbody>
			                			</table>
			                		</td>
			                	</tr>
			                </tbody>
			            </table>
				    </td>
				</tr>
				</tbody>
			</table>
		';
        $headers = 'Content-type: text/html; charset=UTF-8'."\r\n".
            'From: admin' . "\r\n";
        mail($to, $subject, $message, $headers);

        return false;
    }

    public function auth($login, $password, $api = false) {

        global $server;

        $resultUser = $this->getUserInfo($login);

        if ($resultUser) {
            if(hash('sha256', $password) == $resultUser['password']) {

                $timeNow = $server->timeStampNow();
                $ip = $server->getClientIp();

                $token = 112233445566;//$this->generateToken(); TODO fix
                $token = md5($token . $resultUser['login'] . $resultUser['id']);

                $this->qb
                    ->createQueryBuilder(EnumConst::USERS)
                    ->updatesql(array('token', 'last_ip', 'last_time'), array($token, $ip, $timeNow))
                    ->where('id = \'' . $resultUser['id'] . '\'')
                    ->executeQuery()
                    ->getResult()
                ;

                if($api)
                    return $token;

                setcookie("user", $token, time() + 31556926, "/", $_SERVER['HTTP_HOST'] . "");
                header('location: /admin');
                return true;
            }
        }
        return false;
    }

    /**
     * @param bool $referrer
     */
    public function logout($referrer = false) {
        setcookie("user", '', time() - 3600, "/", $_SERVER['HTTP_HOST'] . "");
        setcookie("userId", '', time() - 3600, "/", $_SERVER['HTTP_HOST'] . "");

        $http = '/';

        if($referrer)
            if (isset($_SERVER['HTTP_REFERER'])) $http = $_SERVER['HTTP_REFERER'];

        if(!$this->isAuthUser())
            $http = '/';

        header( "refresh:0; url=" . $http );
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getUserCookie($name) {
        return $_COOKIE[$name];
    }

    /**
     * @param $where
     * @return bool
     */
    public function getUserInfo($where) {

        global $server;
        $where = $server->charsString($where);

        return $this->qb
            ->createQueryBuilder(EnumConst::USERS)
            ->selectSql()
            ->where('login = \'' . $where . '\'')
            ->orWhere('id = \'' . $where . '\'')
            ->orWhere('token = \'' . $where . '\'')
            ->executeQuery()
            ->getSingleResult()
        ;
    }

    /**
     * @param $where
     * @return bool
     */
    public function getUserInfoByToken($where) {

        global $server;
        $where = $server->charsString($where);

        return $this->qb
            ->createQueryBuilder(EnumConst::USERS)
            ->selectSql()
            ->where('token = \'' . $where . '\'')
            ->executeQuery()
            ->getSingleResult()
        ;
    }

    /**
     * @param null $email
     * @return bool
     */
    public function checkEmail($email = null) {
        if (!$email) return false;
        $result = $this->qb->createQueryBuilder(EnumConst::USERS)->selectSql()->where('email = \'' . $email . '\'')->executeQuery()->getSingleResult();
        return empty($result);
    }

    /**
     * @param null $login
     * @return bool
     */
    public function checkLogin($login = null) {
        if (!$login) return false;
        $result = $this->qb->createQueryBuilder(EnumConst::USERS)->selectSql()->where('login = \'' . $login . '\'')->executeQuery()->getSingleResult();
        return empty($result);
    }

    /**
     * @return bool
     */
    public function isAuthUser() {
        if (isset($_COOKIE['user']) && !empty($_COOKIE['user'])) {
            global $userInfo;
            if($_COOKIE['user'] == $userInfo['token'])
                return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function generateToken() {
        return md5($this->server->timeStampNow . rand(0, PHP_INT_MAX));
    }
}