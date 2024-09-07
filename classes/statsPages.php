<?php

function getUserIpAddr() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		//ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		//ip pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

echo 'User Real IP - ' . getUserIpAddr();

class RemoteAddress {

	/**
	 * Whether to use proxy addresses or not.
	 *
	 * As default this setting is disabled - IP address is mostly needed to increase
	 * security. HTTP_* are not reliable since can easily be spoofed. It can be enabled
	 * just for more flexibility, but if user uses proxy to connect to trusted services
	 * it's his/her own risk, only reliable field for IP address is $_SERVER['REMOTE_ADDR'].
	 *
	 * @var bool
	 */
	protected $useProxy = false;

	/**
	 * List of trusted proxy IP addresses
	 *
	 * @var array
	 */
	protected $trustedProxies = array();

	/**
	 * HTTP header to introspect for proxies
	 *
	 * @var string
	 */
	protected $proxyHeader = 'HTTP_X_FORWARDED_FOR';

	// [...]

	/**
	 * Returns client IP address.
	 *
	 * @return string IP address.
	 */
	public function getIpAddress() {
		$ip = $this->getIpAddressFromProxy();
		if ($ip) {
			return $ip;
		}

		// direct IP address
		if (isset($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];
		}

		return '';
	}

	/**
	 * Attempt to get the IP address for a proxied client
	 *
	 * @see http://tools.ietf.org/html/draft-ietf-appsawg-http-forwarded-10#section-5.2
	 * @return false|string
	 */
	protected function getIpAddressFromProxy() {
		if (!$this->useProxy || (isset($_SERVER['REMOTE_ADDR']) && !in_array($_SERVER['REMOTE_ADDR'], $this->trustedProxies))
		) {
			return false;
		}

		$header = $this->proxyHeader;
		if (!isset($_SERVER[$header]) || empty($_SERVER[$header])) {
			return false;
		}

		// Extract IPs
		$ips = explode(',', $_SERVER[$header]);
		// trim, so we can compare against trusted proxies properly
		$ips = array_map('trim', $ips);
		// remove trusted proxy IPs
		$ips = array_diff($ips, $this->trustedProxies);

		// Any left?
		if (empty($ips)) {
			return false;
		}

		// Since we've removed any known, trusted proxy servers, the right-most
		// address represents the first IP we do not know about -- i.e., we do
		// not know if it is a proxy server, or a client. As such, we treat it
		// as the originating IP.
		// @see http://en.wikipedia.org/wiki/X-Forwarded-For
		$ip = array_pop($ips);
		return $ip;
	}

	// [...]
}
