<?php

namespace moderation\api;

use JsonException;
use moderation\Main;
use pocketmine\utils\Config;

class BanAPI {
    private Main $plugin;
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @throws JsonException
     */
    public function insertBan($name, $value) : void {
        $config = new Config($this->plugin->getDataFolder() . "Ban.json", Config::JSON);
        $config->set($name, $value);
        $config->save();
    }

    public function isBanned($name) : bool {
        $config = new Config($this->plugin->getDataFolder() . "Ban.json", Config::JSON);

        return $config->exists($name);
    }

    /**
     * @throws JsonException
     */
    public function deleteBan($name) : void {
        $config = new Config($this->plugin->getDataFolder() . "Ban.json", Config::JSON);
        $config->remove($name);
        $config->save();
    }

    public function getBan($name) {
        $config = new Config($this->plugin->getDataFolder() . "Ban.json", Config::JSON);

        return $config->get($name);
    }

    /**
     * @throws JsonException
     */
    public function insertBanPerm($name, $value) : void {
        $config = new Config($this->plugin->getDataFolder() . "BanPerm.json", Config::JSON);
        $config->set($name, $value);
        $config->save();
    }

    public function isBannedPerm($name) : bool {
        $config = new Config($this->plugin->getDataFolder() . "BanPerm.json", Config::JSON);

        return $config->exists($name);
    }

    /**
     * @throws JsonException
     */
    public function deleteBanPerm($name) : void {
        $config = new Config($this->plugin->getDataFolder() . "BanPerm.json", Config::JSON);
        $config->remove($name);
        $config->save();
    }

    public function getBanPerm($name) {
        $config = new Config($this->plugin->getDataFolder() . "BanPerm.json", Config::JSON);

        return $config->get($name);
    }

    /**
     * @throws JsonException
     */
    public function insertBanIP($ip, $value) : void {
        $config = new Config($this->plugin->getDataFolder() . "BanIP.json", Config::JSON);
        $config->set($ip, $value);
        $config->save();
    }

    public function isBannedIP($ip) : bool {
        $config = new Config($this->plugin->getDataFolder() . "BanIP.json", Config::JSON);

        return $config->exists($ip);
    }

    /**
     * @throws JsonException
     */
    public function deleteBanIP($ip) : void {
        $config = new Config($this->plugin->getDataFolder() . "BanIP.json", Config::JSON);
        $config->remove($ip);
        $config->save();
    }

    public function getBanIP($ip) {
        $config = new Config($this->plugin->getDataFolder() . "BanIP.json", Config::JSON);

        return $config->get($ip);
    }

    /**
     * @throws JsonException
     */
    public function insertBanID($id, $value) : void {
        $config = new Config($this->plugin->getDataFolder() . "BanID.json", Config::JSON);
        $config->set($id, $value);
        $config->save();
    }

    public function isBannedID($id) : bool {
        $config = new Config($this->plugin->getDataFolder() . "BanID.json", Config::JSON);

        return $config->exists($id);
    }

    /**
     * @throws JsonException
     */
    public function deleteBanID($id) : void {
        $config = new Config($this->plugin->getDataFolder() . "BanID.json", Config::JSON);
        $config->remove($id);
        $config->save();
    }

    public function getBanID($id) {
        $config = new Config($this->plugin->getDataFolder() . "BanID.json", Config::JSON);

        return $config->get($id);
    }
}

