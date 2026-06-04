<?php
use App\Common\Utils;
use Illuminate\Support\Facades\Auth;
use App\Models\ShoppingCart;
use App\Models\ProductVariant;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\Category;
use App\Models\MetaManagement;
use App\Models\ProductCategory;
use App\Models\Setting;


use Darryldecode\Cart\CartCondition;

function calculateTax()
{
    $tax = Setting::where('key', 'TAX')->first();
    if(!empty($tax)){
        $taxObject = [
            'name' => 'VAT',
            'type' => 'tax',
            'target' => 'subtotal',
            'value' => $tax['value'],
        ];
    }
    else{
        $taxObject = [
            'name' => 'VAT',
            'type' => 'tax',
            'target' => 'subtotal',
            'value' => "18%",
        ];
    }
    $taxCondition  = new CartCondition($taxObject);

    // Apply the condition to the cart
    \Cart::condition($taxCondition);

    return $taxObject;
}

function get_shipping_charge()
{
    $tax = Setting::where('key', 'SHIPPING_CHARGES')->first();
    return (!empty($tax)) ? $tax['value'] : 0;
}

/* Get all categories */
function get_category_treeview()
{
    $search_params = array('status'=>1, 'deleted_at'=>NULL);
    $category_list = Utils::getCategoryTreeArray($search_params);

    // dd($category_list);
    return $category_list;
}


function get_cart_items()
{
    if( Auth::guard('user')->check() )
    {
        $user_id = Auth::guard('user')->id();
        $totalQuantity = ShoppingCart::where('user_id', $user_id)->sum('quantity');
        return $totalQuantity;
    }else{
        return \Cart::session(session()->getId())->getTotalQuantity();
    }

}

function get_cart_content()
{
    if( Auth::guard('user')->check() )
    {
        $user_id = Auth::guard('user')->id();
        $cart_content = \Cart::session($user_id)->getContent();

    }else{
        $cart_content = \Cart::session(session()->getId())->getContent();
    }

    return $cart_content;

}

function get_cart_subtotal()
{
    calculateTax();
    if( Auth::guard('user')->check() )
    {
        $user_id = Auth::guard('user')->id();
        $subtotal = \Cart::session($user_id)->getSubTotalWithoutConditions();

    }else{
        $subtotal = \Cart::session(session()->getId())->getSubTotalWithoutConditions();
    }

    return $subtotal;

}

/**
 * Get a setting value by key
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function get_setting($key, $default = null)
{
    $setting = Setting::where('key', $key)->first();
    return $setting ? $setting->value : $default;
}

function get_cart_total()
{
    calculateTax();
    if( Auth::guard('user')->check() )
    {
        $user_id = Auth::guard('user')->id();
        $subtotal = \Cart::session($user_id)->getTotal();

    }else{
        $subtotal = \Cart::session(session()->getId())->getTotal();
    }

    return $subtotal;

}

function get_wishlist_count()
{
    if( Auth::guard('user')->check() )
    {
        $user_id = Auth::guard('user')->id();
        $totalQuantity = Wishlist::where('user_id', $user_id)->count('product_varient_id');
        return $totalQuantity;
    }else{
        return 0;
    }

}

function format_inr($value) {
    // Check if value is a string and is numeric
    if (is_string($value) && is_numeric($value)) {
        $value = (float) $value;
    }

    // Check if it's a valid number after conversion
    if (is_int($value) || is_float($value)) {
        // Convert the number to a string with two decimal places
        $parts = explode('.', number_format($value, 2, '.', ''));
        $integerPart = $parts[0];
        $decimalPart = isset($parts[1]) ? '.' . $parts[1] : '';

        // Format integer part using Indian numbering format
        $length = strlen($integerPart);
        if ($length > 3) {
            $lastThree = substr($integerPart, $length - 3);
            $rest = substr($integerPart, 0, $length - 3);
            $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
            $integerPart = $rest . ',' . $lastThree;
        }

        return $integerPart . $decimalPart;
    }

    return 'Invalid number';
}

function get_meta_details()
{
    $meta_details = [];

    $meta_details['title'] = "Z Years Attire";
    $meta_details['description'] = "Z Years Attire";
    $meta_details['keywords'] = "men cloths, women cloths, child cloths";

    // getting URI segment

    $uri = Request::segment(1);

    $slug = Request::segment(2);
    $slug2 = Request::segment(3);

    // case based on URI

    switch ($uri) {
        case 'shop':
            $categorydtls = get_category_dtls_by_slug($slug);
            $query = [
                ["item_id", '=', $categorydtls->id],
                ["section", '=', "category"]
            ];

            $rawMeta = get_meta_from_db($query);

            if(!empty($rawMeta)){
                $meta_details['title'] = ($rawMeta->meta_title != null || $rawMeta->meta_title != "") ? $rawMeta->meta_title : '"Z Years Attire"';
                $meta_details['description'] = $rawMeta->meta_description;
                $meta_details['keywords'] = $rawMeta->meta_keywords;
            }

            break;
        case 'product':

            $productdtls = get_product_dtls_by_slug($slug2);
            $query = [
                ["item_id", '=', $productdtls->id],
                ["section", '=', "category"]
            ];

            $rawMeta = get_meta_from_db($query);

            if(!empty($rawMeta)){
                $meta_details['title'] = $rawMeta->meta_title;
                $meta_details['description'] = $rawMeta->meta_description;
                $meta_details['keywords'] = $rawMeta->meta_keywords;
            }

            break;

        default:
            break;
    }

    return $meta_details;
}

function get_product_dtls_by_slug($slug,)
{
    return Product::where('slug', $slug)->first();
}

function get_category_dtls_by_slug($slug,)
{
    return Category::where('slug', $slug)->first();
}

function get_meta_from_db($query){
    return MetaManagement::where($query)->first();
}


function get_category_slug_by_product_id($product_id){
    $product_category = ProductCategory::where('product_id', $product_id)->first();
    if(empty($product_category))
        return "";
    $category = Category::where('id', $product_category['category_id'])->first();
    if(!empty($category))
        return $category['slug'];
    else
        return "";
}


function get_socal_link(array $keys = ['FACEBOOK', 'X_TWITTER', 'INSTAGRAM', 'YOUTUBE', 'MAP', 'PHONE', 'EMAIL'])
{
    $link = Setting::whereIn('key', $keys)->pluck('value', 'key');
    $result = [];
    foreach ($keys as $key) {
        $result[$key] = $link[$key] ?? ''; // Default to an empty string if the key is not found.
    }
    return $result;
}