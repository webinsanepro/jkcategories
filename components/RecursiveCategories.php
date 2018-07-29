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
            'name'        => 'Categories levels',
            'description' => 'Recursive list of categories presentation'
        ];
    }

    public function defineProperties()
    {
        return [
            'categoryPage' => [
                'title'       => 'Category page',
                'description' => 'Category page link',
                'type'        => 'dropdown',
                'default'     => 'category',
                'group'       => 'Links',
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
            ->where('show_in_list', 1)
            ->get();

    }
}