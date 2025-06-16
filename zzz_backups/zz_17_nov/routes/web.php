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
    GeneralSettingController
};

Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return redirect()->route('dashboard')->with('doneMessage', 'Cache Cleared');
})->name('cacheClear');

Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('sendbasicemail', [MailController::class, 'basic_email']);
Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth'])->name('dashboard');

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
    });

    Route::get('/check_user_arrived', [KitchenController::class, 'check_user_arrived'])->name('check_user_arrived');
});
