<?php

namespace moderation\commands;

use JsonException;
use moderation\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class BanCommand extends Command {
    private Main $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("tban", "Ban player", "/tban <player> <time> <reason>");
        $this->setPermission("ban");
        $this->plugin = $plugin;
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender->hasPermission($this->plugin->getConfig()->getNested("ban.permission"))) {
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

        if ($this->plugin->getServer()->getPlayerByPrefix($args[0]) instanceof Player) {
            $target = $this->plugin->getServer()->getPlayerByPrefix($args[0]);
            $targetName = $target->getName();
            $sender->sendMessage(str_replace("{player}", $targetName, $this->plugin->getConfig()->get("ban-succes")));
            $reason = $args[2];
            $staff = $sender->getName();
            if ($this->plugin->getConfig()->getNested("ban.in-chat", true)) {
                $this->plugin->getServer()->broadcastMessage((str_replace(["{player}", "{reason}", "{time}", "{staff}"], [$targetName, $reason, $FormatTemp, $staff], $this->plugin->getConfig()->getNested("ban.message-in-chat"))));
            }
            $target->kick((str_replace(["{time}", "{reason}", "{staff}"], [$FormatTemp, $reason, $staff], $this->plugin->getConfig()->getNested("ban.kick-message"))));
            $value = "{$staff}:{$time}:{$reason}";
            $this->plugin->getBanAPI()->insertBan(mb_strtolower($targetName), $value);
        } else {
            $targetName = mb_strtolower($args[0]);
            $sender->sendMessage(str_replace("{player}", $targetName, $this->plugin->getConfig()->get("ban-succes")));
            $reason = $args[2];
            $staff = $sender->getName();
            if ($this->plugin->getConfig()->getNested("ban.in-chat", true)) {
                $this->plugin->getServer()->broadcastMessage((str_replace(["{player}", "{reason}", "{time}", "{staff}"], [$targetName, $reason, $FormatTemp, $staff], $this->plugin->getConfig()->getNested("ban.message-in-chat"))));
            }
            $value = "{$sender->getName()}:{$time}:{$reason}";
            $this->plugin->getBanAPI()->insertBan($targetName, $value);
        }
    }
}
