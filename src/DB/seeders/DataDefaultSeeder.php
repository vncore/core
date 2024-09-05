<?php

namespace Vncore\Core\DB\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DataDefaultSeeder extends Seeder
{
    public $adminUser = 'admin';
    public $adminPassword = '$2y$10$JcmAHe5eUZ2rS0jU1GWr/.xhwCnh2RU13qwjTPcqfmtZXjZxcryPO';
    public $adminEmail = 'admin@vncore.local';
    public $language_default = 'en';
    public $title_en = 'Demo VnCore CMS';
    public $title_vi = 'Demo VnCore CMS';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Preparing update data version
        $this->updateDataVersion();

        $db = DB::connection(VNCORE_DB_CONNECTION);
        $dataMenu = $this->dataMenu();
        $db->table(VNCORE_DB_PREFIX.'admin_menu')->insertOrIgnore($dataMenu);
        
        $dataAdminPermission = $this->dataAdminPermission(VNCORE_ADMIN_PREFIX);
        $db->table(VNCORE_DB_PREFIX.'admin_permission')->insertOrIgnore($dataAdminPermission);
        
        $dataAdminRole = $this->dataAdminRole();
        $db->table(VNCORE_DB_PREFIX.'admin_role')->insertOrIgnore($dataAdminRole);
        
        $dataAdminRolePermission = $this->dataAdminRolePermission();
        $db->table(VNCORE_DB_PREFIX.'admin_role_permission')->insertOrIgnore($dataAdminRolePermission);

        $dataAdminRoleUser = $this->dataAdminRoleUser();
        $db->table(VNCORE_DB_PREFIX.'admin_role_user')->insertOrIgnore($dataAdminRoleUser);

        $dataAdminUser = $this->dataAdminUser($this->adminUser, $this->adminPassword, $this->adminEmail);
        $db->table(VNCORE_DB_PREFIX.'admin_user')->insertOrIgnore($dataAdminUser);

        $dataAdminConfig = $this->dataAdminConfig();
        $db->table(VNCORE_DB_PREFIX.'admin_config')->insertOrIgnore($dataAdminConfig);

        $dataAdminStore = $this->dataAdminStore($this->adminEmail, $this->language_default, str_replace(['http://','https://'], '', url('/')));
        $db->table(VNCORE_DB_PREFIX.'admin_store')->insertOrIgnore($dataAdminStore);

        $dataAdminStoreDescription = $this->dataAdminStoreDescription($this->title_en, $this->title_vi);
        $db->table(VNCORE_DB_PREFIX.'admin_store_description')->insertOrIgnore($dataAdminStoreDescription);

        $dataShopLang = $this->dataShopLang();
        $db->table(VNCORE_DB_PREFIX.'admin_language')->insertOrIgnore($dataShopLang);

