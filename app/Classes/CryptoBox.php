<?php

namespace App\Classes;
use Config;
use App\Traits\CryptoFunction;

class CryptoBox {

    use CryptoFunction;
	// Custom Variables

	private $public_key 	= "";		// value from your gourl.io member page - https://gourl.io/info/memberarea
	private $private_key 	= "";		// value from your gourl.io member page.  Also you setup cryptocoin name on gourl.io member page
	private $webdev_key 	= "";		// optional, web developer affiliate key
	private $amount 		= 0;		// amount of cryptocoins which will be used in the payment box/captcha, precision is 4 (number of digits after the decimal), example: 0.0001, 2.444, 100, 2455, etc.
										/* we will use this $amount value of cryptocoins in the payment box with a small fraction after the decimal point to uniquely identify each of your users individually
										 * (for example, if you enter 0.5 BTC, one of your user will see 0.500011 BTC, and another will see  0.500046 BTC, etc) */
	private $amountUSD 		= 0;		/* you can specify your price in USD and cryptobox will automatically convert that USD amount to cryptocoin amount using today live cryptocurrency exchange rates.
										 * Using that functionality (price in USD), you don't need to worry if cryptocurrency prices go down or up.
										 * User will pay you all times the actual price which is linked on current exchange price in USD on the datetime of purchase.
										 * You can use in cryptobox options one variable only: amount or amountUSD. You cannot place values of those two variables together. */
	private $period 		= "";		// period after which the payment becomes obsolete and new cryptobox will be shown; allow values: NOEXPIRY, 1 MINUTE..90 MINUTE, 1 HOUR..90 HOURS, 1 DAY..90 DAYS, 1 WEEK..90 WEEKS, 1 MONTH..90 MONTHS
	private $language		= "en";		// cryptobox localisation; en - English, es - Spanish, fr - French, de - German, nl - Dutch, it - Italian, ru - Russian, pl - Polish, pt - Portuguese, fa - Persian, ko - Korean, ja - Japanese, id - Indonesian, tr - Turkish, ar - Arabic, cn - Simplified Chinese, zh - Traditional Chinese, hi - Hindi
	private $iframeID		= "";		// optional, html iframe element id; allow symbols: a..Z0..9_-
	private $orderID 		= "";		// your page name / product name or order name (not unique); allow symbols: a..Z0..9_-@.; max size: 50 symbols
	private $userID 		= "";		// optional, manual setup unique identifier for each of your users; allow symbols: a..Z0..9_-@.; max size: 50 symbols
										/* IMPORTANT - If you use Payment Box/Captcha for registered users on your website, you need to set userID manually with
										 * an unique value for each of your registered user. It is better than to use cookies by default. Examples: 'user1', 'user2', '3vIh9MjEis' */
	private $userFormat 	= "COOKIE"; // this variable use only if $userID above is empty - it will save random userID in cookies, sessions or use user IP address as userID. Available values: COOKIE, SESSION, IPADDRESS

	/* PLEASE NOTE -
	 * If you use multiple stores/sites online, please create separate GoUrl Payment Box (with unique payment box public/private keys) for each of your stores/websites.
	 * Do not use the same GoUrl Payment Box with the same public/private keys on your different websites/stores.
	 * if you use the same $public_key, $orderID and $userID in your multiple cryptocoin payment boxes on different website pages and a user has made payment; a successful result for that user will be returned on all those pages (if $period time valid).
	 * if you change - $public_key or $orderID or $userID - new cryptocoin payment box will be shown for exisiting paid user. (function $this->is_paid() starts to return 'false').
	 * */


	// Internal Variables

	private $boxID			= 0; 		// cryptobox id, the same as on gourl.io member page. For each your cryptocoin payment boxes you will have unique public / private keys
	private $coinLabel		= ""; 		// current cryptocoin label (BTC, DOGE, etc.)
	private $coinName		= ""; 		// current cryptocoin name (Bitcoin, Dogecoin, etc.)
	private $paid			= false;	// paid or not
	private $confirmed		= false;	// transaction/payment have 6+ confirmations or not
	private $paymentID		= false;	// current record id in the table crypto_payments (table stores all payments from your users)
	private $paymentDate	= "";		// transaction/payment datetime in GMT format
	private $amountPaid 	= 0;		// exact paid amount; for example, $amount = 0.5 BTC and user paid - $amountPaid = 0.50002 BTC
	private $amountPaidUSD 	= 0;		// approximate paid amount in USD; using cryptocurrency exchange rate on datetime of payment
	private $boxType		= "";		// cryptobox type - 'paymentbox' or 'captchabox'
	private $processed		= false;	// optional - set flag to paid & processed
	private $cookieName 	= "";		// user cookie/session name (if cookies/sessions use)
	private $localisation 	= "";		// localisation; en - English, es - Spanish, fr - French, de - German, nl - Dutch, it - Italian, ru - Russian, pl - Polish, pt - Portuguese, fa - Persian, ko - Korean, ja - Japanese, id - Indonesian, tr - Turkish, ar - Arabic, cn - Simplified Chinese, zh - Traditional Chinese, hi - Hindi
	private $ver 		    = "";		// version


