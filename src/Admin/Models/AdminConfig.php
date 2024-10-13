<?php
namespace Vncore\Core\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminConfig extends Model
{
    public $table = VNCORE_DB_PREFIX.'admin_config';
    protected $guarded = [];

    protected static $getAllGlobal = null;
    protected static $getAllConfigOfStore = null;
    protected $connection = VNCORE_DB_CONNECTION;

    /**
     * get Plugin installed
     * @param  boolean $onlyActive
     * @return [type]              [description]
     */
    public static function getPluginCode($onlyActive = true)
    {
        $query =  self::where('group', 'Plugins');
        if ($onlyActive) {
            $query = $query->where('value', 1);
        }
        $data = $query->orderBy('sort', 'asc')
            ->get()->keyBy('key');
        return $data;
    }

    /**
     * get Templates installed
     * @param  boolean $onlyActive
     * @return [type]              [description]
     */
    public static function getTemplateCode($onlyActive = true)
    {
        $query =  self::where('group', 'Templates');
        if ($onlyActive) {
            $query = $query->where('value', 1);
        }
        $data = $query->orderBy('sort', 'asc')
            ->get()->keyBy('key');
        return $data;
    }

    /**
     * get Plugin Captcha installed
     * @param  boolean $onlyActive
     * @return [type]              [description]
     */
    public static function getPluginCaptchaCode($onlyActive = true)
    {
        $query =  self::where('group', 'Plugins')
        ->where('code', 'Other')
        ->where('key', 'like', '%Captcha');
        if ($onlyActive) {
            $query = $query->where('value', 1);
        }
        $data = $query->orderBy('sort', 'asc')
            ->get()->keyBy('key');
        return $data;
    }


    /**
     * get Group
     * @param  [string]  $group
     * @param  [string]  $suffix
     * @return [type]              [description]
     */
    public static function getGroup($group = null, $suffix = null):array
    {
        if ($group === null) {
            return [];
        }
        $return =  self::where('group', $group);
        if ($suffix) {
            $return = $return->orWhere('group', $group.'__'.$suffix);
        }
        $return = $return->orderBy('sort', 'desc')->pluck('value')->all();
        if ($return) {
            return $return;
        } else {
            return [];
        }
    }


    /**
     * [getAllGlobal description]
     *
     * @return  [type]  [return description]
     */
    public static function getAllGlobal():array
    {
        if (self::$getAllGlobal === null) {
            self::$getAllGlobal = self::where('store_id', VNCORE_ID_GLOBAL)
                ->pluck('value', 'key')
                ->all();
        }
        return self::$getAllGlobal;
    }

    /**
     * [getAllConfigOfStore description]
     *
     * @param   [type]  $storeId  [$storeId description]
     *
     * @return  [type]            [return description]
     */
    public static function getAllConfigOfStore($storeId):array
    {
        if (self::$getAllConfigOfStore === null) {
            self::$getAllConfigOfStore = self::where('store_id', $storeId)
                ->pluck('value', 'key')
                ->all();
        }
        return self::$getAllConfigOfStore;
    }

    /**
     * [getListConfigByCode description]
     *
     * @param   [array]$code     [$code description]
     *
     * @return  [type]         [return description]
     */
    public static function getListConfigByCode(array $dataQuery)
    {
        if (empty($dataQuery['code'])) {
            return null;
        }
        if (is_array($dataQuery['code'])) {
            $data = self::whereIn('code', $dataQuery['code']);
        } else {
            $data = self::where('code', $dataQuery['code']);
        }
        $storeId = $dataQuery['storeId'] ?? "";
        $sort    = $dataQuery['sort'] ?? 'desc';
        $groupBy = $dataQuery['groupBy'] ?? null;
        $keyBy   = $dataQuery['keyBy'] ?? null;
        $data = $data->where('store_id', $storeId)
            ->orderBy('sort', $sort)
            ->get();
        if ($groupBy) {
            $data = $data->groupBy($groupBy);
        } elseif ($keyBy) {
            $data = $data->keyBy($keyBy);
        }
        return $data;
    }
}
