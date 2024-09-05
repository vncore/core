<?php

namespace Vncore\Core\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminEmailTemplate extends Model
{
    use \Vncore\Core\Admin\Models\ModelTrait;
    use \Vncore\Core\Admin\Models\UuidTrait;
    
    public $table = VNCORE_DB_PREFIX.'shop_email_template';
    protected $guarded = [];
    protected $connection = VNCORE_DB_CONNECTION;
    protected static $getListTitleAdmin = null;
    protected static $getListEmailTemplateGroupByParentAdmin = null;


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
                $model->{$model->getKeyName()} = vncore_generate_id($type = 'shop_email_template');
            }
        });
    }

    /**
     * Get news detail in admin
     *
     * @param   [type]  $id  [$id description]
     *
     * @return  [type]       [return description]
     */
    public static function getEmailTemplateAdmin($id)
    {
        return self::where('id', $id)
        ->where('store_id', session('adminStoreId'))
        ->first();
    }

    /**
     * Get list news in admin
     *
     * @param   [array]  $dataSearch  [$dataSearch description]
     *
     * @return  [type]               [return description]
     */
    public static function getEmailTemplateListAdmin(array $dataSearch)
    {
        $keyword          = $dataSearch['keyword'] ?? '';
        $sort_order       = $dataSearch['sort_order'] ?? '';
        $arrSort          = $dataSearch['arrSort'] ?? '';

        $newsList = (new AdminEmailTemplate)
            ->where('store_id', session('adminStoreId'));

        if ($keyword) {
            $newsList = $newsList->where(function ($sql) {
                $sql->where('name', 'like', '%' . $keyword . '%');
            });
        }

        if ($sort_order && array_key_exists($sort_order, $arrSort)) {
            $field = explode('__', $sort_order)[0];
            $sort_field = explode('__', $sort_order)[1];
            $newsList = $newsList->orderBy($field, $sort_field);
        } else {
            $newsList = $newsList->orderBy('id', 'desc');
        }
        $newsList = $newsList->paginate(20);

        return $newsList;
    }

    /**
     * Create a new news
     *
     * @param   array  $dataCreate  [$dataCreate description]
     *
     * @return  [type]              [return description]
     */
    public static function createEmailTemplateAdmin(array $dataCreate)
    {
        return self::create($dataCreate);
    }
}
