<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['user_id', 'action', 'model', 'model_id', 'data', 'ip_address'];

    public static function log($userId, $action, $model = null, $modelId = null, $data = [])
    {
        self::create([
            'user_id' => $userId,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'data' => json_encode($data),
            'ip_address' => request()->ip(),
        ]);
    }
}