        $db->table(VNCORE_DB_PREFIX.'admin_home')->insertOrIgnore(
            [
                ['size' => 12, 'sort'=> 0,'view' => 'vncore-admin::component.home_footer','status' => 1],
                ['size' => 12, 'sort'=> 1,'view' => 'vncore-admin::component.home_default','status' => 1],
            ]
        );

    }

    public function dataMenu() {
        $dataMenu = [
            //Root
            ['id' => 1,'parent_id' => 0,'sort' => 10,'title' => 'admin.menu_titles.ADMIN_WEBSITE','icon' => 'fas fa-store-alt','uri' => '','key' => 'ADMIN_WEBSITE','type' => 0],
            ['id' => 2,'parent_id' => 0,'sort' => 20,'title' => 'admin.menu_titles.ADMIN_SYSTEM','icon' => 'fas fa-cogs','uri' => '','key' => 'ADMIN_SYSTEM','type' => 0],
            ['id' => 3,'parent_id' => 0,'sort' => 30,'title' => 'admin.menu_titles.ADMIN_EXTENSION','icon' => 'fas fa-th','uri' => '','key' => 'ADMIN_EXTENSION','type' => 0],

            //Management system
            ['id' => 6,'parent_id' => 1,'sort' => 1,'title' => 'admin.menu_titles.store_info','icon' => 'fab fa-shirtsinbulk','uri' => 'admin::store_info','key' => null,'type' => 0],
            ['id' => 7,'parent_id' => 1,'sort' => 2,'title' => 'admin.menu_titles.store_config','icon' => 'fas fa-cog','uri' => 'admin::store_config','key' => null,'type' => 0],
            ['id' => 8,'parent_id' => 1,'sort' => 3,'title' => 'admin.menu_titles.store_maintain','icon' => 'fas fa-wrench','uri' => 'admin::store_maintain','key' => null,'type' => 0],


            //Config system
            ['id' => 9,'parent_id' => 2,'sort' => 2,'title' => 'admin.menu_titles.admin_global','icon' => 'fab fa-whmcs','uri' => '','key' => 'ADMIN_GLOBAL','type' => 0],
            ['id' => 10,'parent_id' => 2,'sort' => 7,'title' => 'admin.menu_titles.api_manager','icon' => 'fas fa-plug','uri' => '','key' => 'ADMIN_API_MANAGER','type' => 0],
            ['id' => 11,'parent_id' => 2,'sort' => 5,'title' => 'admin.menu_titles.localisation','icon' => 'fa fa-map-signs','uri' => '','key' => 'ADMIN_LOCAL','type' => 0],
            ['id' => 12,'parent_id' => 2,'sort' => 1,'title' => 'admin.menu_titles.user_permission','icon' => 'fas fa-users-cog','uri' => '','key' => 'ADMIN_PERMISSION','type' => 0],
            ['id' => 13,'parent_id' => 2,'sort' => 6,'title' => 'admin.menu_titles.security','icon' => 'fas fa-shield-alt','uri' => '','key' => 'ADMIN_SECURITY','type' => 0],

            ['id' => 14,'parent_id' => 9,'sort' => 0,'title' => 'admin.menu_titles.menu','icon' => 'fas fa-bars','uri' => 'admin::menu','key' => null,'type' => 0],
            ['id' => 15,'parent_id' => 9,'sort' => 5,'title' => 'admin.menu_titles.cache_manager','icon' => 'fab fa-tripadvisor','uri' => 'admin::cache_config','key' => null,'type' => 0],
            ['id' => 16,'parent_id' => 9,'sort' => 5,'title' => 'admin.menu_titles.admin_home_config','icon' => 'fas fa-grip-horizontal','uri' => 'admin::admin_home_config','key' => null,'type' => 0],

            ['id' => 17,'parent_id' => 13,'sort' => 2,'title' => 'admin.menu_titles.webhook','icon' => 'fas fa-project-diagram','uri' => 'admin::config/webhook','key' => null,'type' => 0],
            ['id' => 18,'parent_id' => 13,'sort' => 0,'title' => 'admin.menu_titles.operation_log','icon' => 'fas fa-history','uri' => 'admin::log','key' => null,'type' => 0],
            ['id' => 19,'parent_id' => 13,'sort' => 3,'title' => 'admin.menu_titles.password_policy','icon' => 'fa fa-unlock','uri' => 'admin::password_policy','key' => null,'type' => 0],

            ['id' => 20,'parent_id' => 12,'sort' => 0,'title' => 'admin.menu_titles.users','icon' => 'fas fa-users','uri' => 'admin::user','key' => null,'type' => 0],
            ['id' => 21,'parent_id' => 12,'sort' => 0,'title' => 'admin.menu_titles.roles','icon' => 'fas fa-user-tag','uri' => 'admin::role','key' => null,'type' => 0],
            ['id' => 22,'parent_id' => 12,'sort' => 0,'title' => 'admin.menu_titles.permission','icon' => 'fas fa-ban','uri' => 'admin::permission','key' => null,'type' => 0],

            ['id' => 23,'parent_id' => 11,'sort' => 1,'title' => 'admin.menu_titles.language','icon' => 'fas fa-language','uri' => 'admin::language','key' => null,'type' => 0],
            ['id' => 24,'parent_id' => 11,'sort' => 2,'title' => 'admin.menu_titles.language_manager','icon' => 'fa fa-universal-access','uri' => 'admin::language_manager','key' => null,'type' => 0],

            ['id' => 25,'parent_id' => 10,'sort' => 1,'title' => 'admin.menu_titles.api_config','icon' => 'fas fa fa-cog','uri' => 'admin::api_connection','key' => null,'type' => 0],


            //Extension
            ['id' => 4,'parent_id' => 3,'sort' => 1,'title' => 'admin.menu_titles.template_layout','icon' => 'fab fa-windows','uri' => 'admin::template','key' => 'TEMPLATE','type' => 0],
            ['id' => 5,'parent_id' => 3,'sort' => 2,'title' => 'admin.menu_titles.plugin','icon' => 'fas fa-puzzle-piece','uri' => 'admin::plugin','key' => 'PLUGIN','type' => 0],

        ];
        return $dataMenu;
    }

    public function dataAdminPermission($prefix) {
        $dataAdminPermission = [
            ['id' => '1','name' => 'Dashboard','slug' => 'dashboard','http_uri' => 'GET::'.$prefix, 'created_at' => date('Y-m-d H:i:s')],
            ['id' => '2','name' => 'Store manager','slug' => 'store.full','http_uri' => 'ANY::'.$prefix.'/store_info/*,ANY::'.$prefix.'/store_maintain/*,ANY::'.$prefix.'/store_config/*', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => '3','name' => 'File manager','slug' => 'file.full','http_uri' => 'ANY::'.$prefix.'/uploads/*', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => '4','name' => 'Config dashboard','slug' => 'dashboard.full','http_uri' => 'ANY::'.$prefix.'/admin_home_config/*', 'created_at' => date('Y-m-d H:i:s')],
        ];
        return $dataAdminPermission;
    }

    public function dataAdminRole() {
        $dataAdminRole = [
            ['id' => '1','name' => 'Administrator','slug' => 'administrator', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => '2','name' => 'Group only View','slug' => 'view.all', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => '3','name' => 'Content Manager','slug' => 'manager_content', 'created_at' => date('Y-m-d H:i:s')],
        ];
        return $dataAdminRole;
    }


    public function dataAdminRolePermission() {
        $dataAdminRolePermission = [
            ['role_id' => 3,'permission_id' => 1],
            ['role_id' => 3,'permission_id' => 2],
            ['role_id' => 3,'permission_id' => 3],
            ['role_id' => 3,'permission_id' => 4],
        ];
        return $dataAdminRolePermission;
    }

    public function dataAdminRoleUser() {
        $dataAdminRoleUser = [
            ['role_id' => '1','user_id' => 'AU-AAAAA']
        ];
        return $dataAdminRoleUser;
    }

    public function dataAdminUser($username, $password, $email) {
        $dataAdminUser = [
            ['id' => 'AU-AAAAA','username' => $username,'password' => $password,'email' => $email,'name' => 'Administrator','avatar' => '/Vncore/Admin/avatar/admin.png','created_at' => date('Y-m-d H:i:s')]
        ];
        return $dataAdminUser;
    }

    public function dataAdminConfig() {
        $dataAdminConfig = [
            ['group' => 'global','code' => 'webhook_config','key' => 'LOG_SLACK_WEBHOOK_URL','value' => '','sort' => '0','detail' => 'admin.config.LOG_SLACK_WEBHOOK_URL','store_id' => 0],
            ['group' => 'global','code' => 'webhook_config','key' => 'GOOGLE_CHAT_WEBHOOK_URL','value' => '','sort' => '0','detail' => 'admin.config.GOOGLE_CHAT_WEBHOOK_URL','store_id' => 0],
            ['group' => 'global','code' => 'webhook_config','key' => 'CHATWORK_CHAT_WEBHOOK_URL','value' => '','sort' => '0','detail' => 'admin.config.CHATWORK_CHAT_WEBHOOK_URL','store_id' => 0],
            ['group' => 'global','code' => 'api_config','key' => 'api_connection_required','value' => '0','sort' => '1','detail' => 'api_connection.api_connection_required','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_status','value' => '0','sort' => '0','detail' => 'admin.cache.cache_status','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_time','value' => '600','sort' => '0','detail' => 'admin.cache.cache_time','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_category','value' => '0','sort' => '3','detail' => 'admin.cache.cache_category','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_product','value' => '0','sort' => '4','detail' => 'admin.cache.cache_product','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_news','value' => '0','sort' => '5','detail' => 'admin.cache.cache_news','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_category_cms','value' => '0','sort' => '6','detail' => 'admin.cache.cache_category_cms','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_content_cms','value' => '0','sort' => '7','detail' => 'admin.cache.cache_content_cms','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_page','value' => '0','sort' => '8','detail' => 'admin.cache.cache_page','store_id' => 0],
            ['group' => 'global','code' => 'cache','key' => 'cache_country','value' => '0','sort' => '10','detail' => 'admin.cache.cache_country','store_id' => 0],
            ['group' => 'global','code' => 'env_mail','key' => 'smtp_mode','value' => '','sort' => '0','detail' => 'email.smtp_mode','store_id' => 0],
            ['group' => 'global','code' => 'password_policy','key' => 'customer_password_min','value' => '6','sort' => '0','detail' => 'password_policy.customer.min','store_id' => 0],
            ['group' => 'global','code' => 'password_policy','key' => 'customer_password_max','value' => '16','sort' => '0','detail' => 'password_policy.customer.max','store_id' => 0],
            ['group' => 'global','code' => 'password_policy','key' => 'customer_password_letter','value' => '0','sort' => '1','detail' => 'password_policy.customer.letter','store_id' => 0],
            ['group' => 'global','code' => 'password_policy','key' => 'customer_password_mixedcase','value' => '0','sort' => '2','detail' => 'password_policy.customer.mixed','store_id' => 0],
            ['group' => 'global','code' => 'password_policy','key' => 'customer_password_number','value' => '0','sort' => '3','detail' => 'password_policy.customer.number','store_id' => 0],
            ['group' => 'global','code' => 'password_policy','key' => 'customer_password_symbol','value' => '0','sort' => '4','detail' => 'password_policy.customer.symbol','store_id' => 0],
        ];
        return $dataAdminConfig;
    }

    public function dataAdminStore($email, $language, $domain) {
        $dataAdminStore = [
            ['id' => 1,'logo' => '/Vncore/Admin/logo/vncore-mid.png', 'icon' => '/Vncore/Admin/logo/icon.png', 'og_image' => '/Vncore/Admin/logo/vncore-mid.png', 'phone' => '0123456789','long_phone' => 'Support: 0987654321','email' => $email,'time_active' =>'','address' => '123st - abc - xyz','language' => $language,'currency' => 'USD','code' => 'vncore','domain' => $domain]
        ];
        return $dataAdminStore;
    }

    public function dataAdminStoreDescription($titleE, $titleV) {
        $dataAdminStoreDescription = [
            ['store_id' => VNCORE_ID_ROOT,'lang' => 'en','title' => $titleE,'description' => '','keyword' => '','maintain_content' => '<center><img src="/Vncore/Admin/images/maintenance.jpg" />
            <h3><span style="color:#e74c3c;"><strong>Sorry! We are currently doing site maintenance!</strong></span></h3>
            </center>','maintain_note' => 'Website is in maintenance mode!'],
            ['store_id' => VNCORE_ID_ROOT,'lang' => 'vi','title' => $titleV,'description' => '','keyword' => '','maintain_content' => '<center><img src="/Vncore/Admin/images/maintenance.jpg" />
            <h3><span style="color:#e74c3c;"><strong>Xin lỗi! Hiện tại website đang bảo trì!</strong></span></h3>
            </center>','maintain_note' => 'Website đang trong chế độ bảo trì!'],
        ];
        return $dataAdminStoreDescription;
    }

    public function dataShopLang() {
        $dataShopLang = [
            ['id' => '1','name' => 'English','code' =>'en','icon' => '/Vncore/Admin/language/flag_uk.png','status' => '1','rtl' => '0', 'sort' => '1'],
            ['id' => '2','name' => 'Tiếng Việt','code' => 'vi','icon' => '/Vncore/Admin/language/flag_vn.png','status' => '1','rtl' => '0', 'sort' => '2'],
        ];
        return $dataShopLang;
    }

    public function updateDataVersion() {

    }

}
