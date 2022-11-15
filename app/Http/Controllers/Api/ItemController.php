<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\InTransaction;
use App\Models\OutTransaction;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class ItemController extends BaseController
{
    /**
    * create item
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'commercial_name' => 'required|string|max:150',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|integer',
        ]);
       
        if ($validator->fails()) {
            return $this->sendError('Error', $validator->errors()->first(), 400);
        }
        //check category is found
        $category = Category::where('id','=',$request->category_id)->first();
        if(!$category){
            return $this->sendError('Error', 'the category not found!', 400);
        }
        //create item
        $item = Item::create([
              'name' => $request->name,
              'commercial_name' => $request->commercial_name,
              'price' => $request->price,
              'quantity' => $request->quantity,
              'category_id' => $request->category_id,
            ]);

        if($item) {
            $this->createInTransaction($item->quantity, $item->id);
        }

        return $this->sendResponse($item, "success");
    }

    /**
    * pull item
    *
    * @return \Illuminate\Http\Response
    */
    public function pull(Request $request)
    {  
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'quantity' => 'required|integer'
        ]);
       
        if ($validator->fails()) {
            return $this->sendError('Error', $validator->errors()->first(), 400);
        }

        //Is the item available, is the required quantity available?
        $item = Item::where('id','=',$request->id)->first();
        if(!$item){
            return $this->sendError('Error', 'the item not found!', 400);
        }
        if($item->quantity < $request->quantity) {
            return $this->sendError('Error', 'The required quantity is not available!', 400);
        }

        $newQuantity = $item->quantity - $request->quantity;
        //update item quantity 
        $item->update([
              'quantity' => $newQuantity,
            ]);

        if($item) {
            $this->createOutTransaction($item->quantity, $item->id);
        }

        return $this->sendResponse($item, "success");
    }

    /**
    * create InTransaction
    *
    * @return \Illuminate\Http\Response
    */
    public function createInTransaction(int $quantity, int $item_id):void
    {  
        $code = $this->generateCode($item_id);
        InTransaction::create([
            'code' => $code,
            'quantity' => $quantity,
            'item_id' => $item_id,
           ]);
    }

    /**
    * create InTransaction
    *
    * @return \Illuminate\Http\Response
    */
    public function createOutTransaction(int $quantity, int $item_id):void
    {  
        $code = $this->generateCode($item_id);
        OutTransaction::create([
            'code' => $code,
            'quantity' => $quantity,
            'item_id' => $item_id,
           ]);
    }
    
    /**
    * generate transaction code
    */
    public function generateCode(int $item_id) :string
    {
        $chars = array(0,1,2,3,4,5,6,7,8,9);
        $code = '';
        $max = count($chars)-1;
        
        for($i=0;$i<2;$i++){
          $code .= $item_id.$chars[rand(0, $max)];
        }
        
        return $code;
     }
}