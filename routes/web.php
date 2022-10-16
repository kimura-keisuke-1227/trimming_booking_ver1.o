<?php

use App\Http\Controllers\PetController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TempCapacityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\NonMemberBookingController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DefaultCapacityController;
use App\Http\Controllers\OpenCloseSalonController;
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

    if($user->auth == 1){
        return redirect('/admin');
    }
    
    return redirect('/new_booking');
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
[NonMemberBookingController::class,'startNonUserBooking']
)
-> name('nonMember.beginBooking');
;

Route::post('/nonMember/selectCourse',
[NonMemberBookingController::class,'startNonUserBookingEntry']
)
-> name('nonMember.beginBookingEntry');
;

Route::post('/nonMember/SelectCalender',
[NonMemberBookingController::class,'startNonUserBookingSelectCalender']
)
-> name('nonMember.booking.selectCalender');
;

Route::get('/nonMember/confirm/{date}/{time}',
[NonMemberBookingController::class,'confirmNonUserBookingSelectCalender']
)
-> name('nonMember.booking.confirm');
;

Route::post('/nonMember/store',
[NonMemberBookingController::class,'store']
)
-> name('nonMember.booking.store');
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
-> Middleware('auth')
-> name('admin.makebooking');
Route::post('/admin/makebooking',[BookingController::class,'adminMakeBookingSave'])
-> Middleware('auth');

//臨時予約枠調整
Route::get('/admin/capacitysetting',[TempCapacityController::class,'index'])
-> Middleware('auth')
-> name('admin.adjustCapacity');
Route::get('/admin/newtempcapacitycreate',[TempCapacityController::class,'create'])
-> Middleware('auth')
-> name('admin.newtempcapacitycreate');
Route::post('/admin/newtempcapacitycreate',[TempCapacityController::class,'store'])
-> Middleware('auth');


//空き枠数の確認
Route::get('/admin/checkcapacities',[BookingController::class,'getAcceptableCount'])
-> Middleware('auth')
-> name('admin.checkCapacity');

Route::get('/admin/checkcapacities/{salon_id}/{st_date}',[BookingController::class,'getAcceptableCountWithSalonDate'])
-> Middleware('auth');

//ユーザーの確認
Route::get('/admin/ownersInfo',[UserController::class,'index'])
-> Middleware('auth');

Route::get('/admin/setting',[SettingController::class,'index'])
->name('admin.setting')
-> Middleware('auth');

//管理画面からの削除
Route::get('/admin/cancel/{bookingId}',
[BookingController::class,'adminDeleteBookingConfirm']
) -> Middleware('auth')
-> name('admin.cancelConfirm');

/*
Route::post('/admin/cancel/{bookingId}',
[BookingController::class,'adminDeleteBooking']
) -> Middleware('auth')
-> name('admin.cancel');
*/

Route::post('/admin/cancel/{bookingId}',
[BookingController::class,'adminDeleteBooking']
) -> middleware('auth')
-> name('admin.cancel');

Route::get('/admin/bookingDetail/{bookingId}',
    [BookingController::class,'adminShowBookingDetail'])
->middleware('auth')
->name('admin.showBookingDetail');

//会員情報確認
Route::get('admin/ownerInfo/{userID}',[UserController::class,'show'])
->middleware('auth')
->name('admin.showUserInfo');

//スタッフ追加
Route::get('/admin/createStaff',[BookingController::class,'gettest']);

Route::get('/admin/createStaff',
[UserController::class,'createStaff']) -> name('admin.users.createStaff')
->middleware('auth');
Route::post('/admin/createStaff',[UserController::class,'storeStaff']) 
-> name('admin.users.storeStaff')
-> middleware('auth');

//予約画面から非会員の情報を取得
Route::get('/admin/showNonMember/{bookingId}',[BookingController::class,'showNonMember'])
->name('admin.showNonMemberInfo')
-> middleware('auth');
/*****************************************************************
*
*   パスワードリセット
*
******************************************************************/
Route::prefix('password_reset')->name('password_reset.')->group(function () {
    Route::prefix('email')->name('email.')->group(function () {
        // パスワードリセットメール送信フォームページ
        Route::get('/', [PasswordController::class, 'emailFormResetPassword'])->name('form');
        // メール送信処理
        Route::post('/', [PasswordController::class, 'sendEmailResetPassword'])->name('send');
        // メール送信完了ページ
        Route::get('/send_complete', [PasswordController::class, 'sendComplete'])->name('send_complete');
    });
    // パスワード再設定ページ
    Route::get('/edit', [PasswordController::class, 'edit'])->name('edit');
    // パスワード更新処理
    Route::post('/update', [PasswordController::class, 'update'])->name('update');
    // パスワード更新終了ページ
    Route::get('/edited', [PasswordController::class, 'edited'])->name('edited');
});


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

Route::get('/test/{salon_id}/{st_date}' , 
[BookingController::class,'test']);

Route::get('/admin/acceptable' , 
[BookingController::class,'getAcceptableCount'])
-> name('admin.allCapacities');

Route::post('/admin/acceptable' , 
[BookingController::class,'getAcceptableCountWithSalonDate'])
-> name('admin.getAcceptableCountWithSalonDate');

Route::get('/admin/close/{salonId}/{date}/{time}',[BookingController::class,'testAdjust'])
->middleware('auth')
->name('admin.adjust');


Route::get('/test3',[OpenCloseSalonController::class,'testOX']);

Route::get('/checkOpenClose',[OpenCloseSalonController::class,'index'])
->name('admin.checkOpenClose');

Route::get('/checkOpenClose/{salon}/{course}/{date}',[OpenCloseSalonController::class,'index2'])
->name('admin.checkOpenCloseWithDate');

Route::get('/switchOX/{salon}/{course}/{date}/{time}/{st_date}/{count}'
,[OpenCloseSalonController::class,'switchOX'])
->name('admin.switchOX');