<?php
class RindeCommand extends CConsoleCommand {
    
    public function run($args) {
		set_time_limit(0);
		$carga = new Carga();
		$carga->rindeGastos();
    }
}