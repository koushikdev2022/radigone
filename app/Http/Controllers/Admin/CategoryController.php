<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\AdType;
use App\SliderPrice;
use App\Offertype;

use App\Http\Controllers\Controller;
use App\SubCategoryRequest;
use App\Surveyor;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories(){
        $page_title = 'Manage Category';
        $empty_message = 'No category found';
        $categories = Category::latest()->paginate(getPaginate());
        $sub_cat_requests = SubCategoryRequest::where('status', 0)->with('surveyor')->latest()->paginate(getPaginate());
        return view('admin.category', compact('page_title', 'empty_message','categories', 'sub_cat_requests'));
    }

    public function activate(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $category = Category::findOrFail($request->id);
        $category->status = 1;
        $category->save();
        $notify[] = ['success', $category->name . ' has been activated'];
        return back()->withNotify($notify);
    }

    public function deactivate(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $category = Category::findOrFail($request->id);
        $category->status = 0;
        $category->save();
        $notify[] = ['success', $category->name . ' has been disabled'];
        return back()->withNotify($notify);
    }

    public function storeCategory(Request $request){

        $request->validate([
            'name' => 'required|string|max:190|unique:categories'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->price = $request->price;
        $category->subcategories = $request->subcategories;
        $category->status = 1;
        $category->save();

        $notify[] = ['success', 'Category details has been added'];
        return back()->withNotify($notify);
    }

    public function updateCategory(Request $request,$id){

        $request->validate([
            'name' => 'required|string|max:190|unique:categories,name,'.$id,
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->price = $request->price;
        $category->subcategories = $request->subcategories;
        $category->save();

        $notify[] = ['success', 'category details has been Updated'];
        return back()->withNotify($notify);
    }

    public function searchCategory(Request $request)
    {

        $request->validate(['search' => 'required']);
        $search = $request->search;
        $page_title = 'Category Search - ' . $search;
        $empty_message = 'No categories found';
        $categories = Category::where('name', 'like',"%$search%")->paginate(getPaginate());

        return view('admin.category', compact('page_title', 'categories', 'empty_message'));
    }
    public function sliderSecond(){

        $page_title = 'Slider Price';
        $empty_message = 'No ad found';
        $categories = SliderPrice::latest()->paginate(getPaginate());
        return view('admin.slidePrice', compact('page_title', 'empty_message','categories'));
    }
    public function stopSlider(Request $request){

        $category = new SliderPrice();
        $category->second = $request->name;
        $category->price = $request->price;
        $category->save();
        $notify[] = ['success', 'Slider Price has been added'];
        return back()->withNotify($notify);
    }
    public function adType(){

        $page_title = 'Ad Type';
        $empty_message = 'No ad found';
        $categories = AdType::latest()->paginate(getPaginate());
        return view('admin.adType', compact('page_title', 'empty_message','categories'));
    }
    public function storeadType(Request $request){

        $request->validate([
            'name' => 'required|string|max:190|unique:categories'
        ]);

        $category = new AdType();
        $category->name = $request->name;
        $category->price = $request->price;
        $category->status = 1;
        $category->save();

        $notify[] = ['success', 'Category details has been added'];
        return back()->withNotify($notify);
    }
    public function activateadType(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $category = Category::findOrFail($request->id);
        $category->status = 1;
        $category->save();
        $notify[] = ['success', $category->name . ' has been activated'];
        return back()->withNotify($notify);
    }
     public function stopUpdate(Request $request,$id){
        $category = SliderPrice::findOrFail($id);
        $category->second = $request->name;
        $category->price = $request->price;
        $category->save();

        $notify[] = ['success', 'Slider Price has been added'];
        return back()->withNotify($notify);
     }
     public function updateType(Request $request,$id){

        $request->validate([
            'name' => 'required|string|max:190|unique:categories,name,'.$id,
        ]);

        $category = AdType::findOrFail($id);
        $category->name = $request->name;
        $category->price = $request->price;
        $category->save();

        $notify[] = ['success', 'category details has been Updated'];
        return back()->withNotify($notify);
    }
     public function offertype(){

        $page_title = 'Offer type';
        $empty_message = 'No ad found';
        $categories = Offertype::latest()->paginate(getPaginate());
        return view('admin.offertype', compact('page_title', 'empty_message','categories'));
    }
     public function stopOffertype(Request $request){

        $request->validate([
            'name' => 'required|string|max:190|unique:categories'
        ]);

        $category = new Offertype();
        $category->name = $request->name;


        $category->save();

        $notify[] = ['success', 'Offer type has been added'];
        return back()->withNotify($notify);
    }
    public function OffertypeUpdate(Request $request,$id){

        $request->validate([
            'name' => 'required|string|max:190|unique:categories,name,'.$id,
        ]);

        $category = Offertype::findOrFail($id);
        $category->name = $request->name;

        $category->save();

        $notify[] = ['success', 'Offer type has been Updated'];
        return back()->withNotify($notify);
    }

    public function subCategoryRequestUpdate($id)
    {
        $sub_cat_request = SubCategoryRequest::whereId($id)->first();
        $surveyor = Surveyor::whereId($sub_cat_request->surveyor_id)->first();
        $surveyor->business_subcat = $sub_cat_request->name;
        $surveyor->save();
        $sub_cat_request->status = 1;
        $sub_cat_request->save();
        $notify[] = ['success', 'Successfully approved!'];
        return back()->withNotify($notify);
    }


}
