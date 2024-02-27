<?php
class TareasController extends Controller {

    public function accessRules() {
        return array(
            array(
                'allow', // Permite a los usuarios autenticados acceder a la acción
                'actions' => array('sincronizarGastos', 'sincronizarInformes', 'sincronizarRindeGastos'),
                'users' => array('@'),
            ),
            array(
                'deny', // Niega a todos los usuarios el acceso a todas las demás acciones
                'users' => array('*'),
            ),
        );
    }

    public function actionSincronizarGastos() {
        $cronFile = Yii::app()->basePath . '/../cron.php';
        $command = "/usr/local/bin/php $cronFile gastos";
        // $command = "php $cronFile gastos";
        exec($command, $output, $exitCode);

        if ($exitCode === 0) {
            echo CJSON::encode(['status' => 'OK']);
        } else {
            echo CJSON::encode(['status' => 'Error']);
        }
    }


    public function actionSincronizarInformes() {
        $cronFile = Yii::app()->basePath . '/../cron.php';
        $command = "/usr/local/bin/php $cronFile informes";
        // $command = "php $cronFile informes";
        exec($command, $output, $exitCode);

        if ($exitCode === 0) {
            echo CJSON::encode(['status' => 'OK']);
        } else {
            echo CJSON::encode(['status' => 'Error']);
        }
    }

    public function actionSincronizarRindeGastos() {
        $cronFile = Yii::app()->basePath . '/../cron.php';
        $command = "/usr/local/bin/php $cronFile rinde";
        // $command = "php $cronFile rinde";
        exec($command, $output, $exitCode);

        if ($exitCode === 0) {
            echo CJSON::encode(['status' => 'OK']);
        } else {
            echo CJSON::encode(['status' => 'Error']);
        }
    }
}
