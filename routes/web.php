<?php

use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TempCapacityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\SettingController;
use App\Models\TempCapacity;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
/*
Route::get('/', function () {
    return view('welcome');
});
*/

//ルート画面
Route::get('/',function(){
    $user =  Auth::user();

    if($user->id == 1){
        return redirect('/admin');
    }

    return view('index');
})-> Middleware('auth')
->name('main')
;

Route::get('/admin',function(){
    return view('admin.index');
})
-> middleware('auth')
-> name('admin.route')
;

//ログイン画面
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']); 

//ログアウト
Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

//ユーザー登録
Route::get('/register',
[UserController::class,'create']) -> name('admin.users.create');
Route::post('/admin/users/',[UserController::class,'store']) -> name('admin.users.store');

//ペットの登録・確認
Route::resource('/pets',
 PetController::class
)
-> only(['index','create','store'])
-> Middleware('auth')
;

Route::get('/dog_register',
 [PetController::class,'create']
) -> Middleware('auth')
-> name('user.dog_register');

Route::post('/dog_register',
 [PetController::class,'addPet']
) -> Middleware('auth');

//予約の確認
Route::get('/bookings',
 [BookingController::class,'index']
) -> Middleware('auth')
-> name('user.bookings');

//予約取り消し確認
Route::get('/cancel/{bookingId}',
[BookingController::class,'deleteConfirm']
) -> Middleware('auth')
-> name('booking.cancelConfirm');

Route::delete('/bookingdelete/{bookingId}',
[BookingController::class,'destroy']
) -> middleware('auth')
-> name('booking.delete');


//予約の新規登録画面
Route::get('/new_booking',
[BookingController::class,'create']
) -> Middleware('auth')
-> name('user.newBooking');

Route::get('/selectcourse',
 [BookingController::class,'selectcourse']
) -> Middleware('auth');

//コースを選ぶ
Route::post('/selectcourse',
 [BookingController::class,'selectCourse'] ) 
-> Middleware('auth')
-> name('booking.selectCourse');


//予約日を選ぶ
Route::post('/selectCalender',
[BookingController::class,'selectCalender']
)
-> Middleware('auth')
-> name('booking.selectCalender');
;

Route::get('/selectCalenderCheck/{salon}/{st_date}',
[BookingController::class,'selectCalenderSalonAndDate']
)
-> Middleware('auth')
-> name('booking.selectCalender.salonAndDay');
;

Route::get('/selectCalender/{date}/{time}',
[BookingController::class,'confirmBooking']
)
-> Middleware('auth')
-> name('booking.selectCalender.date');
;

//登録前の確認
Route::post('/confirm',
[BookingController::class,'confirm']
)
-> Middleware('auth')
-> name('booking.confirm');
;

//登録前の確認
Route::post('/store',
[BookingController::class,'store']
)
-> Middleware('auth')
-> name('booking.store');
;
/*****************************************************************
*
*   非会員用メニュー
*
******************************************************************/

Route::get('/nonMember',
[BookingController::class,'startNonUserBooking']
)
-> name('nonMember.beginBooking');
;

Route::post('/nonMember',
[BookingController::class,'startNonUserBookingEntry']
)
-> name('nonMember.beginBookingEntry');
;

Route::post('/nonMember/SelectCalender',
[BookingController::class,'startNonUserBookingSelectCalender']
)
-> name('nonMember.booking.selectCalender');
;


/*****************************************************************
*
*   管理者用メニュー
*
******************************************************************/

//全ユーザーの本日のd予約確認
/*
Route::get('/admin/allbookings',[BookingController::class,'getTodayAllBookings'])
-> Middleware('auth');
*/
//店舗と日付を指定しての予約表示
Route::get('/admin/allbookings',[BookingController::class,'getAllBookingsOfSalonAndDate'])
-> Middleware('auth')
-> name('admin.checkBookings.dateAndSalon');

Route::get('/admin/makebooking',[BookingController::class,'adminMakeBooking'])
-> Middleware('auth');
Route::post('/admin/makebooking',[BookingController::class,'adminMakeBookingSave'])
-> Middleware('auth');

//臨時予約枠調整
Route::get('/admin/capacitysetting',[TempCapacityController::class,'index'])
-> Middleware('auth');
Route::get('/admin/newtempcapacitycreate',[TempCapacityController::class,'create'])
-> Middleware('auth');
Route::post('/admin/newtempcapacitycreate',[TempCapacityController::class,'store'])
-> Middleware('auth');


//空き枠数の確認
Route::get('/admin/checkcapacities',[BookingController::class,'getAcceptableCount'])
-> Middleware('auth');

Route::get('/admin/checkcapacities/{salon_id}/{st_date}',[BookingController::class,'getAcceptableCountWithSalonDate'])
-> Middleware('auth');

//ユーザーの確認
Route::get('/admin/ownersInfo',[UserController::class,'index'])
-> Middleware('auth');

Route::get('/admin/setting',[SettingController::class,'index'])
->name('admin.setting')
-> Middleware('auth');

/*****************************************************************
*
*   開発用メニュー
*
******************************************************************/
//コレクション型テスト

//管理者用前予約確認
Route::get('/admin/bookings',
            [BookingController::class,'getAllBookings']) 
            -> name('admin.all_bookings');

Route::get('/test' , 
[BookingController::class,'test']);

Route::get('/admin/acceptable' , 
[BookingController::class,'getAcceptableCount'])
-> name('admin.allCapacities');

Route::post('/admin/acceptable' , 
[BookingController::class,'getAcceptableCountWithSalonDate'])
-> name('admin.getAcceptableCountWithSalonDate');
