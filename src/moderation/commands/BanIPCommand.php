<?php

namespace moderation\commands;

use JsonException;
use moderation\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class BanIPCommand extends Command {
    private Main $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("banip", "Ban ip player", "/banid <name> <time> <reason>", ["ipban"]);
        $this->plugin = $plugin;
        $this->setPermission("ban");
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender->hasPermission($this->plugin->getConfig()->getNested("ban-ip.permission"))) {
            $sender->sendMessage($this->plugin->getConfig()->get("no-perm"));
            return;
        }

        if (!isset($args[0]) || !isset($args[1]) || !isset($args[2])) {
            $sender->sendMessage($this->getUsage());
            return;
        }
        if (!ctype_alnum($args[1])) {
            $sender->sendMessage($this->getUsage());
            return;
        }
        $val = mb_substr($args[1], -1);
        if ($val == "y") {
            $time = time() + ((int) $args[1] * 31536000);
            $FormatTemp = (int) $args[1] . " year(s)";
        } elseif ($val == "M") {
            $time = time() + ((int) $args[1] * 2635200);
            $FormatTemp = (int) $args[1] . " month(s)";
        } elseif ($val == "w") {
            $time = time() + ((int) $args[1] * 604800);
            $FormatTemp = (int) $args[1] . " week(s)";
        } elseif ($val == "d") {
            $time = time() + ((int) $args[1] * 86400);
            $FormatTemp = (int) $args[1] . " day(s)";
        } elseif ($val == "h") {
            $time = time() + ((int) $args[1] * 3600);
            $FormatTemp = (int) $args[1] . " hour(s)";
        } elseif ($val == "m") {
            $time = time() + ((int) $args[1] * 60);
            $FormatTemp = (int) $args[1] . " minute(s)";
        } elseif ($val == "s") {
            $time = time() + ((int) $args[1]);
            $FormatTemp = (int) $args[1] . " second(s)";
        } else {
            $sender->sendMessage($this->getUsage());
            return;
        }

        $target = $this->plugin->getServer()->getPlayerByPrefix($args[0]);
        if (!$target instanceof Player) {
            $sender->sendMessage($this->plugin->getConfig()->get("not-player"));
            return;
        }

        $targetName = $target->getName();
        $sender->sendMessage(str_replace("{player}", $targetName, $this->plugin->getConfig()->get("ban-succes")));
        $reason = $args[2];
        $staff = $sender->getName();

        if ($this->plugin->getConfig()->getNested("ban-ip.in-chat", true)) {
            $this->plugin->getServer()->broadcastMessage((str_replace(["{player}", "{reason}", "{time}", "{staff}"], [$targetName, $reason, $FormatTemp, $staff], $this->plugin->getConfig()->getNested("ban-ip.message-in-chat"))));
        }
        $target->kick((str_replace(["{time}", "{reason}", "{staff}"], [$FormatTemp, $reason, $staff], $this->plugin->getConfig()->getNested("ban-ip.kick-message"))));

        $value = "{$staff}:{$time}:{$reason}";
        $ip = $target->getNetworkSession()->getIp();
        $this->plugin->getBanAPI()->insertBanIP($ip, $value);
    }
}
