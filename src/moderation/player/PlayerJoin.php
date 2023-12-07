<?php

namespace moderation\player;

use JsonException;
use moderation\Main;
use moderation\tasks\KickIdTask;
use moderation\tasks\KickIpTask;
use moderation\tasks\KickTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerJoin implements Listener {
    private Main $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @throws JsonException
     */
    public function onJoin(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();
        $playername = $player->getName();
        $event->setJoinMessage("");

        if ($this->plugin->getBanAPI()->isBannedIP($player->getNetworkSession()->getIp())) {
            $ban = $this->plugin->getBanAPI()->getBanIP($player->getNetworkSession()->getIp());
            $ban = explode(":", $ban);
            $time = $ban[1];
            if ($time - time() <= 0) {
                $this->plugin->getBanAPI()->deleteBanIP($player->getNetworkSession()->getIp());
            } else {
                $this->plugin->getScheduler()->scheduleRepeatingTask(new KickIpTask($player, $this->plugin), 1);
            }
            return;
        }
        if ($this->plugin->getBanAPI()->isBannedID($player->getPlayerInfo()->getExtraData()["ClientRandomId"])) {
            $ban = $this->plugin->getBanAPI()->getBanID($player->getPlayerInfo()->getExtraData()["ClientRandomId"]);
            $ban = explode(":", $ban);
            $time = $ban[1];
            if ($time - time() <= 0) {
                $this->plugin->getBanAPI()->deleteBanID($player->getPlayerInfo()->getExtraData()["ClientRandomId"]);
            } else {
                $this->plugin->getScheduler()->scheduleRepeatingTask(new KickIdTask($player, $this->plugin), 1);
            }
            return;
        }
        if ($this->plugin->getBanAPI()->isBanned(mb_strtolower($playername))) {
            $ban = $this->plugin->getBanAPI()->getBan(mb_strtolower($playername));
            $ban = explode(":", $ban);
            $time = $ban[1];
            if ($time - time() <= 0) {
                $this->plugin->getBanAPI()->deleteBan(mb_strtolower($playername));
            } else {
                $this->plugin->getScheduler()->scheduleRepeatingTask(new KickTask($player, $this->plugin), 1);
            }
            return;
        }
        if ($this->plugin->getBanAPI()->isBannedPerm(mb_strtolower($playername))) {
            $ban = $this->plugin->getBanAPI()->getBanPerm(strtolower($playername));
            $ban = explode(":", $ban);
            $staff  = $ban[0];
            $reason = $ban[1];
            $player->kick((str_replace(["{reason}", "{staff}"], [$reason, $staff], $this->plugin->getConfig()->getNested("ban-perm.connection-message", false))));
        }
    }
}

