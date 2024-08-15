<?php

use App\Http\Controllers\PetController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TempCapacityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NonMemberBookingController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseMasterController;
use App\Http\Controllers\KarteController;
use App\Http\Controllers\KarteFormatController;
use App\Http\Controllers\DefaultCapacityController;
use App\Http\Controllers\OpenCloseSalonController;
use App\Http\Controllers\AccessLogController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\DogtypeController;
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
-> middleware('checkAdminAuth','auth',)
-> name('admin.route')
;

//ログイン画面
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']); 

//ログアウト
Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout')-> Middleware('auth');

//ユーザー登録
Route::get('/register',
[UserController::class,'create']) -> name('admin.users.create');
Route::post('/admin/users/',[UserController::class,'store']) -> name('admin.users.store');

//ペットの登録・確認
Route::resource('/pets',
 PetController::class
)
-> only(['index','create','store','edit','update'])
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


//カルテ表示(飼い主用)
Route::get('/karte/{karte}',
[KarteController::class,'show_for_user']
) -> Middleware('auth')
-> name('owner.karte.show');

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


Route::get('/noMember/withStDate/{start_date}',[NonMemberBookingController::class,'startNonUserBookingSelectCalenderWithStdate'])
-> name('nonMember.test');

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
-> Middleware('auth','checkAdminAuth')
-> name('admin.checkBookings.dateAndSalon');

Route::get('/admin/makebooking',[BookingController::class,'adminMakeBooking'])
-> middleware('auth','checkAdminAuth')
-> name('admin.makebooking');
Route::post('/admin/makebooking',[BookingController::class,'adminMakeBookingSave'])
-> middleware('auth','checkAdminAuth');

//臨時予約枠調整
Route::get('/admin/capacitysetting',[TempCapacityController::class,'index'])
-> middleware('auth','checkAdminAuth')
-> name('admin.adjustCapacity');
Route::get('/admin/newtempcapacitycreate',[TempCapacityController::class,'create'])
-> middleware('auth','checkAdminAuth')
-> name('admin.newtempcapacitycreate');
Route::post('/admin/newtempcapacitycreate',[TempCapacityController::class,'store'])
-> middleware('auth','checkAdminAuth');


//空き枠数の確認
Route::get('/admin/checkcapacities',[BookingController::class,'getAcceptableCount'])
-> middleware('auth','checkAdminAuth')
-> name('admin.checkCapacity');

Route::get('/admin/checkcapacities/{salon_id}/{st_date}',[BookingController::class,'getAcceptableCountWithSalonDate'])
-> middleware('auth','checkAdminAuth');

Route::post('/admin/postOXList',[OpenCloseSalonController::class,'changeOXListAll'])
->name('admin.changeOXlist.all')-> middleware('auth','checkAdminAuth');

//ユーザーの確認
Route::get('/admin/ownersInfo',[UserController::class,'index'])
-> middleware('auth','checkAdminAuth');

Route::get('/admin/setting',[SettingController::class,'index'])
->name('admin.setting')
-> middleware('auth','checkAdminAuth');

Route::post('/admin/setting2',[SettingController::class,'update'])
->name('admin.setting.update')
-> middleware('auth','checkAdminAuth');

//管理画面からの削除
Route::get('/admin/cancel/{bookingId}',
[BookingController::class,'adminDeleteBookingConfirm']
) -> middleware('auth','checkAdminAuth')
-> name('admin.cancelConfirm');

//サロン一覧の取得
Route::get('/admin/salons',
[SalonController::class,'index']
) -> middleware('auth','checkAdminAuth')
-> name('admin.salon.index');

//サロン追加画面
Route::get('/admin/salons/create',
[SalonController::class,'create']
) -> middleware('auth','checkAdminAuth')
-> name('admin.salon.create');

//サロン設定画面の取得
Route::get('/admin/salons/{salon_id}',
[SalonController::class,'edit']
) -> middleware('auth','checkAdminAuth')
-> name('admin.salon.edit');

