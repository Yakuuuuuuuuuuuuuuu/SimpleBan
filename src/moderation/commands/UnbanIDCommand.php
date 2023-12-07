<?php

namespace moderation\commands;

use JsonException;
use moderation\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class UnbanIDCommand extends Command {
    private Main $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("unbanid", "Unban a id player", "/unban <id>");
        $this->setPermission("ban");
        $this->plugin = $plugin;
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!$sender->hasPermission($this->plugin->getConfig()->getNested("unban-id.permission"))) {
             $sender->sendMessage($this->plugin->getConfig()->get("no-perm"));
             return;
         }

        if (!isset($args[0])) {
            $sender->sendMessage($this->getUsage());
            return;
        }

        if (!$this->plugin->getBanAPI()->isBannedID($args[0])) {
            $sender->sendMessage(($this->plugin->getConfig()->get("not-ban")));
            return;
        }
        $id = $args[0];
        $this->plugin->getBanAPI()->deleteBanID($args[0]);
        $sender->sendMessage(str_replace("{id}", $id, $this->plugin->getConfig()->getNested("unban-id.succes")));
    }
}
