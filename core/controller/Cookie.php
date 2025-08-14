<?php

/**
 * Cookie manager.
 */
class Cookie {

    /**
     * Cookie name - the name of the cookie.
     * @var bool
     */
    private $name;

    /**
     * Cookie value
     * @var string
     */
    private $value;

    /**
     * Cookie life time
     * @var DateTime
     */
    private $time;

    /**
     * Cookie domain
     * @var bool
     */
    private $domain = false;

    /**
     * Cookie path
     * @var bool
     */
    private $path;

    /**
     * Cookie secure
     * @var bool
     */
    private $secure = false;

    /**
     * Constructor
     */
    public function __construct() {
 $this->time = time() + 3600;
    }

    /**
     * Create or Update cookie.
     */
    public function create() {
        return setcookie($this->getName(), $this->getValue(), $this->getTime(), $this->getPath(), $this->getDomain(), $this->getSecure(), true);
    }

    /**
     * Return a cookie
     * @return mixed
     */
    public function get() {
        return $_COOKIE[$this->getName()];
    }

    /**
     * Delete cookie.
     * @return bool
     */
    public function delete() {
        return setcookie($this->name, '', time() - 3600, $this->getPath(), $this->getDomain(), $this->getSecure(), true);
    }

    /**
     * @param $domain
     */
    public function setDomain($domain) {
        $this->domain = $domain;
    }

    /**
     * @return bool
     */
    public function getDomain() {
        return $this->domain;
    }

    /**
     * @param $id
     */
    public function setName($id) {
        $this->name = $id;
    }

    /**
     * @return bool
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $value
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param $path
     */
    public function setPath($path) {
        $this->path = $path;
    }

    /**
     * @return bool
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @param $secure
     */
    public function setSecure($secure) {
        $this->secure = $secure;
    }

    /**
     * @return bool
     */
    public function getSecure() {
        return $this->secure;
    }

    /**
     * @param $time
     */
    public function setTime($time) {
        // Create a date
        $date = new DateTime();
        // Modify it (+1hours; +1days; +20years; -2days etc)
        $date->modify($time);
        // Store the date in UNIX timestamp.
        $this->time = $date->getTimestamp();
    }

    /**
     * @return bool|int
     */
    public function getTime() {
        return $this->time;
    }
}



// Set cookie name
//                                            $this->cookie->setName('ckid');
// Set cookie value
//                                            $this->cookie->setValue($usid);
// Set cookie expiration time
//                                            $this->cookie->setTime("+1hour");
//                                            $this->cookie->setPath("/");
// Create the cookie
// 
//          $this->cookie->create();
// Delete the cookie.
                  //  $this->cookie->delete();
?> 