	public function __construct($options = array())
	{

		// Min requirements
		if (!function_exists( 'mb_stripos' ) || !function_exists( 'mb_strripos' ))  die(sprintf("Error. Please enable <a target='_blank' href='%s'>MBSTRING extension</a> in PHP. <a target='_blank' href='%s'>Read here &#187;</a>", "http://php.net/manual/en/book.mbstring.php", "http://www.knowledgebase-script.com/kb/article/how-to-enable-mbstring-in-php-46.html"));
		if (!function_exists( 'curl_init' )) 										die(sprintf("Error. Please enable <a target='_blank' href='%s'>CURL extension</a> in PHP. <a target='_blank' href='%s'>Read here &#187;</a>", "http://php.net/manual/en/book.curl.php", "http://stackoverflow.com/questions/1347146/how-to-enable-curl-in-php-xampp"));
		if (!function_exists( 'mysqli_connect' )) 									die(sprintf("Error. Please enable <a target='_blank' href='%s'>MySQLi extension</a> in PHP. <a target='_blank' href='%s'>Read here &#187;</a>", "http://php.net/manual/en/book.mysqli.php", "http://crybit.com/how-to-enable-mysqli-extension-on-web-server/"));
		if (version_compare(phpversion(), '5.4.0', '<')) 							die(sprintf("Error. You need PHP 5.4.0 (or greater). Current php version: %s", phpversion()));

		foreach($options as $key => $value)
			if (in_array($key, array("public_key", "private_key", "webdev_key", "amount", "amountUSD", "period", "language", "iframeID", "orderID", "userID", "userFormat"))) $this->$key = (is_string($value)) ? trim($value) : $value;

		$this->boxID = $this->left($this->public_key, "AA");

		if (preg_replace('/[^A-Za-z0-9]/', '', $this->public_key) != $this->public_key || strlen($this->public_key) != 50 || !strpos($this->public_key, "AA") || !$this->boxID || !is_numeric($this->boxID) || strpos($this->public_key, "77") === false || !strpos($this->public_key, "PUB")) die("Invalid Cryptocoin Payment Box PUBLIC KEY - " . ($this->public_key?$this->public_key:"cannot be empty"));

		if (preg_replace('/[^A-Za-z0-9]/', '', $this->private_key) != $this->private_key || strlen($this->private_key) != 50 || !strpos($this->private_key, "AA") || $this->boxID != $this->left($this->private_key, "AA") || !strpos($this->private_key, "PRV") || $this->left($this->private_key, "PRV") != $this->left($this->public_key, "PUB")) die("Invalid Cryptocoin Payment Box PRIVATE KEY".($this->private_key?"":" - cannot be empty"));

		if (!Config::get("constants.CRYPTOBOX_PRIVATE_KEYS") || !in_array($this->private_key, explode("^", Config::get("constants.CRYPTOBOX_PRIVATE_KEYS")))) die("Error. Please add your Cryptobox Private Key ".(Config::get("constants.CRYPTOBOX_WORDPRESS") ? "on your plugin settings page" : "to \$cryptobox_private_keys in file cryptobox.config.php"));

		if ($this->webdev_key && (preg_replace('/[^A-Za-z0-9]/', '', $this->webdev_key) != $this->webdev_key || strpos($this->webdev_key, "DEV") !== 0 || $this->webdev_key != strtoupper($this->webdev_key) || $this->icrc32($this->left($this->webdev_key, "G", false)) != $this->right($this->webdev_key, "G", false))) $this->webdev_key = "";

		$c = substr($this->right($this->left($this->public_key, "PUB"), "AA"), 5);
		$this->coinLabel = $this->right($c, "77");
		$this->coinName = $this->left($c, "77");

		if ($this->amount 	 && strpos($this->amount, ".")) 	$this->amount = rtrim(rtrim($this->amount, "0"), ".");
		if ($this->amountUSD && strpos($this->amountUSD, ".")) 	$this->amountUSD = rtrim(rtrim($this->amountUSD, "0"), ".");

		if (!$this->amount || $this->amount <= 0) 		$this->amount 	 = 0;
		if (!$this->amountUSD || $this->amountUSD <= 0) 	$this->amountUSD = 0;

		if (($this->amount <= 0 && $this->amountUSD <= 0) || ($this->amount > 0 && $this->amountUSD > 0)) die("You can use in cryptobox options one of variable only: amount or amountUSD. You cannot place values in that two variables together (submitted amount = '".$this->amount."' and amountUSD = '".$this->amountUSD."' )");

		if ($this->amount && (!is_numeric($this->amount) || $this->amount < 0.0001 || $this->amount > 500000000)) die("Invalid Amount - ".sprintf('%.8f', $this->amount)." $this->coinLabel. Allowed range: 0.0001 .. 500,000,000");
		if ($this->amountUSD && (!is_numeric($this->amountUSD) || $this->amountUSD < 0.01 || $this->amountUSD > 1000000)) die("Invalid amountUSD - ".sprintf('%.8f', $this->amountUSD)." USD. Allowed range: 0.01 .. 1,000,000");

		$this->period = trim(strtoupper(str_replace(" ", "", $this->period)));
		if (substr($this->period, -1) == "S") $this->period = substr($this->period, 0, -1);
		for ($i=1; $i<=90; $i++) { $arr[] = $i."MINUTE"; $arr[] = $i."HOUR"; $arr[] = $i."DAY"; $arr[] = $i."WEEK"; $arr[] = $i."MONTH"; }
		if ($this->period != "NOEXPIRY" && !in_array($this->period, $arr)) die("Invalid Cryptobox Period - $this->period");
		$this->period = str_replace(array("MINUTE", "HOUR", "DAY", "WEEK", "MONTH"), array(" MINUTE", " HOUR", " DAY", " WEEK", " MONTH"), $this->period);

		$this->localisation = json_decode(CRYPTOBOX_LOCALISATION, true);
		if (!in_array(strtolower($this->language), array_keys($this->localisation))) $this->language = "en";
		$this->language = $this->cryptobox_sellanguage($this->language);
		$this->localisation = $this->localisation[$this->language];

		if ($this->iframeID && preg_replace('/[^A-Za-z0-9\_\-]/', '', $this->iframeID) != $this->iframeID || $this->iframeID == "cryptobox_live_") die("Invalid iframe ID - $this->iframeID. Allowed symbols: a..Z0..9_-");

		$this->userID = trim($this->userID);
		if ($this->userID && preg_replace('/[^A-Za-z0-9\.\_\-\@]/', '', $this->userID) != $this->userID) die("Invalid User ID - $this->userID. Allowed symbols: a..Z0..9_-@.");
		if (strlen($this->userID) > 50) die("Invalid User ID - $this->userID. Max: 50 symbols");

		$this->orderID = trim($this->orderID);
		if ($this->orderID && preg_replace('/[^A-Za-z0-9\.\_\-\@]/', '', $this->orderID) != $this->orderID) die("Invalid Order ID - $this->orderID. Allowed symbols: a..Z0..9_-@.");
		if (!$this->orderID || strlen($this->orderID) > 50) die("Invalid Order ID - $this->orderID. Max: 50 symbols");

		if ($this->userID)
			$this->userFormat = "MANUAL";
		else
		{
			switch ($this->userFormat)
			{
				case "COOKIE":
					$this->cookieName = 'cryptoUsr'.$this->icrc32($this->boxID."*&*".$this->coinLabel."*&*".$this->orderID."*&*".$this->private_key);
					if (isset($_COOKIE[$this->cookieName]) && trim($_COOKIE[$this->cookieName]) && strpos($_COOKIE[$this->cookieName], "__") && preg_replace('/[^A-Za-z0-9\_]/', '', $_COOKIE[$this->cookieName]) == $_COOKIE[$this->cookieName] && strlen($_COOKIE[$this->cookieName]) <= 30) $this->userID = trim($_COOKIE[$this->cookieName]);
					else
					{
						$s = trim(strtolower($_SERVER['SERVER_NAME']), " /");
						if (stripos($s, "www.") === 0) $s = substr($s, 4);
						$d = time(); if ($d > 1410000000) $d -= 1410000000;
						$v = trim($d."__".substr(md5(uniqid(mt_rand().mt_rand().mt_rand())), 0, 10));
						setcookie($this->cookieName, $v, time()+(10*365*24*60*60), '/', $s);
						$this->userID = $v;
					}
				break;

				case "SESSION":

					if (session_status() == PHP_SESSION_NONE) session_start();
					$this->cookieName = 'cryptoUser'.$this->icrc32($this->private_key."*&*".$this->boxID."*&*".$this->coinLabel."*&*".$this->orderID);
					if (isset($_SESSION[$this->cookieName]) && trim($_SESSION[$this->cookieName]) && strpos($_SESSION[$this->cookieName], "--") && preg_replace('/[^A-Za-z0-9\-]/', '', $_SESSION[$this->cookieName]) == $_SESSION[$this->cookieName] && strlen($_SESSION[$this->cookieName]) <= 30) $this->userID = trim($_SESSION[$this->cookieName]);
					else
					{
						$d = time(); if ($d > 1410000000) $d -= 1410000000;
						$v = trim($d."--".substr(md5(uniqid(mt_rand().mt_rand().mt_rand())), 0, 10));
						$this->userID = $_SESSION[$this->cookieName] = $v;
					}
				break;

				case "IPADDRESS":

					if (session_status() == PHP_SESSION_NONE) session_start();
					if (isset($_SESSION['cryptoUserIP']) && filter_var($_SESSION['cryptoUserIP'], FILTER_VALIDATE_IP) && preg_replace('/[^A-Za-z0-9\.\:]/', '', $_SESSION['cryptoUserIP']) == $_SESSION['cryptoUserIP'] && strlen($_SESSION['cryptoUserIP']) <= 50)
						 $ip = $_SESSION['cryptoUserIP'];
					else $ip = $_SESSION['cryptoUserIP'] = $this->ip_address();
					$this->userID = trim(md5($ip."*&*".$this->boxID."*&*".$this->coinLabel."*&*".$this->orderID));

				break;

				default:
					die("Invalid userFormat value - $this->userFormat");
				break;
			}
		}

		// version string
		$this->ver = "version | gourlphp " . Config::get("constants.CRYPTOBOX_VERSION");
		if (Config::get("constants.CRYPTOBOX_WORDPRESS")) $this->ver .= " | gourlwordpress" . (defined('GOURL_VERSION') ? " ".GOURL_VERSION : "");
		if (Config::get("constants.CRYPTOBOX_WORDPRESS") && defined('GOURLWC_VERSION') && strpos($this->orderID, "gourlwoocommerce.") === 0)   $this->ver .= " | gourlwoocommerce " . GOURLWC_VERSION;
		if (Config::get("constants.CRYPTOBOX_WORDPRESS") && defined('GOURLAP_VERSION') && strpos($this->orderID, "gourlappthemes.") === 0)     $this->ver .= " | gourlappthemes " . GOURLAP_VERSION;
		if (Config::get("constants.CRYPTOBOX_WORDPRESS") && defined('GOURLEDD_VERSION') && strpos($this->orderID, "gourledd.") === 0)          $this->ver .= " | gourledd " . GOURLEDD_VERSION;
		if (Config::get("constants.CRYPTOBOX_WORDPRESS") && defined('GOURLPMP_VERSION') && strpos($this->orderID, "gourlpmpro.") === 0)        $this->ver .= " | gourlpmpro " . GOURLPMP_VERSION;
		if (Config::get("constants.CRYPTOBOX_WORDPRESS") && defined('GOURLGV_VERSION') && strpos($this->orderID, "gourlgive.") === 0)          $this->ver .= " | gourlgive " . GOURLGV_VERSION;
		if (Config::get("constants.CRYPTOBOX_WORDPRESS") && defined('GOURLJI_VERSION') && strpos($this->orderID, "gourljigoshop.") === 0)      $this->ver .= " | gourljigoshop " . GOURLJI_VERSION;
		if (Config::get("constants.CRYPTOBOX_WORDPRESS") && defined('GOURLWPSC_VERSION') && strpos($this->orderID, "gourlwpecommerce.") === 0) $this->ver .= " | gourlwpecommerce " . GOURLWPSC_VERSION;
		if (Config::get("constants.CRYPTOBOX_WORDPRESS") && defined('GOURLMP_VERSION') && strpos($this->orderID, "gourlmarketpress.") === 0)   $this->ver .= " | gourlmarketpress " . GOURLMP_VERSION;

		if (!$this->iframeID) $this->iframeID = $this->iframe_id();

		$this->check_payment();

		return true;
	}





