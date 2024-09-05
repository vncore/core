<?php
#Vncore/Core/Admin/Models/AdminCustomField.php
namespace Vncore\Core\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Vncore\Core\Admin\Models\AdminCustomFieldDetail;

class AdminCustomField extends Model
{
    use \Vncore\Core\Admin\Models\ModelTrait;
    use \Vncore\Core\Admin\Models\UuidTrait;
    
    public $table          = VNCORE_DB_PREFIX.'admin_custom_field';
    protected $connection  = VNCORE_DB_CONNECTION;
    protected $guarded     = [];

    public function details()
    {
        $data  = (new AdminCustomFieldDetail)->where('custom_field_id', $this->id)
            ->get();
        return $data;
    }

    /**
     * Get custom fields
     */
    public function getCustomField($type)
    {
        return $this->where('type', $type)
            ->where('status', 1)
            ->get();
    }

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
                $model->{$model->getKeyName()} = vncore_generate_id($type = 'admin_custom_field');
            }
        });
    }
}
