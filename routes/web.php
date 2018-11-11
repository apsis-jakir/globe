<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('get-allPlaces', 'CommonController@getAllPlaces')->name('captureSms');

Route::get('ManualProcessing','SmsInboxesController@ManualProcessing');

Route::post('sms-capture', 'SmsInboxesController@captureSms')->name('captureSms');
Route::get('sms-outbox-send','SmsInboxesController@sendOutboxSend')->name('outboxSmsSend');
Route::get('users', 'Auth\RegisterController@users')->name('users');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

Route::get('/show/{user}','Auth\RegisterController@show')
    ->name('user.show')
    ->where('id', '[0-9]+');
Route::get('/{user}/edit','Auth\RegisterController@edit')
    ->name('user.edit')
    ->where('id', '[0-9]+');
Route::put('user/{user}', 'Auth\RegisterController@update')
    ->name('user.update')
    ->where('id', '[0-9]+');

Route::delete('/user/{user}','Auth\RegisterController@destroy')
    ->name('user.destroy')
    ->where('id', '[0-9]+');

Route::get('/','Auth\LoginController@showLoginForm')->name('login');
Route::post('/login','Auth\LoginController@login');
Route::get('logout','Auth\LoginController@logout')->name('logout');
Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset','Auth\ResetPasswordController@reset');


Route::get('profile', 'Auth\RegisterController@profileForm')->name('profile');
Route::post('changepassword', 'Auth\RegisterController@changepassword')->name('changepassword');




/* Settings routing */
Route::get('settings/brand_skue','SettingsController@ordering_brand_skue');
Route::post('orderingBrandSkueAction','SettingsController@ordering_brand_skue_action');
Route::get('showSkue/{id?}','SettingsController@show_skue');
Route::post('orderingSkueAction','SettingsController@orderingSkueAction');
Route::get('promotions','SettingsController@setPromotions');
Route::get('promotionsList','SettingsController@promotions_list');
Route::post('promotionSubmit','SettingsController@promotion_submit');
Route::get('deletePromotions/{id}','SettingsController@delete_promotions');
Route::get('packageDetails/{id}','SettingsController@package_details');
Route::get('activeInactive/{id}/{is_active}','SettingsController@active_inactive');
Route::get('targetSet/{type}/{target_month?}/{base_date?}','SettingsController@target_set');
Route::post('targetSetProcess','SettingsController@target_set_process');
Route::post('targetSubmit','SettingsController@target_submit');
Route::get('targetRemove/{type}/{target_month}','SettingsController@remove_target');
//Route::get('showExistingSetting/{target_type}/{target_month}','SettingsController@show_existing_setting');
/* End setting routings */

Route::get('house-lifting-format','Lifting@getView');
Route::get('house-lifting-format-date/{ids}/{type}/{date}/{memo}','Lifting@getLiftingDateWise');
Route::post('house-lifting-format-search','Lifting@getLiftingSearch');

/**
 * Nirjhar Reporting
 */
Route::get('productivity-list/{type?}','ProductivityReport@productivityList');
Route::post('productivityListAjax/{type?}','ProductivityReport@productivityListAjax');
Route::post('digdownProductivityListAjax/{type?}','ProductivityReport@digdownProductivityListAjax');

Route::get('order-vs-sales/{type?}','OrderVSsalesReport@ordervssalelist');
Route::post('orderVSsalesAjax/{type?}','OrderVSsalesReport@orderVSsalesAjax');
Route::post('digdownOrderSaleListAjax/{type?}','OrderVSsalesReport@digdownOrderSaleListAjax');

Route::get('sales-reconciliation/{type?}','ReconciliationReport@SalesReconciliationList');
Route::post('SalesReconciliationAjax/{type?}','ReconciliationReport@SalesReconciliationAjax');
Route::post('digdownReconciliationAjax/{type?}','ReconciliationReport@digdownReconciliationAjax');

