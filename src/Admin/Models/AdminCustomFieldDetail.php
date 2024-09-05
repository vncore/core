<?php
#Vncore/Core/Admin/Models/AdminCustomFieldDetail.php
namespace Vncore\Core\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class AdminCustomFieldDetail extends Model
{
    use \Vncore\Core\Admin\Models\ModelTrait;
    use \Vncore\Core\Admin\Models\UuidTrait;
    
    public $table          = VNCORE_DB_PREFIX.'admin_custom_field_detail';
    protected $connection  = VNCORE_DB_CONNECTION;
    protected $guarded     = [];

    //Function get text description
    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(
            function ($obj) {
                //
            }
        );

        //Uuid
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = vncore_generate_id($type = 'CFD');
            }
        });
    }
}
