<?php
class InformesCommand extends CConsoleCommand {
    
    public function run($args) {
		set_time_limit(0);
		$carga = new Carga();
		$carga->informes();
    }
}