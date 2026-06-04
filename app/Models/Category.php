<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'category_name', 
        'slug', 
        'parent_id', 
        'image',
        'is_featured',
        'sort_order',
        'status' 
    ];

    /**
     * Get all product categories associated with this category
     */
    public function product_categories()
    {
        return $this->hasMany(ProductCategory::class, 'category_id', 'id');
    }

    /**
     * Get all products associated with this category through product_categories pivot table
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }

    /**
     * Get parent category
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get child categories
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all descendant category IDs recursively.
     *
     * @return array
     */
    public function getAllDescendantIds()
    {
        $ids = [$this->id];
        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getAllDescendantIds());
        }
        return $ids;
    }

    /**
     * Get formatted category hierarchy using temporary table
     * 
     * @param array $search_params Optional search parameters (e.g., ['status' => 'active'])
     * @return array Formatted category list
     */
    public static function getFormattedCategory($search_params = array())
    {
        // Step 1: Create temporary table tmp_menuid
        DB::statement('CREATE TEMPORARY TABLE IF NOT EXISTS tmp_menuid (
            id INT AUTO_INCREMENT PRIMARY KEY,
            menuid INT
        )');

        // Step 2: Truncate tmp_menuid
        DB::table('tmp_menuid')->truncate();

        // Step 3: Insert into tmp_menuid
        DB::insert("INSERT INTO tmp_menuid(menuid)
        SELECT menu_id FROM 
        (
            SELECT DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(t2.all_ids, ',', t1.id), ',', -1) as menu_id
            FROM incr_mst as t1
            JOIN 
            (
                SELECT GROUP_CONCAT(all_id) as all_ids FROM
                (
                    SELECT 
                        m1.id AS level1_id,
                        m1.category_name AS level1_name,
                        m2.id AS level2_id,
                        m2.category_name AS level2_name,
                        m3.id AS level3_id,
                        m3.category_name AS level3_name,
                        m4.id AS level4_id,
                        m4.category_name AS level4_name,
                        CONCAT_WS(',', m1.id, IFNULL(m2.id, ''), IFNULL(m3.id, ''), IFNULL(m4.id, '')) AS all_id
                    FROM 
                        categories m1
                    LEFT JOIN 
                        categories m2 ON m2.parent_id = m1.id
                    LEFT JOIN 
                        categories m3 ON m3.parent_id = m2.id
                    LEFT JOIN 
                        categories m4 ON m4.parent_id = m3.id
                    WHERE 
                        m1.parent_id = 0
                    ORDER BY 
                        m1.id, m1.sort_order, m2.id, m2.sort_order, m3.id, m3.sort_order, m4.id, m4.sort_order
                ) as t1
            ) as t2
        ) as t3 
        WHERE t3.menu_id != ''");

        // Step 4: Select data from categories and join with tmp_menuid
        $sql = "SELECT 
                    t1.id,
                    t1.parent_id,
                    t1.category_name as text,
                    t1.slug,
                    t1.sort_order,
                    t1.deleted_at
                FROM categories as t1 
                JOIN tmp_menuid as t2 ON t1.id = t2.menuid AND t1.deleted_at IS NULL 
                WHERE 1=1";

        if (array_key_exists('status', $search_params) && $search_params['status'] != "") {
            $sql .= " AND t1.status = '" . DB::getPdo()->quote($search_params['status']) . "'";
        }
             
        $sql .= " ORDER BY t1.sort_order";

        $results = DB::select($sql);
        
        return $results;
    }
    
    /**
     * Save category items with hierarchy (for drag-and-drop reordering)
     * 
     * @param array $items Array of category items with potential children
     * @param int $parentId Parent category ID (0 for root level)
     * @return void
     */
    public function saveItems(array $items, $parentId = 0)
    {
        if (!empty($items)) {
            $sort_order = 0;
            foreach ($items as $item) {
                $sort_order = $sort_order + 1;
                DB::table('categories')
                    ->where('id', $item['id'])
                    ->update(['parent_id' => $parentId, 'sort_order' => $sort_order]);

                if (isset($item['children'])) {
                    self::saveItems($item['children'], $item['id']);
                }
            }
        }
    }

    /**
     * Recursive function to retrieve all child category IDs
     * 
     * @param int $id Category ID
     * @return array Array of all child category IDs
     */
    public static function getAllChildIds($id)
    {
        $children = [];
        
        // Find all direct children of the given ID
        $directChildren = DB::table('categories')->where('parent_id', $id)->get();

        foreach ($directChildren as $child) {
            // Add the child's ID to the array
            $children[] = $child->id;
            // Recursively find all children of the current child
            $children = array_merge($children, self::getAllChildIds($child->id));
        }

        return $children;
    }

    /**
     * Delete categories and their associated data
     * 
     * @param array $idsArr Array of category IDs to delete
     * @return void
     * @throws \Exception
     */
    public static function delete_category($idsArr = array())
    {
        DB::beginTransaction();
        try {
            if (is_array($idsArr) && !empty($idsArr)) {
                foreach ($idsArr as $id) {
                    // Unlink category image file
                    $catObj = new Category;
                    $category = $catObj::findOrFail($id);
                    $image_path = config('constants.CATEGORY_IMAGE_PATH');
                    
                    if ($category->image != "" && file_exists(public_path($image_path . $category->image))) {
                        unlink(public_path($image_path . $category->image));
                    }
                    
                    $category->delete();
                    
                    // Delete records from meta table
                    DB::table('meta_management')
                        ->where(['section' => 'category', 'item_id' => $id])
                        ->delete();
                }
            }
            
            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();
            
            throw $e;
        }
    }

    /**
     * Get all root categories (parent_id = 0)
     */
    public static function getRootCategories()
    {
        return self::where('parent_id', 0)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get category breadcrumb trail
     * 
     * @param int $id Category ID
     * @return array Array of parent categories
     */
    public static function getBreadcrumb($id)
    {
        $breadcrumb = [];
        $category = self::find($id);
        
        while ($category) {
            array_unshift($breadcrumb, $category);
            $category = $category->parent;
        }
        
        return $breadcrumb;
    }
}