//サロン設定画面の更新
Route::put('/admin/salons/{salon_id}',
[SalonController::class,'update']
) -> middleware('auth','checkAdminAuth')
-> name('admin.salon.update');

//サロン設定画面の取得
Route::get('/admin/salons/mailtest/{salon_id}',
[SalonController::class,'mailtest']
) -> middleware('auth','checkAdminAuth')
-> name('admin.salon.mailtest');

//コース設定画面の取得
Route::get('/admin/course',
[CourseController::class,'index']
) -> middleware('auth','checkAdminAuth')
-> name('admin.course.edit');

//コース設定画面の取得
Route::post('/admin/course',
[CourseController::class,'update']
) -> middleware('auth','checkAdminAuth')
-> name('admin.course.store');

//コース設定画面の取得
Route::get('/admin/course/{course}',
[CourseController::class,'switch_course']
) -> middleware('auth','checkAdminAuth')
-> name('admin.course.switch');

// ペット確認（管理者）
Route::get('/admin/pet/{pet_id}',
[PetController::class,'show_by_staff'])
-> middleware('auth','checkAdminAuth')
-> name('admin.pet.show');
;

//カルテ記載画面
Route::get('/admin/karte/create/{bookingID}',
[KarteController::class,'create']
) -> middleware('auth','checkAdminAuth')
-> name('admin.karte.create');

//カルテ記載画面
Route::post('/admin/karte/create',
[KarteController::class,'store']
) -> middleware('auth','checkAdminAuth')
-> name('admin.karte.store');

//カルテ表示(スタッフ用)
Route::get('/admin/karte/{karte}',
[KarteController::class,'edit']
) -> middleware('auth','checkAdminAuth')
-> name('admin.karte.show');

//カルテ表示(スタッフ用)
Route::post('/admin/karte/{karte}',
[KarteController::class,'update']
) -> middleware('auth','checkAdminAuth')
-> name('admin.karte.update');
/*
Route::post('/admin/cancel/{bookingId}',
[BookingController::class,'adminDeleteBooking']
) -> middleware('auth','checkAdminAuth')
-> name('admin.cancel');
*/

//カルテテンプレート表示
Route::get('/admin/karte_template',
[KarteFormatController::class,'index']
) -> middleware('auth','checkAdminAuth')
-> name('admin.karte.template.index');

//カルテテンプレート編集
Route::get('/admin/karte_template/{karteFormat}',
[KarteFormatController::class,'edit']
) -> middleware('auth','checkAdminAuth')
-> name('admin.karte.template.edit');

//カルテテンプレート更新
Route::post('/admin/karte_template/{karteFormat}',
[KarteFormatController::class,'update']
) -> middleware('auth','checkAdminAuth')
-> name('admin.karte.template.update');

Route::post('/admin/cancel/{bookingId}',
[BookingController::class,'adminDeleteBooking']
) -> middleware('auth','checkAdminAuth')
-> name('admin.cancel');

Route::get('/admin/bookingDetail/{bookingId}',
    [BookingController::class,'adminShowBookingDetail'])
->middleware('auth','checkAdminAuth')
->name('admin.showBookingDetail');

//会員情報確認
Route::get('admin/ownerInfo/{userID}',[UserController::class,'show'])
->middleware('auth','checkAdminAuth')
->name('admin.showUserInfo');

//スタッフ追加
Route::get('/admin/createStaff',[BookingController::class,'gettest'])
-> middleware('auth','checkAdminAuth')
;

Route::get('/admin/createStaff',
[UserController::class,'createStaff']) -> name('admin.users.createStaff')
->middleware('auth','checkAdminAuth');
Route::post('/admin/createStaff',[UserController::class,'storeStaff']) 
-> name('admin.users.storeStaff')
-> middleware('auth','checkAdminAuth');

//予約画面から非会員の情報を取得
Route::get('/admin/showNonMember/{bookingId}',[BookingController::class,'showNonMember'])
->name('admin.showNonMemberInfo')
-> middleware('auth','checkAdminAuth');

