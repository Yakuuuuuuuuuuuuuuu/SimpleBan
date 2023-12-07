<?php

namespace moderation\commands;

use JsonException;
use moderation\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class UnbanIPCommand extends Command {
    private Main $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("unbanip", "Unban a ip player", "/unban <ip>");
        $this->setPermission("ban");
        $this->plugin = $plugin;
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!$sender->hasPermission($this->plugin->getConfig()->getNested("unban-ip.permission"))) {
             $sender->sendMessage($this->plugin->getConfig()->get("no-perm"));
             return;
         }

        if (!isset($args[0])) {
            $sender->sendMessage($this->getUsage());
            return;
        }

        if (!$this->plugin->getBanAPI()->isBannedIP($args[0])) {
            $sender->sendMessage(($this->plugin->getConfig()->get("not-ban")));
            return;
        }
        $ip = $args[0];
        $this->plugin->getBanAPI()->deleteBanIP($args[0]);
        $sender->sendMessage(str_replace("{ip}", $ip, $this->plugin->getConfig()->getNested("unban-ip.succes")));
    }
}
