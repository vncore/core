<?php
#Vncore/Core/Admin/Models/AdminStoreDescription.php
namespace Vncore\Core\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminStoreDescription extends Model
{
    use \Vncore\Core\Admin\Models\ModelTrait;
    
    protected $primaryKey = ['lang', 'store_id'];
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;
    public $table = VNCORE_DB_PREFIX.'admin_store_description';
    protected $connection = VNCORE_DB_CONNECTION;
}
