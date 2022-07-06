<?php
namespace app\utils\logs;

use app\models\helpers\Logs as Log;

class Logs
{
    public function save($table=null, $activity=null, $data_before=null, $data_inserted=null){
        try{
            $model = new Log();
            $model->table = $table;
            $model->activity = $activity;
            $model->data_before = $data_before;
            $model->data_inserted = $data_inserted; 
            if(!$model->save()):
                throw new Exception("oups error");
            endif;
            return true;
        }
        catch(Exception $e){
            return true;
        }
    }
}