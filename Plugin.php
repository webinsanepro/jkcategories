<?php namespace Webinsane\Jkcategories;

use Event;
use Jiri\JKShop\Controllers\Categories;
use Jiri\JKShop\Models\Category;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
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
                'show_in_list' => [
                    'tab'     => 'jiri.jkshop::lang.categories.detail',
                    'label'   => 'Show category and its children',
                    'type'    => 'switch'
                ]
            ]);
        });
        Categories::extendListColumns(function($list, $model) {

            if (!$model instanceof Category)
                return;

            $list->addColumns([
                'show_in_list' => [
                    'label'   => 'Show category and its children',
                    'type'    => 'switch'
                ]
            ]);

        });

        Category::extend(function($model) {
            $model->addDynamicMethod('childrenInList', function() use ($model) {
                return $model->children->filter(function($subCategory){
                    return $subCategory->active == 1 && $subCategory->show_in_list;
                });
            });
        });
    }
}
