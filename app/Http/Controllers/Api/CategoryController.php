<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class CategoryController extends BaseController
{
    /**
    * create category
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
        ]);
       
        if ($validator->fails()) {
            return $this->sendError('Error', $validator->errors()->first(), 400);
        }

        $item = Category::create([
              'name' => $request->name,
            ]);

        return $this->sendResponse($item, "success");
    }
}
