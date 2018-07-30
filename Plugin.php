<?php namespace Webinsane\Jkcategories;

use Event;
use Jiri\JKShop\Controllers\Categories;
use Jiri\JKShop\Models\Category;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'webinsane.jkcategories::lang.plugin.name',
            'description' => 'webinsane.jkcategories::lang.plugin.description',
            'author'      => 'Webinsane',
            'icon'        => 'oc-icon-table'
        ];
    }

    public function registerComponents()
    {
        return [
            'Webinsane\Jkcategories\Components\Categories' => 'webinsane_categories',
            'Webinsane\Jkcategories\Components\RecursiveCategories' => 'webinsane_recursive_categories',
        ];
    }

    public function boot()
    {
        Event::listen('backend.form.extendFields', function($widget) {

            if (!$widget->getController() instanceof \Jiri\JKShop\Controllers\Categories) {
                return;
            }
            if (!$widget->model instanceof Category) {
                return;
            }
            $widget->addTabFields([
                'webinsane_jkcategories_show_in_list' => [
                    'tab'     => 'jiri.jkshop::lang.categories.detail',
                    'label'   => 'webinsane.jkcategories::lang.categories.switch',
                    'type'    => 'switch'
                ]
            ]);
        });
        Categories::extendListColumns(function($list, $model) {

            if (!$model instanceof Category)
                return;

            $list->addColumns([
                'webinsane_jkcategories_show_in_list' => [
                    'label'   => 'Show category and its children',
                    'type'    => 'switch'
                ]
            ]);

        });

        Category::extend(function($model) {
            $model->addDynamicMethod('childrenInList', function() use ($model) {
                return $model->children->filter(function($subCategory){
                    return $subCategory->active == 1 && $subCategory->webinsane_jkcategories_show_in_list;
                });
            });
        });
    }
}
