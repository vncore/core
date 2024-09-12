<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Drop table if exist
        $this->down();
        $schema = Schema::connection(VNCORE_DB_CONNECTION);
        $schema->create(VNCORE_DB_PREFIX . 'admin_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username', 100)->unique();
            $table->string('password', 60);
            $table->string('name', 100);
            $table->string('email', 150)->unique();
            $table->string('avatar', 255)->nullable();
            $table->integer('status')->default(1)->comment('O: Lock, 1: Active');
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        $schema->create(VNCORE_DB_PREFIX . 'admin_role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('slug', 50)->unique();
            $table->timestamps();
        });

        $schema->create(VNCORE_DB_PREFIX . 'admin_permission', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->unique();
            $table->string('slug', 50)->unique();
            $table->text('http_uri')->nullable();
            $table->timestamps();
        });

        $schema->create(VNCORE_DB_PREFIX . 'admin_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->integer('sort')->default(0);
            $table->string('title', 255);
            $table->string('icon', 50);
            $table->string('uri', 255)->nullable();
            $table->integer('type')->default(0);
            $table->integer('hidden')->default(0);
            $table->string('key', 50)->unique()->nullable();
            $table->timestamps();
        });

        $schema->create(VNCORE_DB_PREFIX . 'admin_role_user', function (Blueprint $table) {
            $table->integer('role_id');
            $table->uuid('user_id');
            $table->index(['role_id', 'user_id']);
            $table->primary(['role_id', 'user_id']);
            $table->timestamps();
        });

        $schema->create(VNCORE_DB_PREFIX . 'admin_role_permission', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
            $table->primary(['role_id', 'permission_id']);
        });

        $schema->create(VNCORE_DB_PREFIX . 'admin_user_permission', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->integer('permission_id');
            $table->timestamps();
            $table->index(['user_id', 'permission_id']);
            $table->primary(['user_id', 'permission_id']);
        });

        $schema->create(VNCORE_DB_PREFIX . 'admin_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_id');
            $table->string('path');
            $table->string('method', 10);
            $table->string('ip');
            $table->string('user_agent')->nullable();
            $table->text('input');
            $table->index('user_id');
            $table->timestamps();
        });

        $schema->create(VNCORE_DB_PREFIX . 'admin_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group', 50)->nullable();
            $table->string('code', 50)->index();
            $table->string('key', 50);
            $table->string('value', 500)->nullable();
            $table->integer('security')->default(0)->nullable();
            $table->uuid('store_id')->default(0);
            $table->integer('sort')->default(0);
            $table->string('detail', 200)->nullable();
            $table->unique(['key', 'store_id']);
            $table->timestamps();
        });

        $schema->create(VNCORE_DB_PREFIX . 'admin_store', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('logo', 255)->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('og_image', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('long_phone', 100)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('time_active', 200)->nullable();
            $table->string('address', 300)->nullable();
            $table->string('office', 300)->nullable();
            $table->string('warehouse', 300)->nullable();
            $table->string('template', 100)->nullable();
            $table->string('domain', 100)->nullable()->index()->comment('Use for multi-store, multi-vendor');
            $table->string('partner', 10)->default(0)->index()->comment('Use for multi-vendor');
            $table->string('code', 20)->nullable()->unique();
            $table->string('language', 10);
            $table->string('currency', 10);
            $table->integer('status')->default(1)->comment('0:Lock, 1: unlock\nUse for multi-store, multi-vendor');
            $table->integer('active')->default(1)->comment('0:Maintain, 1: Active');
            $table->timestamps();
        });

        $schema->create(VNCORE_DB_PREFIX . 'admin_store_description', function (Blueprint $table) {
            $table->uuid('store_id');
            $table->string('lang', 10)->index();
            $table->string('title', 255)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('keyword', 200)->nullable();
            $table->mediumText('maintain_content')->nullable();
            $table->string('maintain_note', 300)->nullable();
            $table->primary(['store_id', 'lang']);
        });

        $schema->create(
            VNCORE_DB_PREFIX.'admin_password_resets',
            function (Blueprint $table) {
                $table->string('email', 150);
                $table->string('token', 255);
                $table->timestamp('created_at', $precision = 0);
                $table->index('email');
            }
        );

        //Notice
        $schema->create(VNCORE_DB_PREFIX . 'admin_notice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->index()->comment('Admin, Plugin, Template...');
            $table->string('type_id', 36)->index()->nullable()->comment('ID of order, customer, plugin...');
            $table->integer('status')->default(0)->index()->comment('O: new, 1: read');
            $table->string('admin_id', 36)->index()->nullable()->comment('Id of admin get notice');
            $table->string('partner_member_id', 36)->nullable()->index()->comment('Id of member partner get notice. Ex: Pmo partner, multi-vendor,...');
            $table->string('admin_created', 36)->comment('Admin created notice');
            $table->text('content');
            $table->timestamps();
        });
        //==End notice

        $schema->create(
            VNCORE_DB_PREFIX.'admin_language',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 255);
                $table->string('code', 50)->unique();
                $table->string('icon', 100)->nullable();
                $table->tinyInteger('status')->default(0);
                $table->tinyInteger('rtl')->nullable()->default(0)->comment('Layout RTL');
                $table->integer('sort')->default(0);
                $table->timestamps();
            }
        );

        $schema->create(
            VNCORE_DB_PREFIX.'admin_country',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('code', 10)->unique();
                $table->string('name', 255);
            }
        );

        
        $schema->create(
            VNCORE_DB_PREFIX.'admin_custom_field',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type', 50)->index()->comment('shop_product, shop_customer,...');
                $table->string('code', 100)->index();
                $table->string('name', 255);
                $table->integer('required')->default(0);
                $table->integer('status')->default(1);
                $table->string('option', 50)->nullable()->comment('radio, select, input');
                $table->string('default', 250)->nullable()->comment('{"value1":"name1", "value2":"name2"}');
                $table->timestamps();
            }
        );

        $schema->create(
            VNCORE_DB_PREFIX.'admin_custom_field_detail',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('custom_field_id')->index();
                $table->uuid('rel_id')->index()->comment('ID of product, customer,...');
                $table->text('text')->nullable();
                $table->timestamps();
            }
        );

        $schema->create(
            VNCORE_DB_PREFIX.'languages',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('code', 100)->index();
                $table->text('text')->nullable();
                $table->string('position', 100)->index();
                $table->string('location', 10)->index();
                $table->unique(['code', 'location']);
                $table->timestamps();
            }
        );

        $schema->create(
            VNCORE_DB_PREFIX.'api_connection',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('description', 500);
                $table->string('apiconnection', 50)->unique();
                $table->string('apikey', 128);
                $table->date('expire')->nullable();
                $table->timestamp('last_active', $precision = 0)->nullable();
                $table->timestamps();
                $table->tinyInteger('status')->default(0);
            }
        );

        $schema->create(VNCORE_DB_PREFIX . 'admin_home', function (Blueprint $table) {
            $table->increments('id');
            $table->string('view', 100);
            $table->string('extension', 100)->comment('Use when need remove|disable extension')->nullable();
            $table->integer('size')->comment('1-12: column size');
            $table->tinyInteger('status')->default(1);
            $table->integer('sort')->default(0);
            $table->timestamps();
        });


        //Sanctum
        $schema->create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->uuidMorphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $schema = Schema::connection(VNCORE_DB_CONNECTION);

        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_user');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_role');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_permission');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_menu');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_user_permission');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_role_user');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_role_permission');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_log');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_config');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_store');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_store_description');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_password_resets');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_notice');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_language');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_country');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_custom_field');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_custom_field_detail');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'languages');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'api_connection');
        $schema->dropIfExists(VNCORE_DB_PREFIX . 'admin_home');
        $schema->dropIfExists('personal_access_tokens');
        
    }
};
