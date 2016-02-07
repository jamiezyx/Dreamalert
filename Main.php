namespace MStaffalert;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
class MainClass extends PluginBase implements Listener{
	public function onEnable(){
		$this->getserver()->getPluginManager()->registerEvents($this, $this);
		if(!is_dir($this->getDataFolder())){
			@mkdir($this->getDataFolder());
		}
		$config = new Config($this->getDataFolder()."config.yml", CONFIG::YAML, array(
			"Message" => "Server Administrator: {player} has just logged in!!!.",
		));
	}

	/**
	 * @param PlayerJoinEvent $event
	 *
	 * @priority MONITOR
	 * @ignoreCancelled true
	 */
	public function onJoin(PlayerJoinEvent $event){
		if($event->getPlayer()->isOp()){
			$name = $event->getPlayer()->getDisplayName();
			$config = new Config($this->getDataFolder()."config.yml", CONFIG::YAML);
			$message = $config->get("Message");
			$message = str_replace("{player}", $name, $message);
			$this->getServer()->broadcastMessage(TextFormat::RED.$message);
		}
	}
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		switch($command->getName()){
			case "adminjoin":
				if(!isset($args[0])){
					$sender->sendMessage(TextFormat::GREEN."Usage: ".$command->getUsage()."");
					return true;
				}
				$args = implode(" ", $args);
				unlink($this->getDataFolder()."config.yml");
				$config = new Config($this->getDataFolder()."Config.yml", CONFIG::YAML, array(
					"Message" => $args,
				));
				$sender->sendMessage(TextFormat::YELLOW."[MStaffalert] The message have been successfully");
				return true;
		}
	}
