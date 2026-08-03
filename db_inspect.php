<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo 'categories total: ' . DB::table('categories')->count() . PHP_EOL;
echo 'categories active(1): ' . DB::table('categories')->where('status', 1)->count() . PHP_EOL;
echo 'categories inactive(0): ' . DB::table('categories')->where('status', 0)->count() . PHP_EOL;
echo 'categories with parent_id: ' . DB::table('categories')->whereNotNull('parent_id')->count() . PHP_EOL;

echo 'brands total: ' . DB::table('brands')->count() . PHP_EOL;
echo 'brands active(1): ' . DB::table('brands')->where('status', 1)->count() . PHP_EOL;
echo 'brands inactive(0): ' . DB::table('brands')->where('status', 0)->count() . PHP_EOL;

echo 'products total: ' . DB::table('products')->count() . PHP_EOL;
echo 'products active(1): ' . DB::table('products')->where('status', 1)->count() . PHP_EOL;

foreach (DB::table('categories')->select('id','name','parent_id','status')->get() as $row) {
    echo "cat {$row->id}: {$row->name} parent=" . ($row->parent_id ?? 'NULL') . " status={$row->status}" . PHP_EOL;
}
foreach (DB::table('brands')->select('id','name','status')->get() as $row) {
    echo "brand {$row->id}: {$row->name} status={$row->status}" . PHP_EOL;
}