Route::resource('/admin/notification',
 NotificationController::class
)
// -> only(['index','create','store','edit','update'])
-> middleware('auth','checkAdminAuth')
;

Route::resource('/admin/accesslog', AccessLogController::class)
-> only(['index','show'])
-> middleware('checkAdminAuth','auth')
;


// 休日の一覧
Route::get('/admin/holiday/{salon_id}',
[HolidayController::class,'index'])
-> middleware('auth','checkAdminAuth')
-> name('admin.holiday');
;

// 休日の追加画面
Route::get('/admin/holiday/{salon_id}/create',
[HolidayController::class,'create'])
-> middleware('auth','checkAdminAuth')
-> name('admin.holiday.create');
;

// 休日の追加保存
Route::post('/admin/holiday/{salon_id}/create',
[HolidayController::class,'store'])
-> middleware('auth','checkAdminAuth')
-> name('admin.holiday.store');
;

// 休日の削除
Route::get('/admin/holiday/destroy/{holiday}',
[HolidayController::class,'destroy'])
-> middleware('auth','checkAdminAuth')
-> name('admin.holiday.destroy');
;

Route::resource('admin/course_master', CourseMasterController::class)   
    ->names([
        'index' => 'admin.course_master.index',
        'create' => 'admin.course_master.create',
        'store' => 'admin.course_master.store',
        'show' => 'admin.course_master.show',
        'edit' => 'admin.course_master.edit',
        'update' => 'admin.course_master.update',
        'destroy' => 'admin.course_master.destroy',
    ])
    ->middleware('checkAdminAuth','auth');

Route::resource('admin/dogtype', DogtypeController::class)
    ->middleware('checkAdminAuth','auth')
    ->names([
        'index' => 'admin.dogtype.index',
        'create' => 'admin.dogtype.create',
        'store' => 'admin.dogtype.store',
        'show' => 'admin.dogtype.show',
        'edit' => 'admin.dogtype.edit',
        'update' => 'admin.dogtype.update',
        'destroy' => 'admin.dogtype.destroy',
    ]);

// 休日の追加保存
Route::get('/admin/dogtype/switch_flg/{dogtype}',
[DogtypeController::class,'switch_flg_show'])
-> middleware('auth','checkAdminAuth')
-> name('admin.dogtype.switch_flg_show');
;

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
->middleware('auth')
-> name('admin.allCapacities');

Route::post('/admin/acceptable' , 
[BookingController::class,'getAcceptableCountWithSalonDate'])
->middleware('auth')
-> name('admin.getAcceptableCountWithSalonDate');

Route::get('/admin/close/{salonId}/{date}/{time}',[BookingController::class,'testAdjust'])
->middleware('auth')
->name('admin.adjust');


Route::get('/test3',[OpenCloseSalonController::class,'testOX']);

Route::get('/checkOpenClose',[OpenCloseSalonController::class,'index'])
->middleware('auth','checkAdminAuth')
->name('admin.checkOpenClose');

Route::get('/checkOpenClose/{salon}/{course}/{date}',[OpenCloseSalonController::class,'index2'])
->middleware('auth')
->name('admin.checkOpenCloseWithDate');

Route::get('/switchOX/{salon}/{course}/{date}/{time}/{st_date}/{count}'
,[OpenCloseSalonController::class,'switchOX'])
->middleware('auth')
->name('admin.switchOX');

Route::get('/checkOpenClose/show',[OpenCloseSalonController::class,'index3'])
->middleware('auth')
->name('admin.checkOpenCloseWithDate.change');

Route::get('/test/addmonth/{date}/{addmonth}', [BookingController::class,'testAddMonth']);

Route::get('/testautoClose', [BookingController::class,'testAutoClose']);
Route::get('/testBeforeCame', [BookingController::class,'testGetTheUserCameBefore']);

Route::get('/cameBeforetest',
[BookingController::class,'testUtilGetComeBefore'])
;