	/* 1. Function display_cryptobox() -
	 *
	 * Display Cryptocoin Payment Box; the cryptobox will automatically displays successful message if payment has been received
	 *
	 * Usually user will see on bottom of payment box button 'Click Here if you have already sent coins' (when $submit_btn = true)
	 * and when they click on that button, script will connect to our remote cryptocoin payment box server
	 * and check user payment.
	 *
	 * As backup, our server will also inform your server automatically through IPN every time a payment is received
	 * (file cryptobox.callback.php). I.e. if the user does not click on the button or you have not displayed the button,
	 * your website will receive a notification about a given user anyway and save it to your database.
	 * Next time your user goes to your website/reloads page they will automatically see the message
	 * that their payment has been received successfully.
	*/
	public function display_cryptobox($submit_btn = true, $width = "540", $height = "230", $box_style = "", $message_style = "", $anchor = "")
	{
		if (!$box_style) 	 $box_style = "border-radius:15px;box-shadow:0 0 12px #aaa;-moz-box-shadow:0 0 12px #aaa;-webkit-box-shadow:0 0 12px #aaa;padding:3px 6px;margin:10px";
		if (!$message_style) $message_style = "display:inline-block;max-width:580px;padding:15px 20px;box-shadow:0 0 10px #aaa;-moz-box-shadow: 0 0 10px #aaa;margin:7px;font-size:13px;font-weight:normal;line-height:21px;font-family: Verdana, Arial, Helvetica, sans-serif;";

		$width = intval($width);
		$height = intval($height);

		$box_style = trim($box_style, "; ") .";max-width:".$width."px !important;max-height:".$height."px !important;";

		$cryptobox_html = "";
		$val 			= md5($this->iframeID.$this->private_key.$this->userID);

		if ($submit_btn && isset($_POST["cryptobox_live_"]) && $_POST["cryptobox_live_"] == $val)
		{
			$id = "id".md5(mt_rand());
			if (!$this->paid) $cryptobox_html .= "<a id='c".$this->iframeID."' name='c".$this->iframeID."'></a>";
			$cryptobox_html .= "<br><div id='$id' align='center'>";
			$cryptobox_html .= '<div'.(in_array($this->language, array("ar", "fa"))?' dir="rtl"':'').' style="'.htmlspecialchars($message_style, ENT_COMPAT).'">';


			if ($this->paid) $cryptobox_html .= "<span style='color:#339e2e;white-space:nowrap;'>".$this->payment_status_text()."</span>";
			else $cryptobox_html .= "<span style='color:#eb4847'>".$this->payment_status_text()."</span><script type='text/javascript'>cryptobox_msghide('$id')</script>";

			$cryptobox_html .= "</div></div><br>";
		}

		$hash = $this->cryptobox_hash(false, $width, $height);

		$cryptobox_html .= "<div align='center' style='min-width:".$width."px'><iframe id='$this->iframeID' ".($box_style?'style="'.htmlspecialchars($box_style, ENT_COMPAT).'"':'')." scrolling='no' marginheight='0' marginwidth='0' frameborder='0' width='$width' height='$height'></iframe></div>";
		$cryptobox_html .= "<div><script type='text/javascript'>";
		$cryptobox_html .= "cryptobox_show($this->boxID, '$this->coinName', '$this->public_key', $this->amount, $this->amountUSD, '$this->period', '$this->language', '$this->iframeID', '$this->userID', '$this->userFormat', '$this->orderID', '$this->cookieName', '$this->webdev_key', '".base64_encode($this->ver)."', '$hash', $width, $height);";
		$cryptobox_html .= "</script></div>";

		if ($submit_btn && !$this->paid)
		{
			$cryptobox_html .= "<form action='".$_SERVER["REQUEST_URI"]."#".($anchor?$anchor:"c".$this->iframeID)."' method='post'>";
			$cryptobox_html .= "<input type='hidden' id='cryptobox_live_' name='cryptobox_live_' value='$val'>";
			$cryptobox_html .= "<div align='center'>";
			$cryptobox_html .= "<button".(in_array($this->language, array("ar", "fa"))?' dir="rtl"':'')." style='color:#555;border-color:#ccc;background:#f7f7f7;-webkit-box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);vertical-align:top;display:inline-block;text-decoration:none;font-size:13px;line-height:26px;min-height:28px;margin:20px 0 25px 0;padding:0 10px 1px;cursor:pointer;border-width:1px;border-style:solid;-webkit-appearance:none;-webkit-border-radius:3px;border-radius:3px;white-space:nowrap;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;font-family:\"Open Sans\",sans-serif;font-size: 13px;font-weight: normal;text-transform: none;'>&#160; ".str_replace(array("%coinName%", "%coinNames%", "%coinLabel%"), array($this->coinName, (in_array($this->coinLabel, array('BCH', 'BSV', 'DASH'))?$this->coinName:$this->coinName.'s'), $this->coinLabel), $this->localisation["button"]).($this->language!="ar"?" &#187;":"")." &#160;</button>";
			$cryptobox_html .= "</div>";
			$cryptobox_html .= "</form>";
		}

		$cryptobox_html .= "<br>";

		return $cryptobox_html;
	}





	/* 2. Function cryptobox_json_url()
	 *
	 * It generates url with your parameters to gourl.io payment gateway.
	 * Using this url you can get bitcoin/altcoin payment box values in JSON format and use it on html page with Jquery/Ajax.
	 * See instruction https://gourl.io/bitcoin-payment-gateway-api.html#p8
	 *
	 * JSON Values Example -
	 * Payment not received - https://coins.gourl.io/b/20/c/Bitcoin/p/20AAvZCcgBitcoin77BTCPUB0xyyeKkxMUmeTJRWj7IZrbJ0oL/a/0/au/2.21/pe/NOEXPIRY/l/en/o/invoice22/u/83412313__3bccb54769/us/COOKIE/j/1/d/ODIuMTEuOTQuMTIx/h/e889b9a07493ee96a479e471a892ae2e
	 * Payment received successfully - https://coins.gourl.io/b/20/c/Bitcoin/p/20AAvZCcgBitcoin77BTCPUB0xyyeKkxMUmeTJRWj7IZrbJ0oL/a/0/au/0.1/pe/NOEXPIRY/l/en/o/invoice1/u/demo/us/MANUAL/j/1/d/ODIuMTEuOTQuMTIx/h/ac7733d264421c8410a218548b2d2a2a
	 *
	 * Alternatively, you can receive JSON values through php curl on server side - function get_json_values() and use it in your php/other files without using javascript and jquery/ajax.
	 *
	 * By default the user sees bitcoin payment box as iframe in html format - function display_cryptobox().
	 * JSON data will allow you to easily customise your bitcoin payment boxes. For example, you can display payment amount and
	 * bitcoin payment address with your own text, you can also accept payments in android/windows and other applications.
	 * You get an array of values - payment amount, bitcoin address, text; and can place them in any position on your webpage/application.
	 */
	public function cryptobox_json_url()
	{

	    $ip		= $this->ip_address();
	    $hash 	= $this->cryptobox_hash(true);

	    $data = array
	    (
	        "b" 	=> $this->boxID,
	        "c" 	=> $this->coinName,
	        "p" 	=> $this->public_key,
	        "a" 	=> $this->amount,
	        "au" 	=> $this->amountUSD,
	        "pe"	=> str_replace(" ", "_", $this->period),
	        "l" 	=> $this->language,
	        "o" 	=> $this->orderID,
	        "u" 	=> $this->userID,
	        "us"	=> $this->userFormat,
	        "j"     => 1, // json
	        "d" 	=> base64_encode($ip),
	        "f"     => base64_encode($this->ua(false)),
	        "t" 	=> base64_encode($this->ver),
	        "h" 	=> $hash
	    );

	    if ($this->webdev_key) $data["w"]  = $this->webdev_key;
	    $data["z"] = rand(0,10000000);

	    $url = "https://coins.gourl.io";
	    foreach($data as $k=>$v) $url .= "/".$k."/".rawurlencode($v);

	    return $url;
	}





	/* 3. Function get_json_values()
	 *
	 * Alternatively, you can receive JSON values through php curl on server side and use it in your php/other files without using javascript and jquery/ajax.
	 * Return Array; Examples -
	 * Payment not received - https://coins.gourl.io/b/20/c/Bitcoin/p/20AAvZCcgBitcoin77BTCPUB0xyyeKkxMUmeTJRWj7IZrbJ0oL/a/0/au/2.21/pe/NOEXPIRY/l/en/o/invoice22/u/83412313__3bccb54769/us/COOKIE/j/1/d/ODIuMTEuOTQuMTIx/h/e889b9a07493ee96a479e471a892ae2e
	 * Payment received successfully - https://coins.gourl.io/b/20/c/Bitcoin/p/20AAvZCcgBitcoin77BTCPUB0xyyeKkxMUmeTJRWj7IZrbJ0oL/a/0/au/0.1/pe/NOEXPIRY/l/en/o/invoice1/u/demo/us/MANUAL/j/1/d/ODIuMTEuOTQuMTIx/h/ac7733d264421c8410a218548b2d2a2a
	 *
	 * By default the user sees bitcoin payment box as iframe in html format - function display_cryptobox().
	 * JSON data will allow you to easily customise your bitcoin payment boxes. For example, you can display payment amount and
	 * bitcoin payment address with your own text, you can also accept payments in android/windows and other applications.
	 * You get an array of values - payment amount, bitcoin address, text; and can place them in any position on your webpage/application.
	 */
	public function get_json_values()
	{
		$url = $this->cryptobox_json_url();

		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt( $ch, CURLOPT_USERAGENT, $this->ua());
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt( $ch, CURLOPT_TIMEOUT, 20);
		$res = curl_exec( $ch );
		curl_close($ch);

		// security; validate data sent by gourl.io
		$f = false;
		if ($res)
		{
		  $arr = $arr2 = json_decode($res, true);

		  // if error
		  if (!$arr && $res) return array("status" => "error", "err" => substr(strip_tags($res), 0, 250));

		  if (isset($arr2["data_hash"]))
		  {
		      unset($arr2["data_hash"]);
		      if (strtolower($arr["data_hash"]) == strtolower(hash("sha512", $this->private_key.json_encode($arr2).$this->private_key))) $f = true;
		  }
		}
		if (!$f) $arr = array();

		if ($arr && $arr["status"] == "payment_received" && !$this->paid) $this->check_payment(true);

		return $arr;
	}





	/* 4. Function cryptobox_hash($json = false, $width = 0, $height = 0)
	 *
	 * It generates security md5 hash for all values used in payment boxes.
	 * This protects payment box parameters from changes by end user in web browser.
	 * $json = true - generate md5 hash for json payment data output
	 * or generate hash for iframe html box with sizes $width x $height
	 */
	public function cryptobox_hash($json = false, $width = 0, $height = 0)
	{

	    if ($json) $hash_str = $this->boxID."|".$this->coinName."|".$this->public_key."|".$this->private_key."|".$this->webdev_key."|".$this->amount."|".$this->amountUSD."|".$this->period."|". $this->language."|".$this->orderID."|".$this->userID."|".$this->userFormat."|".$this->ver."|".$this->ip_address();
	    else       $hash_str = $this->boxID."|".$this->coinName."|".$this->public_key."|".$this->private_key."|".$this->webdev_key."|".$this->amount."|".$this->amountUSD."|".$this->period."|". $this->language."|".$this->orderID."|".$this->userID."|".$this->userFormat."|".$this->ver."|".$this->iframeID."|".$width."|".$height;


	    $hash = md5($hash_str);


	    return $hash;
	}





