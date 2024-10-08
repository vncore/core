<?php

namespace Vncore\Core\DB\seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataStoreSeeder extends Seeder
{
    public function getTemplateDefault() {
        return  empty(session('lastStoreTemplate')) ? 'vncore-light' : session('lastStoreTemplate');
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $storeId = empty(session('lastStoreId')) ? VNCORE_ID_ROOT : session('lastStoreId');

        $db = DB::connection(VNCORE_DB_CONNECTION);

        $dataConfig = $this->dataConfig($storeId);
        $db->table(VNCORE_DB_PREFIX.'admin_config')->insert($dataConfig);
    }
    
    public function dataConfig($storeId) {
        $dataConfig = [
            ['group' => '','code' => 'product_config_attribute','key' => 'product_brand','value' => '1','sort' => '0','detail' => 'product.config_manager.brand','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute_required','key' => 'product_brand_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute','key' => 'product_supplier','value' => '1','sort' => '0','detail' => 'product.config_manager.supplier','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute_required','key' => 'product_supplier_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute','key' => 'product_price','value' => '1','sort' => '0','detail' => 'product.config_manager.price','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute_required','key' => 'product_price_required','value' => '1','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute','key' => 'product_cost','value' => '1','sort' => '0','detail' => 'product.config_manager.cost','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute_required','key' => 'product_cost_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute','key' => 'product_promotion','value' => '1','sort' => '0','detail' => 'product.config_manager.promotion','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute_required','key' => 'product_promotion_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute','key' => 'product_stock','value' => '1','sort' => '0','detail' => 'product.config_manager.stock','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute_required','key' => 'product_stock_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute','key' => 'product_kind','value' => '1','sort' => '0','detail' => 'product.config_manager.kind','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute','key' => 'product_property','value' => '1','sort' => '0','detail' => 'product.config_manager.property','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute_required','key' => 'product_property_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute','key' => 'product_attribute','value' => '1','sort' => '0','detail' => 'product.config_manager.attribute','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute_required','key' => 'product_attribute_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute','key' => 'product_available','value' => '1','sort' => '0','detail' => 'product.config_manager.available','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute_required','key' => 'product_available_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute','key' => 'product_weight','value' => '1','sort' => '0','detail' => 'product.config_manager.weight','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute_required','key' => 'product_weight_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute','key' => 'product_length','value' => '1','sort' => '0','detail' => 'product.config_manager.length','store_id' => $storeId],
            ['group' => '','code' => 'product_config_attribute_required','key' => 'product_length_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'product_config','key' => 'product_display_out_of_stock','value' => '1','sort' => '19','detail' => 'product.config_manager.product_display_out_of_stock','store_id' => $storeId],
            ['group' => '','code' => 'product_config','key' => 'show_date_available','value' => '1','sort' => '21','detail' => 'product.config_manager.show_date_available','store_id' => $storeId],
            ['group' => '','code' => 'product_config','key' => 'product_cart_off','value' => '0','sort' => '22','detail' => 'product.config_manager.product_cart_off','store_id' => $storeId],
            ['group' => '','code' => 'product_config','key' => 'product_wishlist_off','value' => '0','sort' => '23','detail' => 'product.config_manager.product_wishlist_off','store_id' => $storeId],
            ['group' => '','code' => 'product_config','key' => 'product_compare_off','value' => '0','sort' => '24','detail' => 'product.config_manager.product_compare_off','store_id' => $storeId],
            ['group' => '','code' => 'product_config','key' => 'product_tax','value' => '1','sort' => '0','detail' => 'product.config_manager.tax','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute','key' => 'customer_lastname','value' => '1','sort' => '1','detail' => 'customer.config_manager.lastname','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute_required','key' => 'customer_lastname_required','value' => '1','sort' => '1','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute','key' => 'customer_address1','value' => '1','sort' => '2','detail' => 'customer.config_manager.address1','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute_required','key' => 'customer_address1_required','value' => '1','sort' => '2','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute','key' => 'customer_address2','value' => '1','sort' => '2','detail' => 'customer.config_manager.address2','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute_required','key' => 'customer_address2_required','value' => '1','sort' => '2','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute','key' => 'customer_address3','value' => '0','sort' => '2','detail' => 'customer.config_manager.address3','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute_required','key' => 'customer_address3_required','value' => '0','sort' => '2','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute','key' => 'customer_company','value' => '0','sort' => '0','detail' => 'customer.config_manager.company','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute_required','key' => 'customer_company_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute','key' => 'customer_postcode','value' => '0','sort' => '0','detail' => 'customer.config_manager.postcode','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute_required','key' => 'customer_postcode_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute','key' => 'customer_country','value' => '1','sort' => '0','detail' => 'customer.config_manager.country','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute_required','key' => 'customer_country_required','value' => '1','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute','key' => 'customer_group','value' => '0','sort' => '0','detail' => 'customer.config_manager.group','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute_required','key' => 'customer_group_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute','key' => 'customer_birthday','value' => '0','sort' => '0','detail' => 'customer.config_manager.birthday','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute_required','key' => 'customer_birthday_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute','key' => 'customer_sex','value' => '0','sort' => '0','detail' => 'customer.config_manager.sex','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute_required','key' => 'customer_sex_required','value' => '0','sort' => '0','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute','key' => 'customer_phone','value' => '1','sort' => '0','detail' => 'customer.config_manager.phone','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute_required','key' => 'customer_phone_required','value' => '1','sort' => '1','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute','key' => 'customer_name_kana','value' => '0','sort' => '0','detail' => 'customer.config_manager.name_kana','store_id' => $storeId],
            ['group' => '','code' => 'customer_config_attribute_required','key' => 'customer_name_kana_required','value' => '0','sort' => '1','detail' => '','store_id' => $storeId],
            ['group' => '','code' => 'admin_config','key' => 'ADMIN_NAME','value' => 'Vncore System','sort' => '0','detail' => 'admin.env.ADMIN_NAME','store_id' => $storeId],
            ['group' => '','code' => 'admin_config','key' => 'ADMIN_TITLE','value' => 'Vncore Admin','sort' => '0','detail' => 'admin.env.ADMIN_TITLE','store_id' => $storeId],
            ['group' => '','code' => 'admin_config','key' => 'hidden_copyright_footer','value' => '0','sort' => '0','detail' => 'admin.env.hidden_copyright_footer','store_id' => $storeId],
            ['group' => '','code' => 'admin_config','key' => 'hidden_copyright_footer_admin','value' => '0','sort' => '0','detail' => 'admin.env.hidden_copyright_footer_admin','store_id' => $storeId],
            ['group' => '','code' => 'display_config','key' => 'product_top','value' => '12','sort' => '0','detail' => 'store.display.product_top','store_id' => $storeId],
            ['group' => '','code' => 'display_config','key' => 'product_list','value' => '12','sort' => '0','detail' => 'store.display.list_product','store_id' => $storeId],
            ['group' => '','code' => 'display_config','key' => 'product_relation','value' => '4','sort' => '0','detail' => 'store.display.relation_product','store_id' => $storeId],
            ['group' => '','code' => 'display_config','key' => 'product_viewed','value' => '4','sort' => '0','detail' => 'store.display.viewed_product','store_id' => $storeId],
            ['group' => '','code' => 'display_config','key' => 'item_list','value' => '12','sort' => '0','detail' => 'store.display.item_list','store_id' => $storeId],
            ['group' => '','code' => 'display_config','key' => 'item_top','value' => '12','sort' => '0','detail' => 'store.display.item_top','store_id' => $storeId],
            ['group' => '','code' => 'order_config','key' => 'shop_allow_guest','value' => '1','sort' => '11','detail' => 'order.admin.shop_allow_guest','store_id' => $storeId],
            ['group' => '','code' => 'order_config','key' => 'product_preorder','value' => '1','sort' => '18','detail' => 'order.admin.product_preorder','store_id' => $storeId],
            ['group' => '','code' => 'order_config','key' => 'product_buy_out_of_stock','value' => '1','sort' => '20','detail' => 'order.admin.product_buy_out_of_stock','store_id' => $storeId],
            ['group' => '','code' => 'order_config','key' => 'shipping_off','value' => '0','sort' => '20','detail' => 'order.admin.shipping_off','store_id' => $storeId],
            ['group' => '','code' => 'order_config','key' => 'payment_off','value' => '0','sort' => '20','detail' => 'order.admin.payment_off','store_id' => $storeId],
            ['group' => '','code' => 'email_action','key' => 'email_action_mode','value' => '0','sort' => '0','detail' => 'email.email_action.email_action_mode','store_id' => $storeId],
            ['group' => '','code' => 'email_action','key' => 'email_action_queue','value' => '0','sort' => '1','detail' => 'email.email_action.email_action_queue','store_id' => $storeId],
            ['group' => '','code' => 'smtp_config','key' => 'smtp_host','value' => '','sort' => '1','detail' => 'email.config_smtp.smtp_host','store_id' => $storeId],
            ['group' => '','code' => 'smtp_config','key' => 'smtp_user','value' => '','sort' => '2','detail' => 'email.config_smtp.smtp_user','store_id' => $storeId],
            ['group' => '','code' => 'smtp_config','key' => 'smtp_password','value' => '','sort' => '3','detail' => 'email.config_smtp.smtp_password','store_id' => $storeId],
            ['group' => '','code' => 'smtp_config','key' => 'smtp_security','value' => '','sort' => '4','detail' => 'email.config_smtp.smtp_security','store_id' => $storeId],
            ['group' => '','code' => 'smtp_config','key' => 'smtp_port','value' => '','sort' => '5','detail' => 'email.config_smtp.smtp_port','store_id' => $storeId],
            ['group' => '','code' => 'smtp_config','key' => 'smtp_name','value' => '','sort' => '6','detail' => 'email.config_smtp.smtp_name','store_id' => $storeId],
            ['group' => '','code' => 'smtp_config','key' => 'smtp_from','value' => '','sort' => '7','detail' => 'email.config_smtp.smtp_from','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'SUFFIX_URL','value' => '.html','sort' => '0','detail' => 'admin.env.SUFFIX_URL','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_SHOP','value' => 'shop','sort' => '0','detail' => 'admin.env.PREFIX_SHOP','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_BRAND','value' => 'brand','sort' => '0','detail' => 'admin.env.PREFIX_BRAND','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_CATEGORY','value' => 'category','sort' => '0','detail' => 'admin.env.PREFIX_CATEGORY','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_SUB_CATEGORY','value' => 'sub-category','sort' => '0','detail' => 'admin.env.PREFIX_SUB_CATEGORY','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_PRODUCT','value' => 'product','sort' => '0','detail' => 'admin.env.PREFIX_PRODUCT','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_SEARCH','value' => 'search','sort' => '0','detail' => 'admin.env.PREFIX_SEARCH','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_CONTACT','value' => 'contact','sort' => '0','detail' => 'admin.env.PREFIX_CONTACT','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_ABOUT','value' => 'about','sort' => '0','detail' => 'admin.env.PREFIX_ABOUT','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_NEWS','value' => 'news','sort' => '0','detail' => 'admin.env.PREFIX_NEWS','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_MEMBER','value' => 'customer','sort' => '0','detail' => 'admin.env.PREFIX_MEMBER','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_MEMBER_ORDER_LIST','value' => 'order-list','sort' => '0','detail' => 'admin.env.PREFIX_MEMBER_ORDER_LIST','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_MEMBER_CHANGE_PWD','value' => 'change-password','sort' => '0','detail' => 'admin.env.PREFIX_MEMBER_CHANGE_PWD','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_MEMBER_CHANGE_INFO','value' => 'change-info','sort' => '0','detail' => 'admin.env.PREFIX_MEMBER_CHANGE_INFO','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_CMS_CATEGORY','value' => 'cms-category','sort' => '0','detail' => 'admin.env.PREFIX_CMS_CATEGORY','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_CMS_ENTRY','value' => 'entry','sort' => '0','detail' => 'admin.env.PREFIX_CMS_ENTRY','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_CART_WISHLIST','value' => 'wishlst','sort' => '0','detail' => 'admin.env.PREFIX_CART_WISHLIST','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_CART_COMPARE','value' => 'compare','sort' => '0','detail' => 'admin.env.PREFIX_CART_COMPARE','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_CART_DEFAULT','value' => 'cart','sort' => '0','detail' => 'admin.env.PREFIX_CART_DEFAULT','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_CART_CHECKOUT','value' => 'checkout','sort' => '0','detail' => 'admin.env.PREFIX_CART_CHECKOUT','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_CART_CHECKOUT_CONFIRM','value' => 'checkout-confirm','sort' => '0','detail' => 'admin.env.PREFIX_CART_CHECKOUT_CONFIRM','store_id' => $storeId],
            ['group' => '','code' => 'url_config','key' => 'PREFIX_ORDER_SUCCESS','value' => 'order-success','sort' => '0','detail' => 'admin.env.PREFIX_ORDER_SUCCESS','store_id' => $storeId],
            ['group' => '','code' => 'captcha_config','key' => 'captcha_mode','value' => '0','sort' => '20','detail' => 'admin.captcha.captcha_mode','store_id' => $storeId],
            ['group' => '','code' => 'captcha_config','key' => 'captcha_page','value' => '[]','sort' => '10','detail' => 'captcha.captcha_page','store_id' => $storeId],
            ['group' => '','code' => 'captcha_config','key' => 'captcha_method','value' => '','sort' => '0','detail' => 'admin.captcha.captcha_method','store_id' => $storeId],
            ['group' => '','code' => 'admin_custom_config','key' => 'facebook_url','value' => 'https://www.facebook.com/VnCore.official/','sort' => '0','detail' => 'admin.admin_custom_config.facebook_url','store_id' => $storeId],
            ['group' => '','code' => 'admin_custom_config','key' => 'fanpage_url','value' => 'https://www.facebook.com/VnCore.official/','sort' => '0','detail' => 'admin.admin_custom_config.fanpage_url','store_id' => $storeId],
            ['group' => '','code' => 'admin_custom_config','key' => 'twitter_url','value' => '#','sort' => '0','detail' => 'admin.admin_custom_config.twitter_url','store_id' => $storeId],
            ['group' => '','code' => 'admin_custom_config','key' => 'instagram_url','value' => '#','sort' => '0','detail' => 'admin.admin_custom_config.instagram_url','store_id' => $storeId],
            ['group' => '','code' => 'admin_custom_config','key' => 'youtube_url','value' => 'https://www.youtube.com/channel/UCR8kitefby3N6KvvawQVqdg/videos','sort' => '0','detail' => 'admin.admin_custom_config.youtube_url','store_id' => $storeId],
            ['group' => '','code' => 'config_layout','key' => 'link_account','value' => '1','sort' => '0','detail' => 'admin.config_layout.link_account','store_id' => $storeId],
            ['group' => '','code' => 'config_layout','key' => 'link_language','value' => '1','sort' => '0','detail' => 'admin.config_layout.link_language','store_id' => $storeId],
            ['group' => '','code' => 'config_layout','key' => 'link_currency','value' => '1','sort' => '0','detail' => 'admin.config_layout.link_currency','store_id' => $storeId],
            ['group' => '','code' => 'config_layout','key' => 'link_cart','value' => '1','sort' => '0','detail' => 'admin.config_layout.link_cart','store_id' => $storeId],
        ];
        return $dataConfig;
    }
}
