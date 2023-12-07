<?php

namespace moderation\commands;

use JsonException;
use moderation\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class BanPermCommand extends Command {
    private Main $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("banperm", "Ban definitely a player", "/ban <player> <reason>");
        $this->setPermission("ban");
        $this->plugin = $plugin;
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender->hasPermission($this->plugin->getConfig()->getNested("ban-perm.permission"))) {
            $sender->sendMessage($this->plugin->getConfig()->get("no-perm"));
            return;
        }
        if (!isset($args[0]) || !isset($args[1])) {
            $sender->sendMessage($this->getUsage());
            return;
        }
        if (!ctype_alnum($args[1])) {
            $sender->sendMessage($this->getUsage());
            return;
        }

        if ($this->plugin->getServer()->getPlayerByPrefix($args[0]) instanceof Player) {
            $target = $this->plugin->getServer()->getPlayerByPrefix($args[0]);
            $targetName = $target->getName();
            $sender->sendMessage(str_replace("{player}", $targetName, $this->plugin->getConfig()->get("ban-succes")));
            $reason = $args[1];
            $staff = $sender->getName();
            if ($this->plugin->getConfig()->getNested("ban-perm.in-chat", true)) {
                $this->plugin->getServer()->broadcastMessage((str_replace(["{player}", "{reason}","{staff}"], [$targetName, $reason, $staff], $this->plugin->getConfig()->getNested("ban-perm.message-in-chat"))));
            }
            $target->kick((str_replace(["{reason}", "{staff}"], [$reason, $staff], $this->plugin->getConfig()->getNested("ban-perm.kick-message"))));
            $value = "{$staff}:{$reason}";
            $this->plugin->getBanAPI()->insertBanPerm(mb_strtolower($targetName), $value);
        } else {
            $targetName = mb_strtolower($args[0]);
            $sender->sendMessage(str_replace("{player}", $targetName, $this->plugin->getConfig()->get("ban-succes")));
            $reason = $args[1];
            $staff = $sender->getName();
            if ($this->plugin->getConfig()->getNested("ban-perm.in-chat", true)) {
                $this->plugin->getServer()->broadcastMessage((str_replace(["{player}", "{reason}", "{staff}"], [$targetName, $reason, $staff], $this->plugin->getConfig()->getNested("ban-perm.message-in-chat"))));
            }
            $value = "{$staff}:{$reason}";
            $this->plugin->getBanAPI()->insertBanPerm($targetName, $value);
        }
    }
}