	/* 5. Function is_paid($remotedb = false) -
	 *
	 * This Checks your local database whether payment has been received and is stored on your local database.
	 *
	 * If use $remotedb = true, it will check also on the remote cryptocoin payment server (gourl.io),
	 * and if payment is received, it saves it in your local database. Usually user will see on bottom
	 * of payment box button 'Click Here if you have already sent coins' and when they click on that button,
	 * script it will connect to our remote cryptocoin payment box server. Therefore you don't need to use
	 * $remotedb = true, it will make your webpage load slowly if payment on gourl.io is checked during
	 * each of your page loadings.
	 *
	 * Please note that our server will also inform your server automatically every time when payment is
	 * received through callback url: cryptobox.callback.php. I.e. if the user does not click on button,
	 * your website anyway will receive notification about a given user and save it in your database.
	 * And when your user next time comes on your website/reload page he will automatically will see
	 * message that his payment has been received successfully.
	 */
	public function is_paid($remotedb = false)
	{
		if (!$this->paymentID && $remotedb) $this->check_payment($remotedb);
		if ($this->paid) return true;
		else return false;
	}





	/* 6. Function is_confirmed() -
	*
	* Function return is true if transaction/payment has 6+ confirmations.
	* It connects with our payment server and gets the current transaction status (confirmed/unconfirmed).
	* Some merchants wait until this transaction has been confirmed.
	* Average transaction confirmation time - 10-20min for 6+ confirmations (altcoins)
	*/
	public function is_confirmed()
	{
		if ($this->confirmed) return true;
		else return false;
	}





	/* 7. Function amount_paid()
	 *
	 * Returns the amount of coins received from the user
	 */
	public function amount_paid()
	{
		if ($this->paid) return $this->amountPaid;
		else return 0;
	}





	/* 8. Function amount_paid_usd()
	 *
	 * Returns the approximate amount in USD received from the user
	 * using live cryptocurrency exchange rates on the datetime of payment.
	 * Live Exchange Rates obtained from sites poloniex.com and bitstamp.net
	 * and are updated every 30 minutes!
	 *
	 * Or you can directly specify your price in USD and submit it in cryptobox using
	 * variable 'amountUSD'. Cryptobox will automatically convert that USD amount
	 * to cryptocoin amount using today current live cryptocurrency exchange rates.
	 *
	 * Using that functionality, you don't need to worry if cryptocurrency prices go down or up.
	 * User will pay you all times the actual price which is linked on current exchange
	 * price in USD on the datetime of purchase.
	 *
	 * You can accepting cryptocoins on your website with cryptobox variable 'amountUSD'.
	 * It increase your online sales and also use Poloniex.com AutoSell feature
	 * (to trade your cryptocoins to USD/BTC during next 30 minutes after payment received).
	 */
	public function amount_paid_usd()
	{
		if ($this->paid) return $this->amountPaidUSD;
		else return 0;
	}





	/* 9. Functions set_status_processed() and is_processed()
	 *
	 * You can use this function when user payment has been received
	 * (function is_paid() returns true) and want to make one time action,
	 * for example  display 'thank you' message to user, etc.
	 * These functions helps you to exclude duplicate processing.
	 *
	 * Please note that the user will continue to see a successful payment result in
	 * their crypto Payment box during the period/timeframe you specify in cryptobox option $period
	 */
	public function set_status_processed()
	{
		if ($this->paymentID && $this->paid)
		{
			if (!$this->processed)
			{
				$sql = "UPDATE crypto_payments SET processed = 1, processedDate = '".gmdate("Y-m-d H:i:s")."' WHERE paymentID = ".intval($this->paymentID)." LIMIT 1";
				$this->run_sql($sql);
				$this->processed = true;
			}
			return true;
		}
		else return false;
	}





	/* 10. Function is_processed()
	 *
	 * If payment status in database is 'processed' - return true,
	 * otherwise return false. You need to use it with
	 * function set_status_processed() together
	*/
	public function is_processed()
	{
		if ($this->paid && $this->processed) return true;
		else return false;
	}





	/* 11. Function cryptobox_type()
	 *
	 * Returns 'paymentbox' or 'captchabox'
	 *
	 * The Cryptocoin Payment Box and Crypto Captcha are
	 * absolutely identical technically except for their visual effect.
	 *
	 * It uses the same code to get your user payment, to process that
	 * payment and to forward received coins to you. They have only two
	 * visual differences - users will see different logos and different
	 * text on successful result page.
	 * For example, for dogecoin it will be - 'Dogecoin Payment' or
	 * 'Dogecoin Captcha' logos and when payment is received we will publish
	 * 'Payment received successfully' or 'Captcha Passed successfully'.
	 *
	 * We have made it easier for you to adapt our payment system to your website.
	 * On signup page you can use 'Bitcoin Captcha' and on sell products page - 'Bitcoin Payment'.
	*/
	public function cryptobox_type()
	{
		return $this->boxType;
	}





	/* 12. Function payment_id()
	 *
	 * Returns current record id in the table crypto_payments.
	 * Crypto_payments table stores all payments from your users
	*/
	public function payment_id()
	{
		return $this->paymentID;
	}




	/* 13. Function payment_date()
	 *
	 * Returns payment/transaction datetime in GMT format
	 * Example - 2014-09-26 17:31:58 (is 26 September 2014, 5:31pm GMT)
	*/
	public function payment_date()
	{
		return $this->paymentDate;
	}



	/* 14. Function payment_info()
	 *
	 * Returns object with current user payment details -
	 * coinLabel 	 	- cryptocurrency label
	 * countryID 	 	- user location country, 3 letter ISO country code
	 * countryName 	 	- user location country
	 * amount 			- paid cryptocurrency amount
	 * amountUSD 	 	- approximate paid amount in USD with exchange rate on datetime of payment made
	 * addr				- your internal wallet address on gourl.io which received this payment
	 * txID 			- transaction id
	 * txDate 			- transaction date (GMT time)
	 * txConfirmed		- 0 - unconfirmed transaction/payment or 1 - confirmed transaction/payment
	 * processed		- true/false. True if you called function set_status_processed() for that payment before
	 * processedDate	- GMT time when you called function set_status_processed()
	 * recordCreated	- GMT time a payment record was created in your database
	 * etc.
	*/
	public function payment_info()
	{
		$obj = ($this->paymentID) ? $this->run_sql("SELECT * FROM crypto_payments WHERE paymentID = ".intval($this->paymentID)." LIMIT 1") : false;
		if ($obj) $obj->countryName = get_country_name($obj->countryID);
		return $obj;
	}





	/* 15. Function cryptobox_reset()
	 *
	 * Optional, It will delete cookies/sessions with userID and new cryptobox with new payment amount
	 * will be displayed after page reload. Cryptobox will recognize user as a new one with new generated userID.
	 * For example, after you have successfully received the cryptocoin payment and had processed it, you can call
	 * one-time cryptobox_reset() in end of your script. Use this function only if you have not set userID manually.
	*/
	public function cryptobox_reset()
	{
		if (in_array($this->userFormat, array("COOKIE", "SESSION")))
		{
			$iframeID = $this->iframe_id();

			switch ($this->userFormat)
			{
				case "COOKIE":
					$s = trim(strtolower($_SERVER['SERVER_NAME']), " /");
					if (stripos($s, "www.") === 0) $s = substr($s, 4);
					$d = time(); if ($d > 1410000000) $d -= 1410000000;
					$v = trim($d."__".substr(md5(uniqid(mt_rand().mt_rand().mt_rand())), 0, 10));
					setcookie($this->cookieName, $v, time()+(10*365*24*60*60), '/', $s);
					$this->userID = $v;
					break;

				case "SESSION":
					$d = time(); if ($d > 1410000000) $d -= 1410000000;
					$v = trim($d."--".substr(md5(uniqid(mt_rand().mt_rand().mt_rand())), 0, 10));
					$this->userID = $_SESSION[$this->cookieName] = $v;
					break;
			}

			if ($this->iframeID == $iframeID) $this->iframeID = $this->iframe_id();

			return true;
		}
		else return false;
	}




	/* 16. Function coin_name()
	 *
	 * Returns coin name (bitcoin, bitcoincash, bitcoinsv, litecoin, dash, etc)
	*/
	public function coin_name()
	{
		return $this->coinName;
	}




	/* 17. Function coin_label()
	 *
	 * Returns coin label (BTC, BCH, BSV, LTC, DASH, etc)
	*/
	public function coin_label()
	{
		return $this->coinLabel;
	}



	/* 18. Function iframe_id()
	 *
	 * Returns payment box frame id
	*/
	public function iframe_id()
	{
		return "box".$this->icrc32($this->boxID."__".$this->orderID."__".$this->userID."__".$this->private_key);
	}




