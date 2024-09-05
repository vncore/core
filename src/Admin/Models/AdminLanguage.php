<?php
#Vncore/Core/Admin/Models/AdminLanguage.php
namespace Vncore\Core\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLanguage extends Model
{
    use \Vncore\Core\Admin\Models\ModelTrait;
    
    public $table = VNCORE_DB_PREFIX.'admin_language';
    protected $guarded                = [];
    private static $getListAll      = null;
    private static $getListActive      = null;
    private static $getArrayLanguages = null;
    private static $getCodeActive = null;
    protected $connection = VNCORE_DB_CONNECTION;

    public static function getListAll()
    {
        if (self::$getListAll === null) {
            self::$getListAll = self::get()
                ->keyBy('code');
        }
        return self::$getListAll;
    }

    public static function getListActive()
    {
        if (self::$getListActive === null) {
            self::$getListActive = self::where('status', 1)
                ->get()
                ->keyBy('code');
        }
        return self::$getListActive;
    }

    public static function getCodeActive()
    {
        if (self::$getCodeActive === null) {
            self::$getCodeActive = self::where('status', 1)
                ->pluck('name', 'code')
                ->all();
        }
        return self::$getCodeActive;
    }

    public static function getCodeAll()
    {
        if (self::$getArrayLanguages === null) {
            self::$getArrayLanguages = self::pluck('name', 'code')->all();
        }
        return self::$getArrayLanguages;
    }
    
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            if (in_array($model->id, VNCORE_GUARD_LANGUAGE)) {
                return false;
            }
        });
    }
}
