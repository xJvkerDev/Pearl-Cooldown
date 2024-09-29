<?php

declare(strict_types=1);

namespace xjvkerdevv\Plugin;

use pocketmine\event\Listener;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\entity\projectile\EnderPearl;
use pocketmine\player\Player;

class Main extends PluginBase implements Listener {
    private $cooldowns = [];

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onProjectileLaunch(ProjectileLaunchEvent $event) {
        $projectile = $event->getEntity();
        if ($projectile instanceof EnderPearl) {
            $player = $projectile->getOwningEntity();
            if ($player instanceof Player) {
                $playerName = $player->getName();
                if (!isset($this->cooldowns[$playerName]) || $this->cooldowns[$playerName] <= microtime(true)) {
                    $this->cooldowns[$playerName] = microtime(true) + 10;
                } else {
                    $remainingTime = ceil($this->cooldowns[$playerName] - microtime(true));
                    $player->sendMessage("§8[§6HCF§8] §7» §cTienes que esperar §e{$remainingTime}§c segundos para volver a usar una perla!");
                    $projectile->kill();
                    $event->cancel();
                }
            }
        }
    }
}