	/* 19. Function payment_status_text()
	 *
	 * Return localize message from $cryptobox_localisation for current user language
	 * it use for "msg_not_received", "msg_received" or "msg_received2"
	 */
	public function payment_status_text()
	{
        if ($this->paid) $txt = str_replace(array("%coinName%", "%coinLabel%", "%amountPaid%"), array($this->coinName, $this->coinLabel, $this->amountPaid), $this->localisation[($this->boxType=="paymentbox"?"msg_received":"msg_received2")]);
        else $txt = str_replace(array("%coinName%", "%coinNames%", "%coinLabel%"), array($this->coinName, (in_array($this->coinLabel, array('BCH', 'BSV', 'DASH'))?$this->coinName:$this->coinName.'s'), $this->coinLabel), $this->localisation["msg_not_received"]);

	    return $txt;
	}



	/* 20. Function display_cryptobox_bootstrap()
	 *
	 *  Show Customize Mobile Friendly Payment Box and automatically displays successful payment message.
	 *  This function use bootstrap4 template; you can use your own template without this function
	 *
	 *  FREE WHITE-LABEL BITCOIN/ALTCOIN PAYMENT BOX WITH THIS FUNCTION
	 *  Simple use this function with 'curl' option and your own logo
	 *
	 *  Live Demo  (awaiting payment)  -     https://gourl.io/lib/examples/example_customize_box.php?boxtype=1
	 *  Live Demo2 (payment received)  -     https://gourl.io/lib/examples/example_customize_box.php?boxtype=2
	 *
	 *  Your html5 file header should have -
	 *
	 *  <!DOCTYPE html>
	 *  <html lang="en">
	 *  <head>
	 *  <title>...</title>
	 *  <meta charset="utf-8">
	 *  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	 *  <meta name="viewport" content="width=device-width, initial-scale=1">

	 *  A. <!-- Bootstrap CSS - Original Theme -->
	 *  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
	 *
	 *  B. OR you can use other Themes, for example from https://bootswatch.com/; replace line with bootstrap.min.css above to line below -
	 *  <!-- <link rel="stylesheet" href="https://bootswatch.com/4/darkly/bootstrap.css"> -->

	 *  C. OR isolate Bootstrap CSS to a particular class to avoid css conflicts with your site main css style; use custom isolate css themes from /css folder
	 *  Bootstrap Isolated CSS (class='bootstrapiso') Original Theme -
	 *  <!-- <link rel="stylesheet" href="/css/bootstrapcustom.min.css"> -->
	 *
	 *  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" crossorigin="anonymous"></script>
	 *  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js" crossorigin="anonymous"></script>
	 *  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
	 *  <script defer src="https://use.fontawesome.com/releases/v5.12.0/js/all.js" crossorigin="anonymous"></script>
	 *  script src="<?php echo CRYPTOBOX_JS_FILES_PATH; ?>support.min.js" crossorigin="anonymous"></script>
	 *  <style>
            html { font-size: 14px; }
            @media (min-width: 768px) { html { font-size: 16px; } .tooltip-inner { max-width: 350px; } }
            .mncrpt .container { max-width: 980px; }
            .mncrpt .box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
            img.radioimage-select { padding: 7px; border: solid 2px #ffffff; margin: 7px 1px; cursor: pointer; box-shadow: none; }
            img.radioimage-select:hover { border: solid 2px #a5c1e5; }
            img.radioimage-select.radioimage-checked { border: solid 2px #7db8d9; background-color: #f4f8fb; }
	 *  </style>
	 *  </head>
	 *  <body>
	 *  ....
	 *

	* This function has the following parameters -
	* $coins - list of cryptocoins which you accept for payment (bitcoin/litecoin/dash/..)
	* $def_coin - default coin in payment box
	* $def_language - default language in payment box
	* $custom_text - your own text above payment box
	* $coinImageSize - coin selection list - image sizes; default 70px
	* $qrcodeSize - QRCode size; default 200px
	* $show_languages - show or hide language selection menu above payment box
	* $logoimg_path - show or hide (when empty value) logo above payment box. You can use default logo or place path to your own logo
	* $resultimg_path - after payment is received, you can customize successful image in payment box (image with your company text for example)
	* $resultimgSize - result image size; default 250px
	* redirect - redirect to another page after payment is received (3 seconds delay)
	*
	* method - "ajax" or "curl".
	*    AJAX - user don't need click payment submit button on form. Payment box show successful paid message automatically
	*    CURL + White Label Payment Box with Your Own Logo (White Label Product - https://www.google.com/search?q=white+label+product), user need to click on button below payment form when payment is sent
	*    with ajax - user browser receive payment data directly from our server and automatically show successful payment notification message on the page (without page reload, any clicks on buttons).
	*    with curl - User browser receive payment data in json format from your server only; and your server receive json data from our server
	*
	* debug - show raw payment data from gourl.io on the page also, for debug purposes.
	*
	* JSON Raw Payment Values Example -
	* Payment not received - https://coins.gourl.io/b/20/c/Bitcoin/p/20AAvZCcgBitcoin77BTCPUB0xyyeKkxMUmeTJRWj7IZrbJ0oL/a/0/au/2.21/pe/NOEXPIRY/l/en/o/invoice22/u/83412313__3bccb54769/us/COOKIE/j/1/d/ODIuMTEuOTQuMTIx/h/e889b9a07493ee96a479e471a892ae2e
	* Payment received successfully - https://coins.gourl.io/b/20/c/Bitcoin/p/20AAvZCcgBitcoin77BTCPUB0xyyeKkxMUmeTJRWj7IZrbJ0oL/a/0/au/0.1/pe/NOEXPIRY/l/en/o/invoice1/u/demo/us/MANUAL/j/1/d/ODIuMTEuOTQuMTIx/h/ac7733d264421c8410a218548b2d2a2a
	 *
	 */

	public function display_cryptobox_bootstrap ($coins = array(), $def_coin = "", $def_language = "en", $custom_text = "", $coinImageSize = 70, $qrcodeSize = 200, $show_languages = true, $logoimg_path = "default", $resultimg_path = "default", $resultimgSize = 250, $redirect = "", $method = "curl", $debug = false)
	{


	    $logoimg_path = preg_replace('/[^A-Za-z0-9\-\_\=\?\&\.\;\:\/]/', '', $logoimg_path);
	    $resultimg_path = preg_replace('/[^A-Za-z0-9\-\_\=\?\&\.\;\:\/]/', '', $resultimg_path);
	    $redirect = preg_replace('/[^A-Za-z0-9\-\_\=\?\&\.\;\:\/]/', '', $redirect);

	    $custom_text = strip_tags($custom_text, '<p><a><br>');

	    $coinImageSize    = intval($coinImageSize);
	    if ($coinImageSize > 200) $coinImageSize = 70;

	    $qrcodeSize    = intval($qrcodeSize);
	    if ($qrcodeSize > 500) $qrcodeSize = 200;

	    $resultimgSize    = intval($resultimgSize);
	    if ($resultimgSize > 500) $resultimgSize = 250;

	    if (!in_array($method, array("ajax", "curl"))) $method = "curl";


	    $ext           = Config::get("constants.CRYPTOBOX_PREFIX_HTMLID");      // any prefix for all html elements; default 'acrypto_'
	    $ext2          = "h".trim($ext, " _");

	    $page_url      = "//".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]."#".$ext2; // Current page url
	    $hide       =  "style='display:none'";

	    $phpdir_path   = (defined("CRYPTOBOX_PHP_FILES_PATH")) ? CRYPTOBOX_PHP_FILES_PATH : "";            // path to directory with files cryptobox.class.php/cryptobox.callback.php/cryptobox.newpayment.php; cryptobox.newpayment.php will be automatically call through ajax or php two times - when payment received and when confirmed (6 confirmations)
	    $imgdir_path   = asset('crypto_images')."/";     // path to directory with coin image files (directory 'images' by default)
	    $jsdir_path    = asset('crypto_js')."/";      // path to directory with files ajax.min.js/support.min.js



	    // Language selection list for payment box (html code)
	    if ($show_languages) $languages_list = $this->display_language_box($def_language, $ext2, false);





	    // ---------------------------
	    // Bootstrap4 Template Start
	    // ----------------------------

	    // All Payment Box Elements Area Start ...
	    $tmp  = "<div class='bootstrapiso'>";
	    $tmp .= "<div id='".$ext2."' class='".$ext."cryptobox_area mncrpt'>";

	    //JQuery Payment Box Script, see https://github.com/cryptoapi/Payment-Gateway/blob/master/js/source/ajax.js
	    if ($method == "ajax")
	    {
	        $tmp .= "<script>jQuery.getScript('".$jsdir_path."ajax.min.js',  function() { cryptobox_ajax('" . base64_encode($this->cryptobox_json_url()) . "', " . intval($this->is_paid()) . ", " . intval($this->is_confirmed()) . ", '" . base64_encode($phpdir_path) . "', '" . base64_encode($imgdir_path) . "', '" . base64_encode($logoimg_path) . "', '" . base64_encode($ext) . "', '" . base64_encode($redirect) . "'); })</script>";
	    }
	    else
	    {
	        $data = $this->get_json_values();
	        unset($data["public_key"]); unset($data["texts"]["website"]);
	        if (isset($data["private_key"])) unset($data["private_key"]);
	        if (isset($data["private_key_hash"])) unset($data["private_key_hash"]);
	        unset($data["data_hash"]);
	        $data = json_encode($data, JSON_FORCE_OBJECT | JSON_HEX_APOS);
	        $tmp .= '<script>jQuery(document).ready(function(){ cryptobox_update_page("'.base64_encode($data).'", "' . base64_encode($imgdir_path) . '", "' . base64_encode($logoimg_path) . '", "' . base64_encode($ext) . '") })</script>';
	        if ($this->is_paid() && $redirect) $tmp .= '<script>setTimeout(function() { window.location = "'.$redirect.'"; }, 3000);</script>';
	    }



