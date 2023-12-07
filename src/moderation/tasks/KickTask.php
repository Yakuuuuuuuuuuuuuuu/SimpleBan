<?php

namespace moderation\tasks;


use moderation\Main;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;

class KickTask extends Task {
    private Player $player;
    private Main $plugin;
    private int $time = 1;

    public function __construct(Player $player, Main $plugin) {
        $this->player = $player;
        $this->plugin = $plugin;
    }

    public function onRun(): void {
        if ($this->time == 0) {
            $player = $this->player;
            $name   = $player->getName();
            if ($this->plugin->getBanAPI()->isBanned(strtolower($name))) {
                $ban = $this->plugin->getBanAPI()->getBan(strtolower($name));
                $ban = explode(":", $ban);


                $staff  = $ban[0];
                $time   = $ban[1];
                $reason = $ban[2];

                $timeRestant = $time - time();
                $year        = intval(abs($timeRestant / 31536000));
                $timeRestant = $timeRestant - ($year * 31536000);
                $month        = intval(abs($timeRestant / 2635200));
                $timeRestant = $timeRestant - ($month * 2635200);
                $day       = intval(abs($timeRestant / 86400));
                $timeRestant = $timeRestant - ($day * 86400);
                $hour      = intval(abs($timeRestant / 3600));
                $timeRestant = $timeRestant - ($hour * 3600);
                $minute     = intval(abs($timeRestant / 60));
                $second    = intval(abs($timeRestant - $minute * 60));

                if ($year > 0) {
                    $formatTemp = "{$year} year(s)";
                } elseif ($month > 0) {
                    $formatTemp = "{$month} month(s) et {$day} day(s)";
                } elseif ($day > 0) {
                    $formatTemp = "{$day} day(s) et {$hour} hour(s)";
                } elseif ($hour > 0) {
                    $formatTemp = "{$hour} hour(s) et {$minute} minute(s)";
                } elseif ($minute > 0) {
                    $formatTemp = "{$minute} minute(s) et {$second} second(s)";
                } else {
                    $formatTemp = "{$second} second(s)";
                }
                $player->kick((str_replace(["{time}", "{reason}", "{staff}"], [$formatTemp ,$reason, $staff], $this->plugin->getConfig()->getNested("ban.connection-message"))));
                $this->getHandler()->cancel();
            }
        } else {
            $this->time--;
        }
    }
}
