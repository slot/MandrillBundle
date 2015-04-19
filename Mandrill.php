<?php
/**
 * Created by IntelliJ IDEA.
 * User: svenloth
 * Date: 19.04.15
 * Time: 19:25
 */

namespace Hip\MandrillBundle;


class Mandrill {

    public function __construct($service) {

        $this->service = $service;

    }

    /**
     * @var \Mandrill
     */
    public $service;

    /**
     * Load Mandrill user info
     *
     * return User
     */
    public function userInfo() {

        $userInfo = $this->service->users->info();
        $mapper = new \JsonMapper();
        return $mapper->map($userInfo, new UserInfo());

    }

}