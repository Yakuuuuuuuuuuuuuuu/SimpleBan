<?php

namespace moderation;

use moderation\api\BanAPI;
use moderation\commands\BanCommand;
use moderation\commands\BanIDCommand;
use moderation\commands\BanIPCommand;
use moderation\commands\BanPermCommand;
use moderation\commands\UnbanCommand;
use moderation\commands\UnbanIDCommand;
use moderation\commands\UnbanIPCommand;
use moderation\player\PlayerJoin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase {

    use SingletonTrait;
    public function onEnable(): void {
        self::setInstance($this);
        $this->saveDefaultConfig();
        $this->saveResource("BanIP.json");
        $this->saveResource("Ban.json");
        $this->saveResource("BanID.json");
        $this->saveResource("BanPerm.json");

        $this->getServer()->getCommandMap()->registerAll("", [
                new BanCommand($this),
                new BanPermCommand($this),
                new BanIPCommand($this),
                new BanIDCommand($this),
                new UnbanCommand($this),
                new UnbanIPCommand($this),
                new UnbanIDCommand($this)

        ]);

        $this->getServer()->getPluginManager()->registerEvents(new PlayerJoin($this), $this);
    }

    public function onDisable(): void {
        $this->saveDefaultConfig();
        $this->saveResource("BanIP.json");
        $this->saveResource("Ban.json");
        $this->saveResource("BanID.json");
        $this->saveResource("BanPerm.json");
    }

    public function getBanAPI() : BanAPI {
        return new BanAPI($this);
    }
}
