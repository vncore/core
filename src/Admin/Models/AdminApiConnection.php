<?php
namespace Vncore\Core\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminApiConnection extends Model
{
    public $table = VNCORE_DB_PREFIX.'api_connection';
    protected $guarded = [];
    protected $connection = VNCORE_DB_CONNECTION;
    protected static $getGroup = null;

    public static function check($apiconnection, $apikey)
    {
        return self::where('apikey', $apikey)
                    ->where('apiconnection', $apiconnection)
                    ->where(function ($query) {
                        $query->whereNull('expire')
                              ->orWhere('expire', '>=', date('Y-m-d'));
                    })
                    ->where('status', 1)
                    ->first();
    }
}
