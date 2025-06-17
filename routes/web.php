<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Backend\{
    MailController,
    HomeController,
    ItemController,
    AddonTypeController,
    ServeTableController,
    MenuController,
    RoleController,
    UserController,
    KitchenController,
    OrderController,
    ModuleController,
    AppUserController,
    RestaurantController,
    GeneralSettingController,
    ExportController,
    
};
use App\Http\Controllers\FrontEnd\{
    IndexController,   
};

use App\Http\Controllers\BlogController;
use App\Http\Controllers\LawSController;
use App\Http\Controllers\SlidderController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CompanyDetailController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\FounderController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\MissionController;  
use App\Http\Controllers\VissionController;  
use App\Http\Controllers\ChooseUsController;  
use App\Http\Controllers\ClientReviewController;  

Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return redirect()->route('dashboard')->with('doneMessage', 'Cache Cleared');
})->name('cacheClear');

Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('sendbasicemail', [MailController::class, 'basic_email']);
Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth'])->name('dashboard');

    // Add Service
        // Route::get('/restaurants/services',[ItemController::class,'create_service']);
        // Route::post('/restaurants/services',[ItemController::class,'store_service'])->name('services.store');
        Route::resource('law-services', LawSController::class);
        Route::get('inactive-service/{id}', [LawSController::class, 'makeInActive'])->name('service-inactive');
        Route::get('service-active/{id}', [LawSController::class, 'makeActive'])->name('service-active');
        Route::get('services/admin-datatable', [LawSController::class, 'service_datatable'])->name('services.service_datatable');

    // Add Service

    // Blogs Routes

        Route::resource('blog-post',BlogController::class);
        Route::get('inactive-blog/{id}', [BlogController::class, 'makeInActive'])->name('blog-inactive');
        Route::get('blog-active/{id}', [BlogController::class, 'makeActive'])->name('blog-active');
        Route::get('blog/blog-post', [BlogController::class, 'service_datatable'])->name('blog-post-table');
    // Blogs Routes

    // Slidder Routes
    Route::resource('/slidder',SlidderController::class);
    Route::get('slidder-posts', [SlidderController::class, 'service_datatable'])->name('slidder-post-table');
    Route::get('slidder-active/{id}', [SlidderController::class, 'makeActive'])->name('slidder-active');
    Route::get('slidder-inactive/{id}', [SlidderController::class, 'makeInActive'])->name('slidder-inactive');
    // Slidder Routes


    // Logo Controller 
    Route::resource('/web-logo',LogoController::class);
    Route::get('weblogos', [LogoController::class, 'service_datatable'])->name('weblogos');
    // Logo Controller 

    // Contact Routes
    Route::resource('contact',ContactController::class);
    Route::get('/contact-record', [ContactController::class, 'service_datatable'])->name('contact-record');
    // Contact Routes

    // Contact Routes
    Route::resource('client_review',ClientReviewController::class);
    Route::get('/client_review_record', [ClientReviewController::class, 'service_datatable'])->name('client_review_record');
    // Contact Routes


    // Company Detail
    Route::resource('company-detail',CompanyDetailController::class);
    Route::get('/company-record', [CompanyDetailController::class, 'service_datatable'])->name('company-record');   
    Route::get('/company-detail-active/{id}', [CompanyDetailController::class, 'makeActive'])->name('company-detail-active');
    Route::get('/company-detail-inactive/{id}', [CompanyDetailController::class, 'makeInActive'])->name('company-detail-inactive');
    // Company Detail

    // Our Team
    Route::resource('our-team',TeamController::class);
    Route::get('/our-team-active/{id}', [TeamController::class, 'makeActive'])->name('our-team-active');
    Route::get('/our-team-inactive/{id}', [TeamController::class, 'makeInActive'])->name('our-team-inactive');
    Route::get('/our-team-record', [TeamController::class, 'service_datatable'])->name('our-team-record');   
    // Our Team

    // Questions
    Route::resource('question',QuestionController::class);
    Route::get('/question-active/{id}', [QuestionController::class, 'makeActive'])->name('question-active');
    Route::get('/question-inactive/{id}', [QuestionController::class, 'makeInActive'])->name('question-inactive');
    Route::get('/question-record', [QuestionController::class, 'service_datatable'])->name('question-record'); 
    // Questions

    // About
    Route::resource('about',AboutController::class);
    Route::get('/about-active/{id}', [AboutController::class, 'makeActive'])->name('about-active');
    Route::get('/about-inactive/{id}', [AboutController::class, 'makeInActive'])->name('about-inactive');
    Route::get('/about-record', [AboutController::class, 'service_datatable'])->name('about-record'); 
    // About


    // Case Study
    Route::resource('case-study',CaseStudyController::class);
    Route::get('/case-study-record', [CaseStudyController::class, 'service_datatable'])->name('case-study-record'); 
    // Case Study

    // Case Study
    Route::resource('founder',FounderController::class);
    Route::get('/founder-record', [FounderController::class, 'service_datatable'])->name('founder-record'); 
    // Case Study


    // Certificate 
    Route::resource('certificate',CertificateController::class); 
    Route::get('/certificate-record', [CertificateController::class, 'service_datatable'])->name('certificate-record'); 
    // Certificate  


    // Mission 
    Route::resource('mission',MissionController::class); 
    Route::get('/mission-record', [MissionController::class, 'service_datatable'])->name('mission-record'); 
    // Mission 

    // Mission 
    Route::resource('vission',VissionController::class); 
    Route::get('/vission-record', [VissionController::class, 'service_datatable'])->name('vission-record'); 
    // Mission 


    // Why Choose Us
    Route::resource('chooseus',ChooseUsController::class);
    Route::get('/chooseus-record',[ChooseUsController::class, 'service_datatable'])->name('chooseus-record');
    Route::delete('/delete-field/{id}', [ChooseUsController::class, 'removefield'])->name('remove.field');
    // Why Choose Us
    Route::prefix('front-end')->name('front.')->group(function () {
        Route::get('/', [IndexController::class, 'index'])->name('home');
        Route::get('/blog-detail/{id}', [IndexController::class, 'blogdetail'])->name('about');
        Route::get('/all-blogs', [IndexController::class,'AllBlogs'])->name('allblogs');
        Route::get('/all-services',[IndexController::class,'AllServices'])->name('allservices');
        Route::get('/single-services/{id}',[IndexController::class,'SingleService'])->name('singleservices');
        Route::post('/set-language', [IndexController::class, 'setLanguage'])->name('setLanguage');
        // Arabic Pages

        Route::get('/arabicPage',[IndexController::class,'arabicPage'])->name('arabicPage');
        Route::get('/single-arabic-blog/{id}',[IndexController::class,'Single_Arabic_Blog'])->name('single_arabic_blog');
        Route::get('/all-arabicblogs', [IndexController::class,'AllArabicBlogs'])->name('allarabicblogs');
        Route::get('/arabic-services',[IndexController::class,'arabicservice'])->name('arabic_service');
        Route::get('/single-arabic-services/{id}',[IndexController::class,'singlearabicservice'])->name('single_arabic_service');

    });
    
    // Laanguage Routes
    // Laanguage Routes