	    // ----------------------------------
	    // Text - Pay now + Custom User text
	    // ----------------------------------

	    $tmp .= "<div class='".$ext."header px-3 py-3 pt-md-5 pb-md-4 mx-auto my-4 text-center' style='max-width:700px'>";
	    $tmp .= "<h1 class='display-4'><span class='".$ext."texts_pay_now'>&#160;</span>";
	    $tmp .= "<span class='".$ext."loading_icon mr-3 float-right' " . $hide . "><i style='font-size:50%;' class='fas fa-sync-alt fa-spin'></i></span>";
	    $tmp .= "</h1>";



	    $custom_text = trim($custom_text);
	    if ($custom_text)
	    {
    	    $tmp .= "<br>";
    	    if (stripos($custom_text, "<p") === false) $tmp .= "<p class='lead'>" . $custom_text . "</p>"; else $tmp .= $custom_text;
	    }
	    $tmp .= "</div>";



	     // Coin selection list (bitcoin/litecoin/etc)
	     // --------------------
	     $coins_list_html = "";
	     if (!$this->is_paid())
	     {
	        // Coin selection list (html code)
	        $coins_list = $this->display_currency_box($coins, $def_coin, $def_language, $coinImageSize, "margin: 20px 0 80px 0", $imgdir_path, $ext2, true);
	        $coins_list_html = "<div class='container ".$ext."coins_list'><div class='row'><div class='col-12 text-center col-sm-10 offset-sm-1 col-md-8 offset-md-2 text-center'>" . $coins_list . "</div></div></div>";
	     }


	    // ------------------------------
	    // Payment Box Ajax Loading ...
	    // ------------------------------
	    $tmp .= "<div class='".$ext."loader' style='height:700px'>";

	    $tmp .= "<form action='" . $page_url. "' method='post'>";
	    $tmp .= "<div class='container text-center ".$ext."loader_button pt-5 mt-5'><br><br><br><br><br>";
	    $tmp .= "<button type='submit' title='Click to Reload Page' class='btn btn-outline-secondary btn-lg'><i class='fas fa-spinner fa-spin'></i> &#160; " . $this->coin_name() . " " . $this->localisation["loading"] . "</button>";
	    $tmp .= "</div>";

	    $tmp .= "<div class='container'>";
	    $tmp .= "<div class='row'>";
	    $tmp .= "<div class='col-12 text-center col-sm-10 offset-sm-1 col-md-8 offset-md-2'>";
	    $tmp .= "<div class='".$ext."cryptobox_error' " . $hide . ">";
	    $tmp .= $coins_list_html;
	    $tmp .= "<div class='card box-shadow'>";
	    $tmp .= "<div class='card-header'>";
	    $tmp .= "<h4 class='my-0 font-weight-normal'>Error Message";
	    $tmp .= "<span class='".$ext."loading_icon mr-3 float-left' " . $hide . "> <i class='fas fa-laptop'></i></span>";
	    $tmp .= "<span class='".$ext."loading_icon mr-3 float-left' " . $hide . "> <i class='fas fa-sync-alt fa-spin'></i></span>";
	    $tmp .= "</h4>";
	    $tmp .= "</div>";
	    $tmp .= "<div class='card-body'>";
	    $tmp .= "<h1 class='card-title'>" . $this->coin_name() . " " . $this->localisation["loading"] . "</h1>";
	    $tmp .= "<br>";
	    $tmp .= "<div class='lead ".$ext."error_message'></div>";
	    $tmp .= "<br><br>";
	    $tmp .= "<button type='submit' class='".$ext."button_error btn btn-outline-primary btn-block btn-lg'><i class='fas fa-sync'></i> &#160; Reload Page</button>";
	    $tmp .= "<br>";
	    $tmp .= "</div>";
	    $tmp .= "</div>";
	    $tmp .= "<br><br><br><br><br>";
	    $tmp .= "</div>";
	    $tmp .= "</div>";
	    $tmp .= "</div>";
	    $tmp .= "</div>";
	    $tmp .= "</form>";

	    $tmp .= "</div>";

	    // End - Payment Box Ajax Loading ...




	    // ----------------------------
	    // Area above Payment Box
	    // ----------------------------
	    $tmp .= "<div class='".$ext."cryptobox_top' " . $hide . ">";


        // A1. Notification payment received or not; when user click 'Refresh' button below payment form
        // --------------------
        if (isset($_POST["".$ext."refresh_"]) || isset($_POST["".$ext."refresh2_"]))
        {
	            $tmp .= "<div class='row ".$ext."msg mx-2'>";
	            $tmp .= "<div class='container'>";
	            $tmp .= "<div class='row'>";
	            $tmp .= "<div class='col-12 col-sm-10 offset-sm-1 mb-5 mt-2 text-left'>";

                if ($this->is_paid(true))
                    $tmp .= "<span class='badge badge-success ".$ext."paymentcaptcha_statustext'>Successfully Received</span>";
                else
                    $tmp .= "<span class='badge badge-danger ".$ext."paymentcaptcha_statustext'>Not Received</span>";

	            $tmp .= "<div class='jumbotron jumbotron-fluid text-center'>";
	            $tmp .= "<div class='container'>";
                $t = $this->payment_status_text();
	            if (mb_strpos($t, '<br>'))
	            {
	                $tmp .= "<h3 class='display-5'>" . $this->left($t, "<br>") . "</h3><br>";
	                $t = $this->right($t, '<br>');
	            }
	            $tmp .= "<p class='lead'>" . $t . "</p>";
	            $tmp .= "</div>";
	            $tmp .= "</div>";

	            $tmp .= "</div>";
	            $tmp .= "</div>";
	            $tmp .= "</div>";
	            $tmp .= "</div>";

	     }



	     // A2. Coin selection list (bitcoin/litecoin/etc)
	     // --------------------
	     if (!$this->is_paid())
	     {
	         if (!$custom_text) $tmp .= "<br>";
	         $tmp .= $coins_list_html;
	     }




	     // Language / logo Row
	     if ($show_languages || $logoimg_path)
	     {
    	     $tmp .= "<div class='container'>";
    	     $tmp .= "<div class='row'>";
	     }


	     // A3. Box Language
	     // --------------------

	     if ($show_languages)
	     {
    	     $offset = ($logoimg_path) ? "mb-2" : "mb-3";
    	     $tmp .= "<div class='".$ext."box_language col-12 ".(Config::get("constants.CRYPTOBOX_WORDPRESS")?"text-left col-sm-2 col-md-3 offset-md-1":"col-sm-4 offset-sm-1 text-sm-left col-md-4 offset-md-2 text-md-left")." mt-sm-4 $offset'>";
    	     $tmp .= "<div class='btn-group'>";
    	     $tmp .= $languages_list;
    	     $tmp .= "</div>";
    	     $tmp .= "</div>";
	     }
	     // End - A3. Box Language


	     // A4. Logo
	     // --------------------
	     if ($logoimg_path)
	     {
	         $offset = ($show_languages) ? "" : "offset-sm-5 offset-md-6";
	         $tmp .= "<div class='".$ext."box_logo col-12 ".(Config::get("constants.CRYPTOBOX_WORDPRESS")?"col-sm-10 col-md-7":"col-sm-6 col-md-4")." mt-4 $offset'>";
    	     $tmp .= "<div class='text-right'><img style='max-width:200px;max-height:40px;' class='".$ext."logo_image' alt='logo' src='#'></div>";
    	     $tmp .= "<br>";
    	     $tmp .= "</div>";
	     }
    	 // End - A4. Logo


	     if ($show_languages || $logoimg_path)
	     {
    	     $tmp .= "</div>";
    	     $tmp .= "</div>";
	     }
	     else $tmp .= "<br><br>";


	     $tmp .= "</div>";
	     // --------------------
         // End - Area above Payment Box







	     // -----------------------------------------------------------------------------------------------
	     // Two visual types of payment box - payment not received (type1) and payment received (type2)
	     // -----------------------------------------------------------------------------------------------


	     // Type1 - Crypto Payment Box - Payment Not Received


	     $tmp .= "<div class='container ".$ext."cryptobox_unpaid' " . $hide . ">";
	     $tmp .= "<div class='row'>";

	     $tmp .= "<div class='col-12 ".(Config::get("constants.CRYPTOBOX_WORDPRESS")?"col-md-10 offset-md-1":"text-center col-sm-10 offset-sm-1 col-md-8 offset-md-2")."'>";

	     $tmp .= "<form action='" . $page_url . "' method='post'>";
	     $tmp .= "<div class='card box-shadow'>";
	     $tmp .= "<div class='card-header'>";

	     $tmp .= "<h4 class='my-0 font-weight-normal ".$ext."addr_title'><span class='".$ext."texts_coin_address'>&#160;</span>";
	     $tmp .= "<button type='submit' class='".$ext."refresh btn btn-sm btn-outline-secondary float-right'><i class='fas fa-sync-alt'></i></button>";
	     $tmp .= "<span class='".$ext."loading_icon mr-3 float-left' " . $hide . "> <i class='fas fa-laptop'></i></span>";
	     $tmp .= "<span class='".$ext."loading_icon mr-3 float-left' " . $hide . "> <i class='fas fa-sync-alt fa-spin'></i></span>";
	     $tmp .= "</h4>";

	     $tmp .= "</div>";

	     $tmp .= "<div class='card-body'>";

	     if ($qrcodeSize) $tmp .= "<div class='".$ext."copy_address'><a href='#a'><img class='".$ext."qrcode_image' style='max-width:".intval($qrcodeSize)."px; height:auto; width:auto\9;' alt='qrcode' data-size='".intval($qrcodeSize)."' src='#'></a></div>";

