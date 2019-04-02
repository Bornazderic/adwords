<?php

namespace App\Http\Controllers;

use App\Exports;
use Woocommerce;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()

    {
        $exports = Exports::all();
        $categories = Woocommerce::get('products/categories', ['per_page' => 100]);
        $status = ['any','draft','pending','private','publish'];
        $stock = ['instock','outofstock','onbackorder'];
        $types = ['xlsx', 'csv', 'tsv', 'xls', 'html'];
        usort($categories,function($a,$b) {return strnatcasecmp($a['name'],$b['name']);});

        //return $categories;

 
        return view('index', [
            'exports' => $exports,
            'categories' => $categories,
            'status' => $status,
            'stock' => $stock,
            'types' => $types
        ]);

    }

    public function arrayCreate(Request $request)
    {   
        $page = 1;
        $products = [];
        $all_products = [];
        $finished_array = [];
        $counter = 1;
        do{
        try {
          
            $products = Woocommerce::get('products', ['per_page' => 100, 'page' => $page, 'status' => $request->status, 'stock_status' => $request->stock]); //category = request cat
        }catch(HttpClientException $e){
            die("Can't get products: $e");
        }
        $all_products = array_merge($all_products,$products);
        $page++;
        } while (count($products) > 0);

        $header = array('ID','ID2','Item Title','Final URL','Image URL from subtitle','Item Description','Item Category','Price','Sale Price'); //header
        $finished_array[0] = $header;

        foreach($all_products as $product)
        {
            if($request->category == collect($product["categories"])->first()["name"] || $request->category == 'All')
            {
                $finished_array[$counter][] = $product["id"];
                $finished_array[$counter][] = null;
                $finished_array[$counter][] = $product["name"];
                $finished_array[$counter][] = $product["permalink"];
                $finished_array[$counter][] = collect($product["images"])->first()["src"];
                $finished_array[$counter][] = strip_tags($product["description"]);
                $finished_array[$counter][] = collect($product["categories"])->first()["name"];
                $finished_array[$counter][] = $product["price"];
                $finished_array[$counter][] = $product["sale_price"];
                $counter = $counter + 1;
            }
            else continue;
        }
        //return $finished_array;

        Exports::create([
            'user_id' => Auth::user()->id,
            'date' => Carbon::now()->toDateString(),
            'time' => Carbon::now()->toTimeString(),
            'file_name' => $request->category.Carbon::now().'.'.$request->type,
            'type' => $request->type
        ]);
        return $this->export($finished_array,$request);
    }

    public function export($finished_array,$request)
    {    
        $extension = $request->type;
        $export = new UsersExport([
            $finished_array
        ]);
    
    
        Excel::store($export,$request->category.Carbon::now().'.'.$extension);
        return $this->index();
    }

    public function delete($id)
    {
       Exports::findOrFail($id)->destroy($id);
       return redirect()->route('index', ['success' => 'kurcinaaaaaaaa']);
    }
}
