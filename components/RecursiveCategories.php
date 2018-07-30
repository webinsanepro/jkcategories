<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.07.2018
 * Time: 18:23
 */

namespace Webinsane\Jkcategories\Components;


use Cms\Classes\ComponentBase;

use Cms\Classes\Page;
use Jiri\JKShop\Models\Category;
class RecursiveCategories extends ComponentBase
{
    public $categoriesTreeByDepth;
    public $categoryPage;
    public function componentDetails()
    {
        return [
            'name'        => 'webinsane.jkcategories::lang.component.c2name',
            'description' => 'webinsane.jkcategories::lang.component.c2desc'
        ];
    }

    public function defineProperties()
    {
        return [
            'categoryPage' => [
                'title'       => 'webinsane.jkcategories::lang.component.title',
                'description' => 'webinsane.jkcategories::lang.component.tetledesc',
                'type'        => 'dropdown',
                'default'     => 'category',
                'group'       => 'webinsane.jkcategories::lang.component.grouplink',
            ],
        ];
    }
    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }
    public function onRun()
    {
        $this->categoriesTreeByDepth = Category::where('nest_depth', 0)
            ->where('active', 1)
            ->where('webinsane_jkcategories_show_in_list', 1)
            ->get();

    }
}