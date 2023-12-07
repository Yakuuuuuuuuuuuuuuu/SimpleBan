<?php

namespace moderation\commands;

use JsonException;
use moderation\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class UnbanCommand extends Command {
    private Main $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("unban", "Unban a player", "/unban <player>");
        $this->setPermission("ban");
        $this->plugin = $plugin;
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender->hasPermission($this->plugin->getConfig()->getNested("unban.permission"))) {
            $sender->sendMessage($this->plugin->getConfig()->get("no-perm"));
            return;
        }

        if (!isset($args[0])) {
            $sender->sendMessage($this->getUsage());
            return;
        }

        $name = $args[0];
        if ($this->plugin->getBanAPI()->isBannedPerm(mb_strtolower($args[0]))) {
            $this->plugin->getBanAPI()->deleteBanPerm(mb_strtolower($args[0]));
            $sender->sendMessage(str_replace("{name}", $name, $this->plugin->getConfig()->getNested("unban.succes")));
            return;
        }

        if (!$this->plugin->getBanAPI()->isBanned(mb_strtolower($args[0]))) {
            $sender->sendMessage(($this->plugin->getConfig()->get("not-ban")));
            return;
        }

        $this->plugin->getBanAPI()->deleteBan(mb_strtolower($args[0]));
        $sender->sendMessage(str_replace("{name}", $name, $this->plugin->getConfig()->getNested("unban.succes")));
    }
}