// users routes
Route::middleware(['auth'])->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user_id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::get('/{user_id}/view', [UserController::class, 'show'])->name('users.show');
    Route::get('/{user_id}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::get('/{user_id}/reject', [UserController::class, 'reject'])->name('users.reject');

    Route::get('/{option}/set-theme', [UserController::class, 'setTheme'])->name('users.theme');
    Route::get('/{option}/set-header', [UserController::class, 'setHeader'])->name('users.header');
    Route::get('/{option}/set-sidebar', [UserController::class, 'setSidebar'])->name('users.sidebar');

    Route::get('/changePassword', [UserController::class, 'changePassword'])->name('users.changePassword');
    Route::post('/updatePassword', [UserController::class, 'updatePassword'])->name('users.updatePassword');
    Route::post('/modify/{user_id}', [UserController::class, 'update'])->name('users.update');
    /* Route::get('userPermissions/{id}',[UserController::class, 'userPermissions'])->name('userPermissions');
      Route::post('userPermissionsSubmit',[UserController::class, 'userPermissionsSubmit'])->name('userPermissionsSubmit'); */

    Route::get('datatable', [UserController::class, 'datatable'])->name('users_datatable');
    Route::get('show-application/{id}', [UserController::class, 'show_application'])->name('users_show_application');
    
    Route::get('inactive-listing', [UserController::class, 'inactive_listing'])->name('users-inactive-listing');
    Route::get('active-listing', [UserController::class, 'active_listing'])->name('users-active-listing');
    
    Route::get('inactive/{id}', [UserController::class, 'makeInActive'])->name('users-inactive');
    Route::get('active/{id}', [UserController::class, 'makeActive'])->name('users-active');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    //Modules
    Route::prefix('modules')->group(function () {
        Route::get('datatable', [ModuleController::class, 'datatable'])->name('modules_datatable');
        Route::get('deactivate/{id}', [ModuleController::class, 'makeInActive'])->name('modules-deactivate');
        Route::get('activate/{id}', [ModuleController::class, 'makeActive'])->name('modules-activate');
    });
        Route::resource('modules', ModuleController::class);

    //Roles
    Route::prefix('roles')->group(function () {
        Route::get('datatable', [RoleController::class, 'datatable'])->name('roles_datatable');
        Route::get('deactivate/{id}', [RoleController::class, 'makeInActive'])->name('roles-deactivate');
        Route::get('activate/{id}', [RoleController::class, 'makeActive'])->name('roles-activate');
        Route::post('permissions/{id}', [RoleController::class, 'permission_update'])->name('permissions_update');
    });
        Route::resource('roles', RoleController::class);

    //App General Settings
    Route::prefix('general-settings')->group(function () {
        Route::get('datatable', [GeneralSettingController::class, 'datatable'])->name('general_settings_datatable');
        Route::get('deactivate/{id}', [GeneralSettingController::class, 'makeInActive'])->name('general_settings-deactivate');
        Route::get('activate/{id}', [GeneralSettingController::class, 'makeActive'])->name('general_settings-activate');
        Route::get('startRamadan', [GeneralSettingController::class, 'startRamadan'])->name('general_settings-startRamadan');
        Route::get('endRamadan', [GeneralSettingController::class, 'endRamadan'])->name('general_settings-endRamadan');
        Route::get('edit-Settings', [GeneralSettingController::class, 'edit'])->name('general_settings_edit_Settings');
        Route::post('update-Settings', [GeneralSettingController::class, 'update'])->name('general_settings_update_Settings');
    });
        Route::resource('general-settings', GeneralSettingController::class);

    //App users Details
    Route::prefix('app-users')->group(function () {
        Route::get('inactive-listing', [AppUserController::class, 'inactive_listing'])->name('app_users-inactive-listing');
        Route::get('active-listing', [AppUserController::class, 'active_listing'])->name('app_users-active-listing');
        Route::get('app_user_favourite_datatable/{id}', [AppUserController::class, 'app_user_favourite_datatable'])->name('app_user_favourite_datatable');
        Route::get('review_datatable/{id}', [AppUserController::class, 'review_datatable'])->name('review_datatable');
        Route::get('order_datatable/{id}', [AppUserController::class, 'order_datatable'])->name('order_datatable');
        Route::get('quries_datatable/{id}', [AppUserController::class, 'quries_datatable'])->name('quries_datatable');
        Route::get('datatable', [AppUserController::class, 'datatable'])->name('app_users_datatable');
        Route::get('inactive/{id}', [AppUserController::class, 'makeInActive'])->name('app_users-inactive');
        Route::get('active/{id}', [AppUserController::class, 'makeActive'])->name('app_users-active');
        Route::get('toggleStatus/{id}/{status}', [AppUserController::class, 'toggleStatus'])->name('app_users_toggleStatus');
    });
        Route::resource('app-users', AppUserController::class);

    // Restaurants
    Route::prefix('restaurants')->group(function () {
        Route::get('{rest_id}/menus.json', function ($res_id) {
            $menus = App\Models\Menu::where(['rest_id' => $res_id, 'status' => 1])->get();
            return $menus;
        });

        Route::get('{rest_id}/items.json', function ($res_id) {
            $menus_ids = array();
            $menus_ids[] = 0;
            $menus = App\Models\Menu::where(['rest_id' => $res_id])->get();
            foreach ($menus as $menu) {
                $menus_ids[] = $menu->id;
            }
            $items = App\Models\Items::whereIn('menu_id', $menus_ids)->get();
            return $items;
        });
        
        Route::get('inactive-listing', [RestaurantController::class, 'inactive_listing'])->name('restaurants-inactive-listing');
        Route::get('active-listing', [RestaurantController::class, 'active_listing'])->name('restaurants-active-listing');

        Route::post('menus/order/{id}', [RestaurantController::class, 'saveOrderMenus'])->name('restaurants_menus.order');
        Route::get('datatable', [RestaurantController::class, 'datatable'])->name('restaurants_datatable');
        Route::get('order_datatable/{id}', [RestaurantController::class, 'order_datatable'])->name('rest_order_datatable');
        Route::get('opening/{rest_id}', [RestaurantController::class, 'get_opening_time'])->name('get_opening_time');
        Route::get('menu-datatable/{id}', [RestaurantController::class, 'menu_datatable'])->name('restaurants_menu_datatable');
        Route::get('index', [RestaurantController::class, 'index'])->name('restaurant.create');
        Route::get('close/{id}', [RestaurantController::class, 'makeClose'])->name('restaurant-close');
        Route::get('open/{id}', [RestaurantController::class, 'makeOpen'])->name('restaurant-open');
        Route::get('busy/{id}', [RestaurantController::class, 'makeBusy'])->name('restaurant-busy');
        Route::get('kitchen/close/{id}', [RestaurantController::class, 'makeKitchenClose'])->name('kitchen-restaurant-close');
        Route::get('kitchen/open/{id}', [RestaurantController::class, 'makeKitchenOpen'])->name('kitchen-restaurant-open');
        Route::get('kitchen/busy/{id}', [RestaurantController::class, 'makeKitchenBusy'])->name('kitchen-restaurant-busy');
        Route::get('kitchen/busy/{id}/{minutes}', [RestaurantController::class, 'makeKitchenBusy'])->name('kitchen-restaurant-busy-minutes');
        Route::get('kitchen/not-busy/{id}', [RestaurantController::class, 'makeKitchenNotBusy'])->name('kitchen-restaurant-not-busy');
        Route::get('inactive/{id}', [RestaurantController::class, 'makeInActive'])->name('restaurant-inactive');
        Route::get('active/{id}', [RestaurantController::class, 'makeActive'])->name('restaurant-active');
        Route::post('menus/{id}', [MenuController::class, 'rest_store'])->name('add-restaurant-menu');
    });
        Route::resource('restaurants', RestaurantController::class);
        

    // Restaurant Menuu
    Route::prefix('serve-tables')->group(function () {
        Route::get('inactive-listing', [ServeTableController::class, 'inactive_listing'])->name('serve_tables-inactive-listing');
        Route::get('active-listing', [ServeTableController::class, 'active_listing'])->name('serve_tables-active-listing');
        Route::get('datatable', [ServeTableController::class, 'datatable'])->name('serve_tables_datatable');
        Route::get('inactive/{id}', [ServeTableController::class, 'makeInActive'])->name('serve-table-inactive');
        Route::get('active/{id}', [ServeTableController::class, 'makeActive'])->name('serve-table-active');
        Route::get('not_available/{id}', [ServeTableController::class, 'makeNotAvailable'])->name('serve-table-not-available');
        Route::get('not_available/{id}/{minutes}', [ServeTableController::class, 'makeNotAvailable'])->name('serve-table-not-available-minutes');
        Route::get('available/{id}', [ServeTableController::class, 'makeAvailable'])->name('serve-table-available');
    });
        Route::resource('serve-tables', ServeTableController::class);

    // Restaurant Menus
    Route::prefix('menus')->group(function () {
        Route::get('inactive-listing', [MenuController::class, 'inactive_listing'])->name('menus-inactive-listing');
        Route::get('active-listing', [MenuController::class, 'active_listing'])->name('menus-active-listing');
        Route::post('order/{id}', [MenuController::class, 'saveOrder'])->name('menus.order');
        Route::get('datatable', [MenuController::class, 'datatable'])->name('menus_datatable');
        Route::get('inactive/{id}', [MenuController::class, 'makeInActive'])->name('menu-inactive');
        Route::get('active/{id}', [MenuController::class, 'makeActive'])->name('menu-active');
        Route::get('not_available/{id}', [MenuController::class, 'makeNotAvailable'])->name('menu-not-available');
        Route::get('not_available/{id}/{minutes}', [MenuController::class, 'makeNotAvailable'])->name('menu-not-available-minutes');
        Route::get('available/{id}', [MenuController::class, 'makeAvailable'])->name('menu-available');
    });
        Route::resource('menus', MenuController::class);

    // Addon Types
    Route::prefix('addon-types')->group(function () {
        Route::get('datatable', [AddonTypeController::class, 'datatable'])->name('addon_types_datatable');
        Route::get('disable-mandatory/{id}', [AddonTypeController::class, 'disableMandatory'])->name('addon-types-disableMandatory');
        Route::get('enable-mandatory/{id}', [AddonTypeController::class, 'enableMandatory'])->name('addon-types-enableMandatory');
        Route::get('disable-multiselect/{id}', [AddonTypeController::class, 'disableMultiselect'])->name('addon-types-disableMultiselect');
        Route::get('enable-multiselect/{id}', [AddonTypeController::class, 'enableMultiselect'])->name('addon-types-enableMultiselect');
    });
        Route::resource('addon-types', AddonTypeController::class);

    // Restaurant Items
    Route::prefix('items')->group(function () {
        Route::get('inactive-listing', [ItemController::class, 'inactive_listing'])->name('items-inactive-listing');
        Route::get('active-listing', [ItemController::class, 'active_listing'])->name('items-active-listing');
        Route::get('datatable', [ItemController::class, 'datatable'])->name('items_datatable');
        Route::get('inactive/{id}', [ItemController::class, 'makeInActive'])->name('item-inactive');
        Route::get('active/{id}', [ItemController::class, 'makeActive'])->name('item-active');
        Route::get('not_available/{id}', [ItemController::class, 'makeNotAvailable'])->name('item-not-available');
        Route::get('not_available/{id}/{minutes}', [ItemController::class, 'makeNotAvailable'])->name('item-not-available-minutes');
        Route::get('available/{id}', [ItemController::class, 'makeAvailable'])->name('item-available');
        Route::patch('addons/{id}', [ItemController::class, 'update_addons'])->name('update_addons');
    });
        Route::resource('items', ItemController::class);

    // Restaurant Orders
    Route::prefix('orders')->group(function () {
        Route::get('open-listing', [OrderController::class, 'open_listing'])->name('orders-open-listing');
        Route::get('completed-listing', [OrderController::class, 'completed_listing'])->name('orders-completed-listing');
        Route::get('declined-listing', [OrderController::class, 'declined_listing'])->name('orders-declined-listing');
        Route::get('cancelled-listing', [OrderController::class, 'cancelled_listing'])->name('orders-cancelled-listing');
        Route::get('datatable', [OrderController::class, 'datatable'])->name('orders_datatable');
        Route::get('datatable/dashboard', [OrderController::class, 'dashboard_datatable'])->name('orders_dashboard_datatable');
        Route::get('pdf/{id}', [OrderController::class, 'makePDF'])->name('make-order-pdf');
        Route::get('inactive/{id}', [OrderController::class, 'makeInActive'])->name('order-inactive');
        Route::get('active/{id}', [OrderController::class, 'makeActive'])->name('order-active');
        Route::get('user-order/{id}/{r_id}', [OrderController::class, 'userOrders'])->name('order-user-orders');
        Route::get('restaurant-order/{id}', [OrderController::class, 'restOrders'])->name('rest-orders');
        Route::match(['GET', 'POST'], 'status-change', [OrderController::class, 'statuschange'])->name('statuschange');
    });
        Route::resource('orders', OrderController::class);

    //Kitchen
    Route::prefix('kitchen')->group(function () {

        Route::get('dashboard', [KitchenController::class, 'kitchenDashboard'])->name('kitchenDashboard');
        
        Route::post('decline-orders-batch', [KitchenController::class, 'decline_order_batch'])->name('decline_order_batch');
        Route::post('scheduled-batch', [KitchenController::class, 'order_scheduled_batch'])->name('order_scheduled_batch');
        Route::post('preparing-batch', [KitchenController::class, 'order_preparing_batch'])->name('order_preparing_batch');
        Route::post('move-to-outgoing-orders-batch', [KitchenController::class, 'move_to_outgoing_orders_batch'])->name('move_to_outgoing_orders_batch');
        Route::post('ready-batch', [KitchenController::class, 'order_ready_batch'])->name('order_ready_batch');
        Route::get('ready-orders-batch/{id}', [KitchenController::class, 'ready_orders'])->name('ready_orders_batch');
        Route::get('ready-order-details-batch/{id}', [KitchenController::class, 'ready_order_details'])->name('ready_order_details_batch');
        Route::get('pickup-orders-batch/{id}', [KitchenController::class, 'pickup_orders_batch'])->name('pickup_orders_batch');
        Route::post('reschedule-orders-batch', [KitchenController::class, 'order_reschedule_batch'])->name('order_reschedule_batch');
        
        
        Route::get('incoming-orders/{id}', [KitchenController::class, 'get_incoming_orders'])->name('get_incoming_orders');
        Route::get('outgoing-orders/{id}', [KitchenController::class, 'outgoing_orders'])->name('outgoing_orders');
        Route::get('scheduled-orders/{id}', [KitchenController::class, 'scheduled_orders'])->name('scheduled_orders');
        Route::get('ready-orders/{id}', [KitchenController::class, 'ready_orders'])->name('ready_orders');
        Route::get('ready-order-details/{id}', [KitchenController::class, 'ready_order_details'])->name('ready_order_details');
        Route::get('check-pin/{id}/{pin}', [KitchenController::class, 'check_pin'])->name('check_pin');
        Route::get('pickup-orders/{id}', [KitchenController::class, 'pickup_orders'])->name('pickup_orders');
        Route::post('decline-orders', [KitchenController::class, 'decline_order'])->name('decline_order');
        Route::post('scheduled', [KitchenController::class, 'order_scheduled'])->name('order_scheduled');
        Route::post('preparing', [KitchenController::class, 'order_preparing'])->name('order_preparing');
        Route::post('move-to-outgoing-orders', [KitchenController::class, 'move_to_outgoing_orders'])->name('move_to_outgoing_orders');
        Route::post('ready', [KitchenController::class, 'order_ready'])->name('order_ready');
        Route::get('served/{id}', [KitchenController::class, 'order_served'])->name('order-served');
        Route::post('get_combined_orders', [KitchenController::class, 'get_combined_orders'])->name('get_combined_orders');
        Route::get('clear_pending_orders', [KitchenController::class, 'clear_pending_orders'])->name('clear_pending_orders');
        Route::get('ready-orders/{id}', [KitchenController::class, 'ready_orders'])->name('ready_orders');
    });

    Route::get('/check_user_arrived', [KitchenController::class, 'check_user_arrived'])->name('check_user_arrived');
    
    Route::prefix('exports')->group(function () {
        Route::post('open-listing', [ExportController::class, 'open_listing'])->name('exports-open-listing');
        Route::post('completed-listing', [ExportController::class, 'completed_listing'])->name('exports-completed-listing');
        Route::post('declined-listing', [ExportController::class, 'declined_listing'])->name('exports-declined-listing');
        Route::post('cancelled-listing', [ExportController::class, 'cancelled_listing'])->name('exports-cancelled-listing');
        Route::post('all-listing', [ExportController::class, 'index'])->name('exports-all-listing');
    });
});
