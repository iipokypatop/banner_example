<?php


namespace Task;


class User
{
    /** @var  string */
    protected $ip_address;
    /** @var  string */
    protected $user_agent;
    /** @var  string */
    protected $page_url;
    /** @var  string */
    protected $hash;

    /**
     * User constructor.
     * @param string $ip_address
     * @param string $user_agent
     * @param string $page_url
     */
    public function __construct($ip_address, $user_agent, $page_url)
    {
        $this->ip_address = $ip_address;
        $this->user_agent = $user_agent;
        $this->page_url = $page_url;

        $this->init();
    }

    protected function init()
    {
        $this->hash = $this->hash();
    }

    protected function hash()
    {
        $pats = [$this->ip_address, $this->user_agent, $this->page_url];

        return sha1(join('', $pats));
    }

    /**
     * @param array $server
     * @return static
     */
    public static function createFromServer(array $server)
    {
        // param 'X-Real-IP' manually configured in frontend nginx:
        // fastcgi_param      X-Real-IP        $remote_addr;

        $ip_address = isset($server['X-Real-IP']) ? $server['X-Real-IP'] : null;
        $user_agent = isset($server['HTTP_USER_AGENT']) ? $server['HTTP_USER_AGENT'] : '';
        $page_url = isset($server['REQUEST_URI']) ? $server['REQUEST_URI'] : '';

        $ob = new static($ip_address, $user_agent, $page_url);

        return $ob;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->user_agent;
    }

    /**
     * @return string
     */
    public function getPageUrl()
    {
        return $this->page_url;
    }
}