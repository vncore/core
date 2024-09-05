<?php
namespace Vncore\Core\Admin\Models;

use Vncore\Core\Admin\Models\AdminStoreDescription;
use Vncore\Core\Admin\Models\AdminConfig;
use Vncore\Core\Admin\Models\ShopProductStore;
use Illuminate\Database\Eloquent\Model;


class AdminStore extends Model
{   


    public $table = VNCORE_DB_PREFIX.'admin_store';
    protected $guarded = [];
    protected static $getAll = null;
    protected static $getStoreActive = null;
    protected static $getCodeActive = null;
    protected static $getDomainPartner = null;
    protected static $getDomainStore = null;
    protected static $getListAllActive = null;
    protected static $arrayStoreId = null;
    protected static $listStoreId = null;
    protected static $listStoreCode = null;
    protected static $getStoreDomainByCode = null;
    protected $connection = VNCORE_DB_CONNECTION;
    
    public function descriptions()
    {
        return $this->hasMany(AdminStoreDescription::class, 'store_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(AdminStore::class, ShopProductStore::class, 'store_id', 'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(AdminStore::class, ShopCategoryStore::class, 'store_id', 'category_id');
    }

    public function banners()
    {
        return $this->belongsToMany(AdminStore::class, ShopBannerStore::class, 'store_id', 'banner_id');
    }

    public function brands()
    {
        return $this->belongsToMany(AdminStore::class, ShopBrandStore::class, 'store_id', 'brand_id');
    }

    public function news()
    {
        return $this->belongsToMany(AdminStore::class, ShopNewsStore::class, 'store_id', 'news_id');
    }

    public function pages()
    {
        return $this->belongsToMany(AdminStore::class, ShopPageStore::class, 'store_id', 'page_id');
    }

    public function links()
    {
        return $this->belongsToMany(AdminStore::class, ShopLinkStore::class, 'store_id', 'link_id');
    }

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(function ($store) {
            //Store id 1 is default
            if ($store->id == VNCORE_ID_ROOT) {
                return false;
            }
            //Delete store descrition
            $store->descriptions()->delete();
            $store->news()->detach();
            $store->banners()->detach();
            $store->brands()->detach();
            $store->pages()->detach();
            $store->products()->detach();
            $store->categories()->detach();
            $store->links()->detach();
            AdminConfig::where('store_id', $store->id)->delete();
        });

        //Uuid
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = vncore_generate_id($type = 'shop_store');
            }
        });
    }


    /**
     * [getAll description]
     *
     * @return  [type]  [return description]
     */
    public static function getListAll()
    {
        if (self::$getAll === null) {
            self::$getAll = self::with('descriptions')
                ->get()
                ->keyBy('id');
        }
        return self::$getAll;
    }

    /**
     * [getAll active description]
     *
     * @return  [type]  [return description]
     */
    public static function getListAllActive()
    {
        if (self::$getListAllActive === null) {
            self::$getListAllActive = self::with('descriptions')
                ->where('active', 1)
                ->get()
                ->keyBy('id');
        }
        return self::$getListAllActive;
    }


    /**
     * Get all domain and id store is vendor unlock domain
     *
     * @return  [array]  [return description]
     */
    public static function getDomainPartner()
    {
        if (self::$getDomainPartner === null) {
            self::$getDomainPartner = self::where('partner', 1)
                ->whereNotNull('domain')
                ->where('status', 1)
                ->pluck('domain', 'id')
                ->all();
        }
        return self::$getDomainPartner;
    }
    

    /**
     * Get all domain and id store unlock domain
     *
     * @return  [array]  [return description]
     */
    public static function getDomainStore()
    {
        if (self::$getDomainStore === null) {
            self::$getDomainStore = self::whereNotNull('domain')
                ->where('status', 1)
                ->pluck('domain', 'id')
                ->all();
        }
        return self::$getDomainStore;
    }

    /**
     * Get all domain and id store active
     *
     * @return  [array]  [return description]
     */
    public static function getStoreActive()
    {
        if (self::$getStoreActive === null) {
            self::$getStoreActive = self::where('active', 1)
                ->pluck('domain', 'id')
                ->all();
        }
        return self::$getStoreActive;
    }
    

    /**
     * Get all code and id store active
     *
     * @return  [array]  [return description]
     */
    public static function getCodeActive()
    {
        if (self::$getCodeActive === null) {
            self::$getCodeActive = self::where('active', 1)
                ->pluck('code', 'id')
                ->all();
        }
        return self::$getCodeActive;
    }

    /**
     * Get array store ID
     *
     * @return array
     */
    public static function getArrayStoreId()
    {
        if (self::$arrayStoreId === null) {
            self::$arrayStoreId = self::pluck('id')->all();
        }
        return self::$arrayStoreId;
    }

    //Function get text description
    public function getText()
    {
        return $this->descriptions()->where('lang', vncore_get_locale())->first();
    }
    public function getTitle()
    {
        return $this->getText()->title ?? '';
    }
    public function getDescription()
    {
        return $this->getText()->description ?? '';
    }
    public function getKeyword()
    {
        return $this->getText()->keyword?? '';
    }
    //End  get text description

    
    //===========Get infor store======
    /**
     * Get list store ID
     */
    public static function getListStoreId()
    {
        if (self::$listStoreId === null) {
            self::$listStoreId = self::pluck('id', 'code')->all();
        }
        return self::$listStoreId;
    }

    /**
     * Get list store code
     */
    public static function getListStoreCode()
    {
        if (self::$listStoreCode === null) {
            self::$listStoreCode = self::pluck('code', 'id')->all();
        }
        return self::$listStoreCode;
    }

    /**
     * Get all domain and code store active
     *
     * @return  [array]  [return description]
     */
    public static function getStoreDomainByCode()
    {
        if (self::$getStoreDomainByCode === null) {
            self::$getStoreDomainByCode = self::whereNotNull('domain')
                ->pluck('domain', 'code')
                ->all();
        }
        return self::$getStoreDomainByCode;
    }

    /**
     * Get all template used
     *
     * @return  [type]  [return description]
     */
    public static function getAllTemplateUsed()
    {
        return self::pluck('template')->all();
    }

    public static function insertDescription(array $data)
    {
        return AdminStoreDescription::insert($data);
    }

    /**
     * Update description
     *
     * @param   array  $data  [$data description]
     *
     * @return  [type]        [return description]
     */
    public static function updateDescription(array $data)
    {
        $checkDes = AdminStoreDescription::where('store_id', $data['storeId'])
        ->where('lang', $data['lang'])
        ->first();
        if ($checkDes) {
            return AdminStoreDescription::where('store_id', $data['storeId'])
            ->where('lang', $data['lang'])
            ->update([$data['name'] => $data['value']]);
        } else {
            return AdminStoreDescription::insert(
                [
                    'store_id' => $data['storeId'],
                    'lang' => $data['lang'],
                    $data['name'] => $data['value'],
                ]
            );
        }
    }
}
