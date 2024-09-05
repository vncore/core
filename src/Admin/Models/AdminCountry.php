<?php
namespace Vncore\Core\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class AdminCountry extends Model
{
    use \Vncore\Core\Admin\Models\ModelTrait;
    
    public $table = VNCORE_DB_PREFIX.'admin_country';
    public $timestamps               = false;
    private static $getListCountries = null;
    private static $getCodeAll = null;
    protected $connection = VNCORE_DB_CONNECTION;

    public static function getListAll()
    {
        if (self::$getListCountries === null) {
            self::$getListCountries = self::get()->keyBy('code');
        }
        return self::$getListCountries;
    }

    public static function getCodeAll()
    {
        if (vncore_config_global('cache_status') && vncore_config_global('cache_country')) {
            if (!Cache::has('cache_country')) {
                if (self::$getCodeAll === null) {
                    self::$getCodeAll = self::pluck('name', 'code')->all();
                }
                vncore_cache_set('cache_country', self::$getCodeAll);
            }
            return Cache::get('cache_country');
        } else {
            if (self::$getCodeAll === null) {
                self::$getCodeAll = self::pluck('name', 'code')->all();
            }
            return self::$getCodeAll;
        }
    }
}
