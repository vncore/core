<?php
namespace Vncore\Core\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminHome extends Model
{
    use \Vncore\Core\Admin\Models\ModelTrait;
    
    public $table = VNCORE_DB_PREFIX.'admin_home';
    protected $guarded = [];

    public static function getBlockHome()
    {
        return self::where('status', 1)
            ->orderBy('sort', 'desc')
            ->get();
    }
}
