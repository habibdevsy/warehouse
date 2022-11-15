<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\InTransaction;
use App\Models\OutTransaction;
use Illuminate\Console\Command;

class UpdateCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update old code';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->generateCodeIndicatingItem();
        
        return Command::SUCCESS;
    }

     /**
    * generate code indicating an item
    */
    public function  generateCodeIndicatingItem()
    {
        $items = Item::with('category','inTransactions','outTransactions')->get();
        foreach($items as $item) {
            $item_name = $item->name;
            $category_name = $item->category->name;
            $commercial_name = $item->commercial_name;       
            $arr_of_item_name = str_split($item_name);
            $first_character_of_item_name = strtoupper($arr_of_item_name[0]);

            $arr_of_category_name = str_split($category_name);
            $first_character_of_category_name = strtoupper($arr_of_category_name[0]);
            $last_character_of_category_name = strtoupper(end($arr_of_category_name));

            $arr_of_commercial_name = str_split($commercial_name);
            $first_character_of_commercial_name = strtoupper($arr_of_commercial_name[0]);
        
            $count_character_of_commercial_name = count($arr_of_commercial_name);
    
            $invID = str_pad($count_character_of_commercial_name, 3, '0', STR_PAD_LEFT);

            $code = join("",[$first_character_of_item_name, $first_character_of_category_name, $last_character_of_category_name, $first_character_of_commercial_name, $invID]);
           
            $inTransaction = InTransaction::where("item_id","=",$item->id)->first();
            $inTransaction->update([
                "code" => $code
            ]);
            $outTransaction = OutTransaction::where("item_id","=",$item->id)->first();
            $outTransaction->update([
                "code" => $code
            ]);
        }            
    }
}