	     $tmp .= "<h1 class='mt-3 mb-4 pb-1 card-title ".$ext."copy_amount'><span class='".$ext."amount'>&#160;</span> <small class='text-muted'><span class='".$ext."coinlabel'></span></small></h1>";
	     $tmp .= "<div class='lead ".$ext."copy_amount ".$ext."texts_send'></div>";
	     $tmp .= "<div class='lead ".$ext."texts_no_include_fee'></div>";
	     $tmp .= "<br>";
	     $tmp .= "<h4 class='card-title'>";
	     $tmp .= "<a class='".$ext."wallet_address' style='line-height:1.5;' href='#a'></a> &#160;&#160;";
	     $tmp .= "<a class='".$ext."copy_address' href='#a'><i class='fas fa-copy'></i></a> &#160;&#160;";
	     $tmp .= "<a class='".$ext."wallet_open' href='#a'><i class='fas fa-external-link-alt'></i></a>";
	     $tmp .= "</h4>";
	     $tmp .= "<br>";
	     $tmp .= "<button type='submit' class='".$ext."button_wait btn btn-lg btn-block btn-outline-primary' style='white-space:normal'></button>";
	     $tmp .= "<br>";
	     $tmp .= "<p class='lead ".$ext."texts_intro3'></p>";

	     $tmp .= "</div>";

	     $tmp .= "</div>";

	     $tmp .= "<input type='hidden' id='".$ext."refresh_' name='".$ext."refresh_' value='1'>";

	     $tmp .= "<button type='submit' class='".$ext."button_refresh btn btn-lg btn-block btn-primary mt-3' " . $hide . "></button>";

	     // $tmp .= "<div class='lead ".$ext."texts_btn_wait_hint mt-5'></div>"; // additional hint

	     $tmp .= "</form>";

	     $tmp .= "</div>";

	     if ($method != "ajax" && !$this->is_paid())
	     {
	         $tmp .= "<div class='col-12 text-center ".(Config::get("constants.CRYPTOBOX_WORDPRESS")?"col-md-10 offset-md-1":"col-sm-10 offset-sm-1 col-md-8 offset-md-2")."'>";
	         $tmp .= "<form action='" . $page_url . "' method='post'>";
	         $tmp .= "<input type='hidden' id='".$ext."refresh2_' name='".$ext."refresh2_' value='1'>";
	         $tmp .= "<br><button type='submit' class='".$ext."button_confirm btn btn-lg btn-block btn-primary my-2' style='white-space:normal'><i class='fas fa-angle-double-right'></i> &#160; ".str_replace(array("%coinName%", "%coinNames%", "%coinLabel%"), array($this->coinName, (in_array($this->coinLabel, array('BCH', 'BSV', 'DASH'))?$this->coinName:$this->coinName.'s'), $this->coinLabel), $this->localisation["button"])." &#160; <i class='fas fa-angle-double-right'></i></button>";
	         $tmp .= "</form>";
	         $tmp .= "</div>";
	     }


	     $tmp .= "</div>";
	     $tmp .= "</div>";

	     // -----------------------------------------------
	     // End Type1 - Payment Box - Payment Not Received
	     // -----------------------------------------------







	     // -------------------------------------------------------------------------
	     // Type2 - Crypto Payment Box - Payment Received/Successful Result
	     // -------------------------------------------------------------------------


	     $tmp .= "<div class='container ".$ext."cryptobox_paid' " . $hide . ">";
	     $tmp .= "<div class='row'>";
	     $tmp .= "<div class='col-12 ".(Config::get("constants.CRYPTOBOX_WORDPRESS")?"col-md-10 offset-md-1":"col-sm-10 offset-sm-1 col-md-8 offset-md-2 text-center")."'>";

	     $tmp .= "<div class='card box-shadow'>";
	     $tmp .= "<div class='card-header'>";

	     $tmp .= "<h4 class='my-0 font-weight-normal ".$ext."addr_title'><span class='".$ext."texts_title'>&#160;</span>";
	     $tmp .= "<span class='".$ext."loading_icon mr-3 float-left' " . $hide . "> <i class='fas fa-laptop'></i></span>";
	     $tmp .= "<span class='".$ext."loading_icon mr-3 float-left' " . $hide . "> <i class='fas fa-sync-alt fa-spin'></i></span>";
	     $tmp .= "</h4>";

	     $tmp .= "</div>";

	     $tmp .= "<div class='card-body'>";

	     $tmp .= "<div class='".$ext."paid_total'>";
	     $tmp .= "<h1 style='margin-top:10px' class='card-title ".$ext."copy_amount'><span class='".$ext."amount'>&#160;</span> <small class='text-muted'><span class='".$ext."coinlabel'></span></small></h1>";
	     $tmp .= "</div>";
	     $tmp .= "<br>";
	     if (!$resultimg_path || $resultimg_path == "default") $resultimg_path = $imgdir_path."paid.png";
	     if ($resultimgSize) $tmp .= "<div class='".$ext."copy_transaction'><img class='".$ext."paidimg' style='max-width: 100%; width: ".$resultimgSize."px; height: auto;' src='".$resultimg_path."' alt='Paid'></div><br><br>";
	     $tmp .= "<h1 class='display-4 ".$ext."paymentcaptcha_successful' style='line-height:1.5;'>.</h1>";
	     $tmp .= "<br>";
	     $tmp .= "<div class='lead ".$ext."paymentcaptcha_date'></div>";
	     $tmp .= "<br>";
	     $tmp .= "<br>";
	     $tmp .= "<a href='#a' class='".$ext."button_details btn btn-lg btn-block btn-outline-primary' style='white-space:normal'></a>";
	     $tmp .= "</div>";

	     $tmp .= "</div>";

	     $tmp .= "</div>";
	     $tmp .= "</div>";
	     $tmp .= "</div>";
	     $tmp .= "<br><br><br>";

	     // -----------------------------------------------
	     // End Type2 - Payment Received/Successful Result
	     // -----------------------------------------------








	    // -------------------------------------------------------------------------------------------
	    // Debug Raw JSON Payment Data from gourl.io
	    // -------------------------------------------------------------------------------------------

	     if ($debug)
	     {

	     $tmp .= "<div class='mncrpt_debug container ".$ext."cryptobox_rawdata px-4 py-3' style='overflow-wrap: break-word; display:none;'>";
	     $tmp .= "<div class='row'>";
	     $tmp .= "<div class='col-12'>";
	     $tmp .= "<br><br><br><br><br>";

	     $tmp .= "<h1 class='display-4'>Raw JSON Data (from GoUrl.io payment gateway) -</h1>";
	     $tmp .= "<br>";
	     $tmp .= "<p class='lead'><b>PHP Language</b> - Please use function <a target='_blank' href='https://github.com/cryptoapi/Payment-Gateway/blob/master/lib/cryptobox.class.php#L754'>\$box->display_cryptobox_bootstrap (...)</a>; it generate customize mobile friendly bitcoin/altcoin payment box and automatically displays successful payment message (bootstrap4, json, your own logo, white label product, etc)</p>";
	     $tmp .= "<p class='lead'><b>ASP/Other Languages</b> - You can use function <a target='_blank' href='https://github.com/cryptoapi/Payment-Gateway/blob/master/lib/cryptobox.class.php#L320'>\$box->cryptobox_json_url()</a>; It generates url with your parameters to gourl.io payment gateway. ";
	     $tmp .= "Using this url you can get bitcoin/altcoin payment box values in JSON format and use it on html page with Jquery/Ajax (on the user side). ";
	     $tmp .= "Or your server can receive JSON values through curl - function <a target='_blank' href='https://github.com/cryptoapi/Payment-Gateway/blob/master/lib/cryptobox.class.php#L374'>\$box->get_json_values()</a>; and use it in your files/scripts directly without javascript when generating the webpage (on the server side).</p>";
	     $tmp .= "<p class='lead'><a target='_blank' href='" . $this->cryptobox_json_url() . "'>JSON data source &#187;</a></p>";

	     $tmp .= "<div class='card card-body bg-light'>";
	     $tmp .= "<div class='".$ext."jsondata'></div>";
	     $tmp .= "</div>";

	     $tmp .= "</div>";
	     $tmp .= "</div>";
	     $tmp .= "</div>";
	     $tmp .= "<br><br><br>";

	     }
	     // ------------------
	     // End Debug
	     // ------------------




	     $tmp .= "</div>";
	     $tmp .= "</div>";
	     // End - <div class='bootstrapiso'>



	     // ---------------------------
	     // Bootstrap4 Template End
	     // ----------------------------


