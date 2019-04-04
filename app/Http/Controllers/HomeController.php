<?php

namespace App\Http\Controllers;

use App\Site;
use App\Exports;
use Woocommerce;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Automattic\WooCommerce\Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\Console\WorkCommand;
use Pixelpeter\Woocommerce\WoocommerceClient;

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
    public function index(Request $request)
    {
        $site = $request->has('site') ? Site::findOrFail($request->site) : Site::first();
        $wooCommerce =  $this->mojWooClient($site);
 

      
     
        $exports = Exports::all();
        $sites = Site::all();
        //$categories = Woocommerce::get('products/categories', ['per_page' => 100]);
        $categories = $wooCommerce->get('products/categories', ['per_page' => 100]);
        $status = ['any', 'draft', 'pending', 'private', 'publish'];
        $stock = ['instock', 'outofstock', 'onbackorder'];
        $types = ['xlsx', 'csv', 'tsv', 'xls', 'html'];
        usort($categories, function ($a, $b) {
            return strnatcasecmp($a['name'], $b['name']);
        });


        return view('index', [
            'exports' => $exports,
            'sites' => $sites,
            'categories' => $categories,
            'status' => $status,
            'stock' => $stock,
            'types' => $types
        ]);
    }

    public function arrayCreate(Request $request)
    {
        $site = Site::findOrFail($request->site);
        $wooCommerce = $this->mojWooClient($site);

        $page = 1;
        $products = [];
        $all_products = [];
        $finished_array = [];
        $counter = 1;
        do {
            try {

                $products = $wooCommerce->get('products', ['per_page' => 100, 'page' => $page, 'status' => $request->status, 'stock_status' => $request->stock]); //category = request cat
            } catch (HttpClientException $e) {
                die("Can't get products: $e");
            }
            $all_products = array_merge($all_products, $products);
            $page++;
        } while (count($products) > 0);

        $header = array('ID', 'ID2', 'Item Title', 'Final URL', 'Image URL from subtitle', 'Item Description', 'Item Category', 'Price', 'Sale Price'); //header
        $finished_array[0] = $header;

        foreach ($all_products as $product) {
                if ($request->category == collect($product["categories"])->first()["name"] || $request->category == 'All') {
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
                    } else continue;
            }


        $data = Exports::create([
            'user_id' => Auth::user()->id,
            'file_name' => Carbon::now()->timestamp . '.' . $request->type,
            'category' => $request->category,
            'status' => $request->status,
            'type' => $request->type,
            'stock' => $request->stock
        ]);
        return $this->export($finished_array, $data);
    }

    public function export($finished_array, $data)
    {
        $export = new UsersExport([
            $finished_array
        ]);

        Excel::store($export, $data->file_name);
        return redirect()->back();
    }

    public function delete($id)
    {
        $file = Exports::findOrFail($id);
        $file->destroy($id);
        Storage::delete($file->file_name);
        Session::flash('delete', 'File je uspjesno izbrisan');
        return redirect()->route('index');
    }

    public function pull($id)
    {
        $file = Exports::findOrFail($id);
        return Storage::download($file->file_name);
    }

    public function mojWooClient($site)
    {
        $client = new Client(
            $site->store_url,
            decrypt($site->consumer_key),
            decrypt($site->consumer_secret),
            [
                'version' => 'wc/'.config('woocommerce.api_version'),
                'verify_ssl' => config('woocommerce.verify_ssl'),
                'wp_api' => config('woocommerce.wp_api'),
                'query_string_auth' => config('woocommerce.query_string_auth'),
                'timeout' => config('woocommerce.timeout'),
            ]);

            return new WoocommerceClient($client);
    }
}
