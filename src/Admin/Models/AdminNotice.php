<?php
namespace Vncore\Core\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotice extends Model
{
    public $table = VNCORE_DB_PREFIX.'admin_notice';
    protected $guarded = [];
    protected $connection = VNCORE_DB_CONNECTION;

    public function admin()
    {
        return $this->hasOne(AdminUser::class, 'id', 'admin_created');
    }

    /**
     * Get count notice new
     *
     * @return  [type]  [return description]
     */
    public static function getCountNoticeNew()
    {
        $data = self::where('status', 0)
        ->where('admin_id', admin()->user()->id)
        ->count();
        return $data;
    }

    /**
     * Get count notice new
     *
     * @return  [type]  [return description]
     */
    public static function getTopNotice()
    {
        $data = self::where('admin_id', admin()->user()->id);
        $data = $data->limit(10)
            ->orderBy('id','desc')
            ->get();

        return $data;
    }

    
    /**
     * [getNoticeListAdmin description]
     *
     * @return  [type]  [return description]
     */
    public function getNoticeListAdmin()
    {
        $data = self::where('admin_id', admin()->user()->id);
        $data = $data
            ->with('admin')
            ->orderBy('id','desc')
            ->paginate(20);
            
        return $data;
    }
}
