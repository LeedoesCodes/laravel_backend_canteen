<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run()
    {
        // ── Meals (Category 1) ─────────────────────────────────────────────
        $meals = [
            ['Chicken Rice',        'Steamed chicken with fragrant rice',                  45.00, 'https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=400&q=80'],
            ['Nasi Lemak',          'Coconut rice with sambal, egg, and anchovies',        50.00, 'https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=400&q=80'],
            ['Fried Rice',          'Indonesian style fried rice with egg',                55.00, 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=400&q=80'],
            ['Mee Goreng',          'Spicy fried noodles',                                 48.00, 'https://images.unsplash.com/photo-1617093727343-374698b1b08d?w=400&q=80'],
            ['Chicken Chop',        'Grilled chicken with fries and coleslaw',             65.00, 'https://mealpractice.b-cdn.net/204346778869436416/bbq-grilled-chicken-with-sweet-potato-fries-and-southern-style-coleslaw-c0YHmBNsYi.webp'],
            ['Fish & Chips',        'Battered fish with golden fries',                     62.00, 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/ff/Fish_and_chips_blackpool.jpg/1280px-Fish_and_chips_blackpool.jpg'],
            ['Spaghetti Bolognese', 'Pasta with rich meat sauce',                          58.00, 'https://www.recipetineats.com/tachyon/2018/07/Spaghetti-Bolognese.jpg'],
            ['Beef Steak',          'Grilled beef with seasonal vegetables',               85.00, 'https://images.unsplash.com/photo-1558030006-450675393462?w=400&q=80'],
            ['Vegetable Curry',     'Mixed vegetables in coconut curry sauce',             42.00, 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=400&q=80'],
            ['Chicken Curry',       'Spicy chicken curry served with rice',                54.00, 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=400&q=80'],
        ];

        // ── Snacks (Category 2) ────────────────────────────────────────────
        $snacks = [
            ['Spring Rolls',     'Crispy vegetable spring rolls (5 pcs)',       25.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRdvofWgqSJ7LmQEfAXYxieN9PYLFEHvNVNw_uYp2sevPp1ZHtR_pTKpWx-sQkEIdANUTdVimujzsxuplIMZ1PqScKk6kd3I213l8jm9g&s=10'],
            ['Chicken Wings',    'Spicy fried chicken wings (4 pcs)',           32.00, 'https://images.unsplash.com/photo-1527477396000-e27163b481c2?w=400&q=80'],
            ['Samosa',           'Potato and pea filled pastries (3 pcs)',      20.00, 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=400&q=80'],
            ['French Fries',     'Crispy golden potato fries',                  18.00, 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=400&q=80'],
            ['Onion Rings',      'Crispy battered onion rings',                 22.00, 'https://images.unsplash.com/photo-1639024471283-03518883512d?w=400&q=80'],
            ['Fish Balls',       'Fried fish balls (10 pcs)',                   15.00, 'https://images.unsplash.com/photo-1626200419199-391ae4be7a41?w=400&q=80'],
            ['Chicken Nuggets',  'Breaded chicken nuggets (6 pcs)',             28.00, 'https://images.unsplash.com/photo-1562802378-063ec186a863?w=400&q=80'],
            ['Curry Puff',       'Flaky pastry with spiced curry filling',      12.00, 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=400&q=80'],
            ['Popcorn Chicken',  'Bite-sized crispy fried chicken',             30.00, 'https://images.unsplash.com/photo-1596797038530-2c107229654b?w=400&q=80'],
            ['Cheese Sticks',    'Mozzarella cheese sticks (4 pcs)',            24.00, 'https://images.unsplash.com/photo-1531749668029-2db88e4276c7?w=400&q=80'],
        ];

        // ── Beverages (Category 3) ─────────────────────────────────────────
        $beverages = [
            ['Mineral Water',   '500ml chilled bottled water',             5.00,  'https://images.unsplash.com/photo-1564419320461-6870880221ad?w=400&q=80'],
            ['Coca Cola',       'Refreshing 330ml can',                    8.00,  'https://images.unsplash.com/photo-1629203851122-3726ecdf080e?w=400&q=80'],
            ['Orange Juice',    'Freshly squeezed orange juice',          12.00,  'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=400&q=80'],
            ['Iced Lemon Tea',  'Fresh brewed tea with lemon and ice',    10.00,  'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&q=80'],
            ['Hot Coffee',      'Rich freshly brewed arabica coffee',      9.00,  'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&q=80'],
            ['Hot Tea',         'Assorted premium tea bags',               8.00,  'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=80'],
            ['Milkshake',       'Thick chocolate or vanilla milkshake',   15.00,  'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=400&q=80'],
            ['Smoothie',        'Blended mixed fruit smoothie',           18.00,  'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=400&q=80'],
            ['Milo',            'Hot or iced chocolate malt drink',       11.00,  'https://www.goodnes.com/sites/g/files/jgfbjl321/files/srh_recipes/d6ca71fc032521bf07f48b209fd79dba.png'],
            ['Thai Milk Tea',   'Sweet and creamy orange Thai tea',       14.00,  'https://images.unsplash.com/photo-1558857563-b371033873b8?w=400&q=80'],
        ];

        // ── Desserts (Category 4) ──────────────────────────────────────────
        $desserts = [
            ['Ice Cream',       'Vanilla, chocolate, or strawberry scoop',  10.00, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=400&q=80'],
            ['Chocolate Cake',  'Rich chocolate fudge cake slice',          18.00, 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&q=80'],
            ['Cheesecake',      'Classic New York style cheesecake',        20.00, 'https://images.unsplash.com/photo-1567171466295-4afa63d45416?w=400&q=80'],
            ['Apple Pie',       'Warm apple pie with cinnamon',             16.00, 'https://images.unsplash.com/photo-1568571780765-9276ac8b75a2?w=400&q=80'],
            ['Brownie',         'Fudgy chocolate brownie with nuts',        14.00, 'https://images.unsplash.com/photo-1606312619070-d48b4c652a52?w=400&q=80'],
            ['Pudding',         'Silky vanilla or chocolate pudding',       12.00, 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=400&q=80'],
            ['Muffin',          'Fluffy blueberry or choc-chip muffin',    11.00, 'https://images.unsplash.com/photo-1607958996333-41aef7caefaa?w=400&q=80'],
            ['Donut',           'Soft glazed or chocolate frosted donut',   9.00, 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=400&q=80'],
            ['Cinnamon Roll',   'Swirled cinnamon pastry with icing',      15.00, 'https://horizon.com/wp-content/uploads/recipe-cin-roll-hero.jpg'],
            ['Fruit Salad',     'Bowl of chilled mixed fresh fruits',      22.00, 'https://images.unsplash.com/photo-1490474418585-ba9bad8fd0ea?w=400&q=80'],
        ];

        // ── Combos (Category 5) ────────────────────────────────────────────
        $combos = [
            ['Student Meal',    'Chicken rice + drink + snack',               65.00,  'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&q=80'],
            ['Lunch Combo',     'Main meal + drink + dessert',                80.00,  'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80'],
            ['Family Pack',     '2 mains + 2 drinks + 2 snacks',            150.00,  'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=400&q=80'],
            ['Party Platter',   'Assorted snacks for 4–6 pax',              180.00,  'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400&q=80'],
            ['Breakfast Set',   'Coffee + pastry + fresh fruit',             45.00,  'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=400&q=80'],
            ['Value Meal',      'Chicken chop + drink + fries',              92.00,  'https://images.unsplash.com/photo-1561758033-d89a9ad46330?w=400&q=80'],
            ['Snack Pack',      '3 assorted snacks + 2 drinks',              70.00,  'https://level5.com.ph/wp-content/uploads/2020/07/snackpackchoco.png'],
            ['Dessert Special', 'Dessert of choice + coffee or tea',         38.00,  'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=400&q=80'],
            ['Healthy Choice',  'Garden salad + juice + fruit cup',          58.00,  'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&q=80'],
            ['Business Lunch',  'Premium main + drink + dessert',           120.00,  'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&q=80'],
        ];

        $insert = function (array $list, int $categoryId, int $minStock, int $maxStock, int $threshold) {
            foreach ($list as $item) {
                MenuItem::create([
                    'category_id'        => $categoryId,
                    'name'               => $item[0],
                    'description'        => $item[1],
                    'price'              => $item[2],
                    'image'              => $item[3],
                    'stock_quantity'     => rand($minStock, $maxStock),
                    'low_stock_threshold'=> $threshold,
                    'is_available'       => true,
                ]);
            }
        };

        $insert($meals,     1, 20, 50,  5);
        $insert($snacks,    2, 30, 60, 10);
        $insert($beverages, 3, 50, 100, 15);
        $insert($desserts,  4, 15, 40,  5);
        $insert($combos,    5, 10, 25,  3);
    }
}