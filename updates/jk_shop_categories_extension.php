<?php namespace Webinsane\Jkcategories\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class JkShopCategoriesExtension extends Migration
{
    public function up()
    {
        Schema::table('jiri_jkshop_categories', function ($table) {
            $table->boolean("show_in_list")->nullable();
        });
    }

    public function down()
    {
        Schema::table('jiri_jkshop_orders', function ($table) {
            $table->dropColumn('show_in_list');
        });
    }
}