	     return $tmp;

	}














	/*
	 * Other Internal functions
	*/
	private function check_payment($remotedb = false)
	{
		static $already_checked = false;

		$this->paymentID = $diff = 0;

		$obj = $this->run_sql("SELECT paymentID, amount, amountUSD, txConfirmed, txCheckDate, txDate, processed, boxType FROM crypto_payments WHERE boxID = ".intval($this->boxID)." && orderID = '".addslashes($this->orderID)."' && userID = '".addslashes($this->userID)."' ".($this->period=="NOEXPIRY"?"":"&& txDate >= DATE_SUB('".gmdate("Y-m-d H:i:s")."', INTERVAL ".addslashes($this->period).")")." ORDER BY txDate DESC LIMIT 1");

		if ($obj)
		{
			$this->paymentID 		= $obj->paymentID;
			$this->paymentDate 		= $obj->txDate;
			$this->amountPaid 		= $obj->amount;
			$this->amountPaidUSD 	= $obj->amountUSD;
			$this->paid 			= true;
			$this->confirmed 		= $obj->txConfirmed;
			$this->boxType 	= $obj->boxType;
			$this->processed 		= ($obj->processed) ? true : false;
			$diff					=  strtotime(gmdate('Y-m-d H:i:s')) - strtotime($obj->txCheckDate);
		}

		if (!$obj && isset($_POST["cryptobox_live_"]) && $_POST["cryptobox_live_"] == md5($this->iframeID.$this->private_key.$this->userID)) $remotedb = true;

		if (!$already_checked && ((!$obj && $remotedb) || ($obj && !$this->confirmed && ($diff > (10*60) || $diff < 0)))) // if $diff < 0 - user have incorrect time on local computer
		{
			$this->check_payment_live();
			$already_checked = true;
		}

		return true;
	}

	private function check_payment_live()
	{
		$ip		= $this->ip_address();
		$private_key_hash = strtolower(hash("sha512", $this->private_key));
		$hash 	= md5($this->boxID.$private_key_hash.$this->userID.$this->orderID.$this->language.$this->period.$this->ver.$ip);
		$box_status = "";

		$data = array(
				"g" 	=> $private_key_hash,
				"b" 	=> $this->boxID,
				"o"		=> $this->orderID,
				"u"		=> $this->userID,
				"l"		=> $this->language,
				"e"		=> $this->period,
				"t"		=> $this->ver,
				"i"		=> $ip,
				"h"		=> $hash
		);

		$ch = curl_init( "https://coins.gourl.io/result.php" );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt( $ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt( $ch, CURLOPT_USERAGENT, $this->ua());
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt( $ch, CURLOPT_TIMEOUT, 20);
		$res = curl_exec( $ch );
		curl_close($ch);

		if ($res) $res = json_decode($res, true);

		if ($res) foreach ($res as $k => $v) if (is_string($v)) $res[$k] = trim($v);


		if (isset($res["status"]) && in_array($res["status"], array("payment_received")) &&
				$res["box"] && is_numeric($res["box"]) && $res["box"] > 0 && $res["amount"] && is_numeric($res["amount"]) && $res["amount"] > 0 &&
				isset($res["private_key_hash"]) && strlen($res["private_key_hash"]) == 128 && preg_replace('/[^A-Za-z0-9]/', '', $res["private_key_hash"]) == $res["private_key_hash"] && strtolower($res["private_key_hash"]) == $private_key_hash)
		{

			foreach ($res as $k => $v)
			{
				if ($k == "datetime") 							$mask = '/[^0-9\ \-\:]/';
				elseif (in_array($k, array("err", "date")))		$mask = '/[^A-Za-z0-9\.\_\-\@\ ]/';
				else											$mask = '/[^A-Za-z0-9\.\_\-\@]/';
				if ($v && preg_replace($mask, '', $v) != $v) 	$res[$k] = "";
			}

			if (!$res["amountusd"] || !is_numeric($res["amountusd"]))	$res["amountusd"] = 0;
			if (!$res["confirmed"] || !is_numeric($res["confirmed"]))	$res["confirmed"] = 0;



			$dt  = gmdate('Y-m-d H:i:s');
			$obj = $this->run_sql("select paymentID, processed, txConfirmed from crypto_payments where boxID = ".intval($res["box"])." && orderID = '".addslashes($res["order"])."' && userID = '".addslashes($res["user"])."' && txID = '".addslashes($res["tx"])."' && amount = ".floatval($res["amount"])." && addr = '".addslashes($res["addr"])."' limit 1");

			if ($obj)
			{
				$this->paymentID 	= $obj->paymentID;
				$this->processed 	= ($obj->processed) ? true : false;
				$this->confirmed 	= $obj->txConfirmed;

				// refresh
				$sql = "UPDATE 		crypto_payments
						SET 		boxType 			= '".$res["boxtype"]."',
									amount 				= ".$res["amount"].",
									amountUSD			= ".$res["amountusd"].",
									coinLabel			= '".$res["coinlabel"]."',
						 			unrecognised		= 0,
						 			addr				= '".$res["addr"]."',
						 			txDate				= '".$res["datetime"]."',
						 			txConfirmed			= ".$res["confirmed"].",
						 			txCheckDate			= '".$dt."'
						WHERE 		paymentID 			= ".intval($this->paymentID)."
						LIMIT 		1";

                $this->run_sql($sql);

				if ($res["confirmed"] && !$this->confirmed) $box_status = "cryptobox_updated";
			}
			else
			{
				// Save new payment details in local database
				$sql = "INSERT INTO crypto_payments (boxID, boxType, orderID, userID, countryID, coinLabel, amount, amountUSD, unrecognised, addr, txID, txDate, txConfirmed, txCheckDate, recordCreated)
						VALUES (".$res["box"].", '".$res["boxtype"]."', '".$res["order"]."', '".$res["user"]."', '".$res["usercountry"]."', '".$res["coinlabel"]."', ".$res["amount"].", ".$res["amountusd"].", 0, '".$res["addr"]."', '".$res["tx"]."', '".$res["datetime"]."', ".$res["confirmed"].", '$dt', '$dt')";

				$this->paymentID = $this->run_sql($sql);

				$box_status = "cryptobox_newrecord";
			}


			$this->paymentDate 		= $res["datetime"];
			$this->amountPaid 		= $res["amount"];
			$this->amountPaidUSD 	= $res["amountusd"];
			$this->paid 			= true;
			$this->boxType 			= $res["boxtype"];
			$this->confirmed 		= $res["confirmed"];


			/**
			 *  User-defined function for new payment - cryptobox_new_payment(...)
			 *  For example, send confirmation email, update database, update user membership, etc.
			 *  You need to modify file - cryptobox.newpayment.php
			 *  Read more - https://gourl.io/api-php.html#ipn
			 */

			if (in_array($box_status, array("cryptobox_newrecord", "cryptobox_updated")) && function_exists('cryptobox_new_payment')) cryptobox_new_payment($this->paymentID, $res, $box_status);


			return true;
		}
		return false;
	}
	public function left($str, $findme, $firstpos = true)
	{
		$pos = ($firstpos)? stripos($str, $findme) : strripos($str, $findme);

		if ($pos === false) return $str;
		else return substr($str, 0, $pos);
	}
	public function right($str, $findme, $firstpos = true)
	{
		$pos = ($firstpos)? stripos($str, $findme) : strripos($str, $findme);

		if ($pos === false) return $str;
		else return substr($str, $pos + strlen($findme));
	}
	public function icrc32($str)
	{
		$in = crc32($str);
		$int_max = pow(2, 31)-1;
		if ($in > $int_max) $out = $in - $int_max * 2 - 2;
		else $out = $in;
		$out = abs($out);

		return $out;
	}
	private function ua($agent = true)
	{
	    return (isset($_SERVER['REQUEST_SCHEME'])?$_SERVER['REQUEST_SCHEME']:'http') . '://' . $_SERVER["SERVER_NAME"] . (isset($_SERVER["REDIRECT_URL"])?$_SERVER["REDIRECT_URL"]:$_SERVER["PHP_SELF"]) . ' | GU ' . (Config::get("CRYPTOBOX_WORDPRESS")?'WORDPRESS':'PHP') . ' ' . Config::get("constants.CRYPTOBOX_VERSION") . ($agent && isset($_SERVER["HTTP_USER_AGENT"])?' | '.$_SERVER["HTTP_USER_AGENT"]:'');
	}
	public function ip_address()
	{
		static $ip_address;

		if ($ip_address) return $ip_address;

		$ip_address         = "";
		$proxy_ips          = (defined("PROXY_IPS")) ? unserialize(PROXY_IPS) : array();  // your server internal proxy ip
		$internal_ips       = array('127.0.0.0', '127.0.0.1', '127.0.0.2', '192.0.0.0', '192.0.0.1', '192.168.0.0', '192.168.0.1', '192.168.0.253', '192.168.0.254', '192.168.0.255', '192.168.1.0', '192.168.1.1', '192.168.1.253', '192.168.1.254', '192.168.1.255', '192.168.2.0', '192.168.2.1', '192.168.2.253', '192.168.2.254', '192.168.2.255', '10.0.0.0', '10.0.0.1', '11.0.0.0', '11.0.0.1', '1.0.0.0', '1.0.1.0', '1.1.1.1', '255.0.0.0', '255.0.0.1', '255.255.255.0', '255.255.255.254', '255.255.255.255', '0.0.0.0', '::', '0::', '0:0:0:0:0:0:0:0');

		for ($i = 1; $i <= 2; $i++)
			if (!$ip_address)
			{
				foreach (array('HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP', 'X-Forwarded-For', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'HTTP_X_REAL_IP', 'REMOTE_ADDR') as $header)
					if (!$ip_address && isset($_SERVER[$header]) && $_SERVER[$header])
					{
						$ip  = trim($_SERVER[$header]);
						$ip2 = "";
						if (strpos($ip, ',') !== FALSE)
						{
							list($ip, $ip2) = explode(',', $ip, 2);
							$ip = trim($ip);
							$ip2 = trim($ip2);
						}

						if ($ip && filter_var($ip, FILTER_VALIDATE_IP) && !in_array($ip, $proxy_ips) && ($i==2 || !in_array($ip, $internal_ips))) 				$ip_address = $ip;
						elseif ($ip2 && filter_var($ip2, FILTER_VALIDATE_IP) && !in_array($ip2, $proxy_ips) && ($i==2 || !in_array($ip2, $internal_ips))) 		$ip_address = $ip2;
					}
			}

			if (!$ip_address || !filter_var($ip_address, FILTER_VALIDATE_IP)) $ip_address = '0.0.0.0';

			return $ip_address;
	}

}
