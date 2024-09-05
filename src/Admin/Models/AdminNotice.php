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

        if (session('partner_member_id')) {
            $data = self::where('status', 0)
            ->where(function ($query){
                $query->where('admin_id', admin()->user()->id)
                      ->orWhere('partner_member_id', session('partner_member_id'));
            })
            ->count();
        } else {
            $data = self::where('status', 0)
            ->where('admin_id', admin()->user()->id)
            ->count();
        }
        return $data;
    }

    /**
     * Get count notice new
     *
     * @return  [type]  [return description]
     */
    public static function getTopNotice()
    {
        if (session('partner_member_id')) {
            $data = self::where('admin_id', admin()->user()->id)
            ->orWhere('partner_member_id', session('partner_member_id'));
        } else {
            $data = self::where('admin_id', admin()->user()->id);
        }
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
        if (session('partner_member_id')) {
            $data = self::where('admin_id', admin()->user()->id)
            ->orWhere('partner_member_id', session('partner_member_id'));
        } else {
            $data = self::where('admin_id', admin()->user()->id);
        }
        $data = $data
            ->with('admin')
            ->orderBy('id','desc')
            ->paginate(20);
            
        return $data;
    }
}
