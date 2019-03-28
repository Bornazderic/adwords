<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Woocommerce;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

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

        $page = 1;
        $products = [];
        $all_products = [];
        $finished_array = [];
        $counter = 0;
        do{
        try {
          
            $products = Woocommerce::get('products', ['per_page' => 100, 'page' => $page]);

        }catch(HttpClientException $e){
            die("Can't get products: $e");
        }
        $all_products = array_merge($all_products,$products);
        $page++;
        } while (count($products) > 0);

        foreach($all_products as $product)
        {
            
            $finished_array[$counter][] = $product["id"];
            $finished_array[$counter][] = null;
            $finished_array[$counter][] = $product["name"];
            $finished_array[$counter][] = $product["permalink"];
            $finished_array[$counter][] = collect($product["images"])->first()["src"];
            $finished_array[$counter][] = $product["description"];
            $finished_array[$counter][] = collect($product["categories"])->first()["name"];
            $finished_array[$counter][] = $product["price"];
            $finished_array[$counter][] = $product["sale_price"];
            $counter = $counter + 1;
        }

        //return $all_products;
        return $this->export($finished_array);

    }

    public function export($finished_array)
    {
        $export = new UsersExport([
            $finished_array
        ]);
    
        return Excel::download($export, 'invoices.xlsx');
    }
}
