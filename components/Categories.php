<?php
namespace Webinsane\Jkcategories\Components;
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23.07.2018
 * Time: 23:04
 */
use Cms\Classes\ComponentBase;

use Cms\Classes\Page;
use Jiri\JKShop\Models\Category;

class Categories extends ComponentBase
{
    public $categoriesTreeByDepth;
    public $categoriesTree;
    public $categoryPage;
    public function componentDetails()
    {
        return [
            'name'        => 'webinsane.jkcategories::lang.component.c1name',
            'description' => 'webinsane.jkcategories::lang.component.c1desc'
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
    public function onRun() {
        Category::all();
        $this->categoriesTreeByDepth = Category::where('nest_depth', 0)->where('active', 1)->where('webinsane_jkcategories_show_in_list', 1)
            ->get()
            ->map(function($category){
               $tree = $category
                   ->getAllChildrenAndSelf()
                   ->filter(function($categoryTaint) {
                       return $categoryTaint->webinsane_jkcategories_show_in_list;
                   });
                /**
                 * Checking of having parents id in the list.
                 */
               $rootWithChildrenIds = $tree->lists('id');
               $tree = $tree->filter(function($category) use($rootWithChildrenIds){
                   if($category->nest_depth == 0){
                       return true;
                   }
                   $parent = $category->getParent()->first();
                   return in_array($parent->id, $rootWithChildrenIds);
               })->groupBy('nest_depth');
               return $tree;
            });
        $this->categoryPage = $this->getProperties();
        if(isset($this->categoryPage['categoryPage'])) {
            $this->categoryPage = $this->categoryPage['categoryPage'];
        }
        else {
            $this->categoryPage = 'category';
        }
    }
}