Route::get('monthly-achievement/{type?}','DssReport@monthlyAchvList');
Route::post('monthlyAchvAjax/{type?}','DssReport@monthlyAchvAjax');
Route::post('digdownDSSAjax/{type?}','DssReport@digdownDSSAjax');

Route::get('route-strike-rate/{type?}','RouteStrikeRate@RouteStrikeRateList');
Route::post('RouteStrikeRateAjax/{type?}','RouteStrikeRate@RouteStrikeRateAjax');

Route::get('ranking','RankingReport@rankingList');
Route::post('RankingAjax','RankingReport@RankingAjax');

Route::get('promotional-report/{type?}','PromotionalReport@PromotionalReportList');
Route::post('PromotionalReportAjax/{type?}','PromotionalReport@PromotionalReportAjax');
Route::post('digdownPromotionalReportAjax/{type?}','PromotionalReport@digdownPromotionalReportAjax');

/*==============================================*/


Route::group(
[
    'prefix' => 'product_brands',
], function () {

    Route::get('/', 'ProductBrandsController@index')
         ->name('product_brands.product_brand.index');

    Route::get('/create','ProductBrandsController@create')
         ->name('product_brands.product_brand.create');

    Route::get('/show/{productBrand}','ProductBrandsController@show')
         ->name('product_brands.product_brand.show')
         ->where('id', '[0-9]+');

    Route::get('/{productBrand}/edit','ProductBrandsController@edit')
         ->name('product_brands.product_brand.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'ProductBrandsController@store')
         ->name('product_brands.product_brand.store');
               
    Route::put('product_brand/{productBrand}', 'ProductBrandsController@update')
         ->name('product_brands.product_brand.update')
         ->where('id', '[0-9]+');

    Route::delete('/product_brand/{productBrand}','ProductBrandsController@destroy')
         ->name('product_brands.product_brand.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'product_categories',
], function () {

    Route::get('/', 'ProductCategoriesController@index')
         ->name('product_categories.product_category.index');

    Route::get('/create','ProductCategoriesController@create')
         ->name('product_categories.product_category.create');

    Route::get('/show/{productCategory}','ProductCategoriesController@show')
         ->name('product_categories.product_category.show')
         ->where('id', '[0-9]+');

    Route::get('/{productCategory}/edit','ProductCategoriesController@edit')
         ->name('product_categories.product_category.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'ProductCategoriesController@store')
         ->name('product_categories.product_category.store');
               
    Route::put('product_category/{productCategory}', 'ProductCategoriesController@update')
         ->name('product_categories.product_category.update')
         ->where('id', '[0-9]+');

    Route::delete('/product_category/{productCategory}','ProductCategoriesController@destroy')
         ->name('product_categories.product_category.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'products',
], function () {

    Route::get('/', 'ProductsController@index')
         ->name('products.product.index');

    Route::get('/create','ProductsController@create')
         ->name('products.product.create');

    Route::get('/show/{product}','ProductsController@show')
         ->name('products.product.show')
         ->where('id', '[0-9]+');

    Route::get('/{product}/edit','ProductsController@edit')
         ->name('products.product.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'ProductsController@store')
         ->name('products.product.store');
               
    Route::put('product/{product}', 'ProductsController@update')
         ->name('products.product.update')
         ->where('id', '[0-9]+');

    Route::delete('/product/{product}','ProductsController@destroy')
         ->name('products.product.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'countries',
], function () {

    Route::get('/', 'CountriesController@index')
         ->name('countries.country.index');

    Route::get('/create','CountriesController@create')
         ->name('countries.country.create');

    Route::get('/show/{country}','CountriesController@show')
         ->name('countries.country.show')
         ->where('id', '[0-9]+');

    Route::get('/{country}/edit','CountriesController@edit')
         ->name('countries.country.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'CountriesController@store')
         ->name('countries.country.store');
               
    Route::put('country/{country}', 'CountriesController@update')
         ->name('countries.country.update')
         ->where('id', '[0-9]+');

    Route::delete('/country/{country}','CountriesController@destroy')
         ->name('countries.country.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'divisions',
], function () {

    Route::get('/', 'DivisionsController@index')
         ->name('divisions.division.index');

    Route::get('/create','DivisionsController@create')
         ->name('divisions.division.create');

    Route::get('/show/{division}','DivisionsController@show')
         ->name('divisions.division.show')
         ->where('id', '[0-9]+');

    Route::get('/{division}/edit','DivisionsController@edit')
         ->name('divisions.division.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'DivisionsController@store')
         ->name('divisions.division.store');
               
    Route::put('division/{division}', 'DivisionsController@update')
         ->name('divisions.division.update')
         ->where('id', '[0-9]+');

    Route::delete('/division/{division}','DivisionsController@destroy')
         ->name('divisions.division.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'districts',
], function () {

    Route::get('/', 'DistrictsController@index')
         ->name('districts.district.index');

    Route::get('/create','DistrictsController@create')
         ->name('districts.district.create');

    Route::get('/show/{district}','DistrictsController@show')
         ->name('districts.district.show')
         ->where('id', '[0-9]+');

    Route::get('/{district}/edit','DistrictsController@edit')
         ->name('districts.district.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'DistrictsController@store')
         ->name('districts.district.store');
               
    Route::put('district/{district}', 'DistrictsController@update')
         ->name('districts.district.update')
         ->where('id', '[0-9]+');

    Route::delete('/district/{district}','DistrictsController@destroy')
         ->name('districts.district.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'upazilas',
], function () {

    Route::get('/', 'UpazilasController@index')
         ->name('upazilas.upazila.index');

    Route::get('/create','UpazilasController@create')
         ->name('upazilas.upazila.create');

    Route::get('/show/{upazila}','UpazilasController@show')
         ->name('upazilas.upazila.show')
         ->where('id', '[0-9]+');

    Route::get('/{upazila}/edit','UpazilasController@edit')
         ->name('upazilas.upazila.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'UpazilasController@store')
         ->name('upazilas.upazila.store');
               
    Route::put('upazila/{upazila}', 'UpazilasController@update')
         ->name('upazilas.upazila.update')
         ->where('id', '[0-9]+');

    Route::delete('/upazila/{upazila}','UpazilasController@destroy')
         ->name('upazilas.upazila.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'unions',
], function () {

    Route::get('/', 'UnionsController@index')
         ->name('unions.union.index');

    Route::get('/create','UnionsController@create')
         ->name('unions.union.create');

    Route::get('/show/{union}','UnionsController@show')
         ->name('unions.union.show')
         ->where('id', '[0-9]+');

    Route::get('/{union}/edit','UnionsController@edit')
         ->name('unions.union.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'UnionsController@store')
         ->name('unions.union.store');
               
    Route::put('union/{union}', 'UnionsController@update')
         ->name('unions.union.update')
         ->where('id', '[0-9]+');

    Route::delete('/union/{union}','UnionsController@destroy')
         ->name('unions.union.destroy')
         ->where('id', '[0-9]+');

});


Route::get('/menuList','MenuController@menuList')->name('menuList');


Route::get('/getModuleList','ModuleController@getModuleList')->name('getModuleList');
// module changer
Route::get('/moduleChanger/{id}','ModuleController@moduleChanger')->name('moduleChanger');

Route::post('/dashboard', function () {
    return view('pages.dashboard');
});

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('dashboard-target-outlet','HomeController@dashboardTargetOutlet');
Route::get('dashboard-visited-outlet','HomeController@dashboardVisitedOutlet');
Route::get('dashboard-successfull-call','HomeController@dashboardSuccessfullCall');
Route::get('dashboard-no-lifter','HomeController@dashboardNoLifter');
Route::get('dashboard-no-orders','HomeController@dashboardNoOrders');
Route::get('dashboard-no-sales','HomeController@dashboardNoSales');
Route::get('dashboard-strike-rate','HomeController@dashboardStrikeRate');
Route::post('dashboard-brand-wise-productivity','HomeController@brandWiseProductivity');




Route::group(
[
    'prefix' => 'posts',
], function () {

    Route::get('/', 'PostsController@index')
         ->name('posts.post.index');

    Route::get('/create','PostsController@create')
         ->name('posts.post.create');

    Route::get('/show/{post}','PostsController@show')
         ->name('posts.post.show')
         ->where('id', '[0-9]+');

    Route::get('/{post}/edit','PostsController@edit')
         ->name('posts.post.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'PostsController@store')
         ->name('posts.post.store');
               
    Route::put('post/{post}', 'PostsController@update')
         ->name('posts.post.update')
         ->where('id', '[0-9]+');

    Route::delete('/post/{post}','PostsController@destroy')
         ->name('posts.post.destroy')
         ->where('id', '[0-9]+');

});
Route::group(
[
    'prefix' => 'abcs',
], function () {

    Route::get('/', 'AbcsController@index')
         ->name('abcs.abc.index');

    Route::get('/create','AbcsController@create')
         ->name('abcs.abc.create');

    Route::get('/show/{abc}','AbcsController@show')
         ->name('abcs.abc.show')
         ->where('id', '[0-9]+');

    Route::get('/{abc}/edit','AbcsController@edit')
         ->name('abcs.abc.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'AbcsController@store')
         ->name('abcs.abc.store');

    Route::put('abc/{abc}', 'AbcsController@update')
         ->name('abcs.abc.update')
         ->where('id', '[0-9]+');

    Route::delete('/abc/{abc}','AbcsController@destroy')
         ->name('abcs.abc.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'zones',
], function () {

    Route::get('/', 'ZonesController@index')
         ->name('zones.zone.index');

    Route::get('/create','ZonesController@create')
         ->name('zones.zone.create');

    Route::get('/show/{zone}','ZonesController@show')
         ->name('zones.zone.show')
         ->where('id', '[0-9]+');

    Route::get('/{zone}/edit','ZonesController@edit')
         ->name('zones.zone.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'ZonesController@store')
         ->name('zones.zone.store');

    Route::put('zone/{zone}', 'ZonesController@update')
         ->name('zones.zone.update')
         ->where('id', '[0-9]+');

    Route::delete('/zone/{zone}','ZonesController@destroy')
         ->name('zones.zone.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'departments',
], function () {

    Route::get('/', 'DepartmentsController@index')
         ->name('departments.department.index');

    Route::get('/create','DepartmentsController@create')
         ->name('departments.department.create');

    Route::get('/show/{department}','DepartmentsController@show')
         ->name('departments.department.show')
         ->where('id', '[0-9]+');

    Route::get('/{department}/edit','DepartmentsController@edit')
         ->name('departments.department.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'DepartmentsController@store')
         ->name('departments.department.store');

    Route::put('department/{department}', 'DepartmentsController@update')
         ->name('departments.department.update')
         ->where('id', '[0-9]+');

    Route::delete('/department/{department}','DepartmentsController@destroy')
         ->name('departments.department.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'regions',
], function () {

    Route::get('/', 'RegionsController@index')
         ->name('regions.region.index');

    Route::get('/create','RegionsController@create')
         ->name('regions.region.create');

    Route::get('/show/{region}','RegionsController@show')
         ->name('regions.region.show')
         ->where('id', '[0-9]+');

    Route::get('/{region}/edit','RegionsController@edit')
         ->name('regions.region.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'RegionsController@store')
         ->name('regions.region.store');

    Route::put('region/{region}', 'RegionsController@update')
         ->name('regions.region.update')
         ->where('id', '[0-9]+');

    Route::delete('/region/{region}','RegionsController@destroy')
         ->name('regions.region.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'territories',
], function () {

    Route::get('/', 'TerritoriesController@index')
         ->name('territories.territorie.index');

    Route::get('/create','TerritoriesController@create')
         ->name('territories.territorie.create');

    Route::get('/show/{territorie}','TerritoriesController@show')
         ->name('territories.territorie.show')
         ->where('id', '[0-9]+');

    Route::get('/{territorie}/edit','TerritoriesController@edit')
         ->name('territories.territorie.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'TerritoriesController@store')
         ->name('territories.territorie.store');

    Route::put('territorie/{territorie}', 'TerritoriesController@update')
         ->name('territories.territorie.update')
         ->where('id', '[0-9]+');

    Route::delete('/territorie/{territorie}','TerritoriesController@destroy')
         ->name('territories.territorie.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'distribution_houses',
], function () {

    Route::get('/', 'DistributionHousesController@index')
         ->name('distribution_houses.distribution_house.index');

    Route::get('/create','DistributionHousesController@create')
         ->name('distribution_houses.distribution_house.create');

    Route::get('/show/{distributionHouse}','DistributionHousesController@show')
         ->name('distribution_houses.distribution_house.show')
         ->where('id', '[0-9]+');

    Route::get('/{distributionHouse}/edit','DistributionHousesController@edit')
         ->name('distribution_houses.distribution_house.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'DistributionHousesController@store')
         ->name('distribution_houses.distribution_house.store');

    Route::put('distribution_house/{distributionHouse}', 'DistributionHousesController@update')
         ->name('distribution_houses.distribution_house.update')
         ->where('id', '[0-9]+');

    Route::delete('/distribution_house/{distributionHouse}','DistributionHousesController@destroy')
         ->name('distribution_houses.distribution_house.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'territories',
], function () {

    Route::get('/', 'TerritoriesController@index')
         ->name('territories.territory.index');

    Route::get('/create','TerritoriesController@create')
         ->name('territories.territory.create');

    Route::get('/show/{territory}','TerritoriesController@show')
         ->name('territories.territory.show')
         ->where('id', '[0-9]+');

    Route::get('/{territory}/edit','TerritoriesController@edit')
         ->name('territories.territory.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'TerritoriesController@store')
         ->name('territories.territory.store');

    Route::put('territory/{territory}', 'TerritoriesController@update')
         ->name('territories.territory.update')
         ->where('id', '[0-9]+');

    Route::delete('/territory/{territory}','TerritoriesController@destroy')
         ->name('territories.territory.destroy')
         ->where('id', '[0-9]+');

});



Route::group(
[
    'prefix' => 'categories',
], function () {

    Route::get('/', 'CategoriesController@index')
         ->name('categories.category.index');

    Route::get('/create','CategoriesController@create')
         ->name('categories.category.create');

    Route::get('/show/{category}','CategoriesController@show')
         ->name('categories.category.show')
         ->where('id', '[0-9]+');

    Route::get('/{category}/edit','CategoriesController@edit')
         ->name('categories.category.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'CategoriesController@store')
         ->name('categories.category.store');

    Route::put('category/{category}', 'CategoriesController@update')
         ->name('categories.category.update')
         ->where('id', '[0-9]+');

    Route::delete('/category/{category}','CategoriesController@destroy')
         ->name('categories.category.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'product_types',
], function () {

    Route::get('/', 'ProductTypesController@index')
         ->name('product_types.product_type.index');

    Route::get('/create','ProductTypesController@create')
         ->name('product_types.product_type.create');

    Route::get('/show/{productType}','ProductTypesController@show')
         ->name('product_types.product_type.show')
         ->where('id', '[0-9]+');

    Route::get('/{productType}/edit','ProductTypesController@edit')
         ->name('product_types.product_type.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'ProductTypesController@store')
         ->name('product_types.product_type.store');

    Route::put('product_type/{productType}', 'ProductTypesController@update')
         ->name('product_types.product_type.update')
         ->where('id', '[0-9]+');

    Route::delete('/product_type/{productType}','ProductTypesController@destroy')
         ->name('product_types.product_type.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'skues',
], function () {

    Route::get('/', 'SkuesController@index')
         ->name('skues.skue.index');

    Route::get('/create','SkuesController@create')
         ->name('skues.skue.create');

    Route::get('/show/{skue}','SkuesController@show')
         ->name('skues.skue.show')
         ->where('id', '[0-9]+');

    Route::get('/{skue}/edit','SkuesController@edit')
         ->name('skues.skue.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'SkuesController@store')
         ->name('skues.skue.store');

    Route::put('skue/{skue}', 'SkuesController@update')
         ->name('skues.skue.update')
         ->where('id', '[0-9]+');

    Route::delete('/skue/{skue}','SkuesController@destroy')
         ->name('skues.skue.destroy')
         ->where('id', '[0-9]+');

});

Route::get('/parse-sms','Sms@parseSms');

Route::post('/sms-inbox','Sms@smsInbox');

Route::group(
[
    'prefix' => 'brands',
], function () {

    Route::get('/', 'BrandsController@index')
         ->name('brands.brand.index');

    Route::get('/create','BrandsController@create')
         ->name('brands.brand.create');

    Route::get('/show/{brand}','BrandsController@show')
         ->name('brands.brand.show')
         ->where('id', '[0-9]+');

    Route::get('/{brand}/edit','BrandsController@edit')
         ->name('brands.brand.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'BrandsController@store')
         ->name('brands.brand.store');

    Route::put('brand/{brand}', 'BrandsController@update')
         ->name('brands.brand.update')
         ->where('id', '[0-9]+');

    Route::delete('/brand/{brand}','BrandsController@destroy')
         ->name('brands.brand.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'file_uploads',
], function () {

    Route::get('/', 'FileUploadsController@index')
         ->name('file_uploads.file_upload.index');

    Route::get('/create','FileUploadsController@create')
         ->name('file_uploads.file_upload.create');

    Route::get('/show/{fileUpload}','FileUploadsController@show')
         ->name('file_uploads.file_upload.show')
         ->where('id', '[0-9]+');

    Route::get('/{fileUpload}/edit','FileUploadsController@edit')
         ->name('file_uploads.file_upload.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'FileUploadsController@store')
         ->name('file_uploads.file_upload.store');

    Route::put('file_upload/{fileUpload}', 'FileUploadsController@update')
         ->name('file_uploads.file_upload.update')
         ->where('id', '[0-9]+');

    Route::delete('/file_upload/{fileUpload}','FileUploadsController@destroy')
         ->name('file_uploads.file_upload.destroy')
         ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'sms_inboxes',
], function () {

    Route::get('/', 'SmsInboxesController@index')
         ->name('sms_inboxes.sms_inbox.index');

    Route::get('/create','SmsInboxesController@create')
         ->name('sms_inboxes.sms_inbox.create');

    Route::get('/show/{smsInbox}','SmsInboxesController@show')
         ->name('sms_inboxes.sms_inbox.show')
         ->where('id', '[0-9]+');

    Route::get('/{smsInbox}/edit','SmsInboxesController@edit')
         ->name('sms_inboxes.sms_inbox.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'SmsInboxesController@store')
         ->name('sms_inboxes.sms_inbox.store');

    Route::put('sms_inbox/{smsInbox}', 'SmsInboxesController@update')
         ->name('sms_inboxes.sms_inbox.update')
         ->where('id', '[0-9]+');

    Route::delete('/sms_inbox/{smsInbox}','SmsInboxesController@destroy')
         ->name('sms_inboxes.sms_inbox.destroy')
         ->where('id', '[0-9]+');
    Route::get('/{smsInbox}/process','SmsInboxesController@process')
        ->name('sms_inboxes.sms_inbox.process')
        ->where('id', '[0-9]+');

});

Route::group(
[
    'prefix' => 'sms_outboxxes',
], function () {

    Route::get('/', 'SmsOutboxxesController@index')
         ->name('sms_outboxxes.sms_outboxx.index');

    Route::get('/create','SmsOutboxxesController@create')
         ->name('sms_outboxxes.sms_outboxx.create');

    Route::get('/show/{smsOutboxx}','SmsOutboxxesController@show')
         ->name('sms_outboxxes.sms_outboxx.show')
         ->where('id', '[0-9]+');

    Route::get('/{smsOutboxx}/edit','SmsOutboxxesController@edit')
         ->name('sms_outboxxes.sms_outboxx.edit')
         ->where('id', '[0-9]+');

    Route::post('/', 'SmsOutboxxesController@store')
         ->name('sms_outboxxes.sms_outboxx.store');
               
    Route::put('sms_outboxx/{smsOutboxx}', 'SmsOutboxxesController@update')
         ->name('sms_outboxxes.sms_outboxx.update')
         ->where('id', '[0-9]+');

    Route::delete('/sms_outboxx/{smsOutboxx}','SmsOutboxxesController@destroy')
         ->name('sms_outboxxes.sms_outboxx.destroy')
         ->where('id', '[0-9]+');

});
/*==========================================================================================*/
/*==========================================================================================*/
/*==========================================================================================*/

Route::get('order-vs-sale-primary','ReportsController@order_vs_sale_primary');

Route::get('order-vs-sale-primary','ReportsController@orderVsSalePrimary');
Route::post('order-vs-sale-primary-search','ReportsController@orderVsSalePrimarySearch');
Route::get('order-vs-sale-primary-date-wise/{house_id}/{post_date}','ReportsController@orderVsSalePrimaryDateWise');
Route::post('order-vs-sale-primary-date-wise-search/{house_id}','ReportsController@orderVsSalePrimaryDateWiseSearch');

Route::get('order-vs-sale-secondary','ReportsController@orderVsSaleSecondary');
Route::post('order-vs-sale-secondary-search','ReportsController@orderVsSaleSecondaryAjax');

Route::get('order-vs-sale-secondary-aso/{dbid}/{postdata}','ReportsController@orderVsSaleSecondaryAso');
Route::post('order-vs-sale-secondary-aso-search/{dbid}','ReportsController@orderVsSaleSecondaryAsoSearch');

Route::get('order-vs-sale-secondary-route/{aso_id}/{postdata}','ReportsController@orderVsSaleSecondaryRoute');
Route::post('order-vs-sale-secondary-route-search/{aso_id}','ReportsController@orderVsSaleSecondaryRouteSearch');

Route::get('order-vs-sale-secondary-date/{aso_id}/{route_id}/{postdata}','ReportsController@orderVsSaleSecondaryDate');
Route::post('order-vs-sale-secondary-date-search/{aso_id}/{route_id}','ReportsController@orderVsSaleSecondaryDateSearch');


Route::get('daily-sale-summary','ReportsController@dailySaleSummary');
Route::post('daily-sale-summary-search','ReportsController@dailySaleSummarySearch');
Route::get('daily-sale-summary-route/{aso_id}/{post_data}','ReportsController@dailySaleSummaryRoute');

Route::get('monthly-sale-reconciliation','ReportsController@saleConciliation');
Route::post('monthly-sale-reconciliation-search','ReportsController@saleConciliationSearch');

Route::get('sales_list/{type}','ReportsController@salesList');
Route::post('salesListAjax/{type}','ReportsController@sales_list_ajax');
Route::get('sales-details/{type}/{id}','ReportsController@salesDetails');

/**======================*/
//ranking
//Route::get('ranking','ReportsController@ranking');
//Route::post('ranking-ajax','ReportsController@rankingAjax');

Route::get('ranking','RankingReport@rankingList');
Route::post('RankingAjax','RankingReport@RankingAjax');

Route::get('route-wise-performence-by-category','ReportsController@routeWisePerformenceByCategory');
Route::post('route-wise-performence-by-category-ajax','ReportsController@routeWisePerformenceByCategoryAjax');

Route::get('route-wise-performence-category/{db_id?}/{post_data?}','ReportsController@routeWisePerformenceCategory');
Route::get('individual-route-performance/{route_id}/{selected_months}/{type?}','ReportsController@individualRoutePerformance');
Route::post('route-wise-performence-category-ajax','ReportsController@routeWisePerformenceCategoryAjax');

Route::get('strike-rate','ReportsController@strikeRateByCategory');
Route::post('strike-rate-search','ReportsController@strikeRateByCategoryAjax');

Route::get('monthly-sale-reconc','ReportsController@monthlySaleReconcilation');
Route::post('monthly-sale-reconc-search','ReportsController@monthlySaleReconcilationAjax');

Route::get('sale-summary-month','ReportsController@saleSummaryMonth');
Route::post('sale-summary-month-search','ReportsController@saleSummaryMonthAjax');

Route::get('sms-outboxes','SmsOutboxesController@smsOutboxes');
Route::post('sms-outboxes-ajax','SmsOutboxesController@smsOutboxesAjax');

//Route::get('brand-wise-sales','ReportsController@brandWiseSale');
//Route::post('brand-wise-sale-ajax','ReportsController@brandWiseSaleSearch');

Route::get('brand-wise-sales','BrandwisesalesController@brandWiseSale');
Route::post('brand-wise-sale-ajax','BrandwisesalesController@brandWiseSaleSearch');

/* Report routing */
Route::get('order-list/{type?}','ReportsController@order_list');
Route::post('orderListAjax/{type?}','ReportsController@order_list_ajax');
Route::post('check-distribution-balack','ReportsController@check_distribution_balack');
Route::get('primary-order-details/{type}/{id}','ReportsController@primary_order_details');
Route::post('get-pack-size_quantity','ReportsController@getPackSizeQuanity');
Route::post('primary-sales-create','ReportsController@primary_sales_create');
Route::post('secondary-sales-create','ReportsController@secondary_sales_create');
Route::post('update-secondary-order','ReportsController@updateSecondaryOrder');

//Reports
Route::get('house-stock','ReportsController@houseStock');
Route::post('house-stock-search','ReportsController@houseStockSearch');

Route::get('current-stock','CurrentStock@current_stock');
Route::post('current-stock-search','CurrentStock@currentStockSearch');
Route::get('stock-memo/{house_id}/{table}/{field_name}','ReportsController@houseStockMemo');




Route::get('house-stock','ReportsController@houseStock');
Route::post('house-stock-search','ReportsController@houseStockSearch');
Route::get('house-stock-memo/{house_id}/{table}/{field_name}','ReportsController@houseStockMemo');
Route::get('db-wise-performance','ReportsController@houseWisePerformance');
Route::post('db-wise-performance-search','ReportsController@houseWisePerformanceSearch');

Route::get('test-url','ReportsController@test_url');



//Route::get('lifting-statement','ReportsController@liftingStatement');
//Route::post('lifting-statement-ajax','ReportsController@liftingStatementAjax');

Route::get('lifting-statement','LiftingstatementController@liftingStatement');
Route::post('lifting-statement-ajax','LiftingstatementController@liftingStatementAjax');


//Route::get('pending-order','ReportsController@pendingOrder');
//Route::post('pending-order-ajax','ReportsController@pendingOrderAjax');
//Route::get('pending-order-details/{field_name}/{field_id}/{config}','ReportsController@pendingOrderDetailsAjax');


Route::get('pending-order','PendingorderController@pendingOrder');
Route::post('pending-order-ajax','PendingorderController@pendingOrderAjax');
Route::get('pending-order-details/{field_name}/{field_id}/{config}','PendingorderController@pendingOrderDetailsAjax');




//Route::get('target-statement','ReportsController@targetStatement');
//Route::post('target-statement-ajax','ReportsController@targetStatementAjax');



Route::get('target-statement','TargetstatementController@targetStatement');
Route::post('target-statement-ajax','TargetstatementController@targetStatementAjax');

//promotional
Route::get('promotional-list','PromotionalController@promotionalSaleList');
Route::post('promotional-list-search','PromotionalController@promotionalSaleListAjax');
Route::get('promotional-sale-details/{id}','PromotionalController@saleDetails');
Route::post('update-promotional-sale','PromotionalController@updatePromotionalSale');