<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TeamAController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\RecruiterController;
use App\Http\Controllers\Admin\IntrovideoController;
use App\Http\Controllers\Admin\EntertainerController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\FeatureAdsPackagesController;


// Web Auth Front End 

use App\Http\Controllers\Web\WebAuthController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\WebRecruiterController;



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
Route::get('get-privacy-policy', [SecurityController::class, 'getPrivacyPolicy']);
Route::get('get-about-us', [SecurityController::class, 'getAboutUs']);

/*Admin routes
 * */
Route::get('/admin-login', [AuthController::class, 'getLoginPage']);
Route::post('/login', [AuthController::class, 'Login']);
Route::get('/admin-forgot-password', [AdminController::class, 'forgetPassword']);
Route::post('/admin-reset-password-link', [AdminController::class, 'adminResetPasswordLink']);
Route::get('/change_password/{id}', [AdminController::class, 'change_password']);
Route::post('/admin-reset-password', [AdminController::class, 'ResetPassword']);

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'getdashboard'])->name('web.recruiter.dashboard');
    Route::get('profile', [AdminController::class, 'getProfile']);
    Route::post('update-profile', [AdminController::class, 'update_profile']);
    Route::post('update-password', [AdminController::class, 'profile_change_password'])->name('profile.change-password');

    Route::get('Privacy-policy', [SecurityController::class, 'PrivacyPolicy']);
    Route::get('privacy-policy-edit', [SecurityController::class, 'PrivacyPolicyEdit'])->name('policy.edit');
    Route::post('privacy-policy-update/{id}', [SecurityController::class, 'PrivacyPolicyUpdate'])->name('policy.update');
    Route::get('term-condition', [SecurityController::class, 'TermCondition']);
    Route::get('term-condition-edit', [SecurityController::class, 'TermConditionEdit']);
    Route::post('term-condition-update', [SecurityController::class, 'TermConditionUpdate']);
    Route::get('about-us', [SecurityController::class, 'AboutUs'])->name('aboutUs.index');
    Route::get('about-us-edit/{id}', [SecurityController::class, 'AboutUsEdit'])->name('aboutUs.edit');
    Route::post('about-us-update/{id}', [SecurityController::class, 'AboutUsUpdate'])->name('aboutUs.update');
    Route::get('logout', [AdminController::class, 'logout']);
    // Users
    Route::get('/users/index', [UserController::class, 'index'])->name('admin.user.index');
    Route::post('/users/verify/{id}', [UserController::class, 'verify'])->name('user.verify');

    //Recruiter
    // Recruiter
    Route::resource('recruiter', RecruiterController::class)->parameters([
        'recruiter' => 'user_id'
    ]);

    Route::get('/recruiter/event/edit/{user_id}/event-id-{event_id}', [RecruiterController::class, 'editEventIndex'])->name('recruiter.event.edit.index');
    Route::post('/recruiter/event/update/event-id-{event_id}', [RecruiterController::class, 'updateEvent'])->name('recruiter.event.update');
    Route::get('/recruiter/event/delete/event-id-{event_id}', [RecruiterController::class, 'destroyEvent'])->name('recruiter.event.delete');
    Route::get('/recruiter/event/add/user-id-{event_id}', [RecruiterController::class, 'createEventIndex'])->name('recruiter.event.add.index');
    Route::post('/recruiter/event/add/user-id-{user_id}',[RecruiterController::class, 'storeEvent'])->name('recruiter.event.store');
    Route::get('/recruiter/event/entertainers/{user_id}/event-id-{event_id}',[RecruiterController::class, 'eventEntertainersIndex'])->name('recruiter.event_entertainers.index');
    Route::get('/recruiter/event/venues/{user_id}/event-id-{event_id}',[RecruiterController::class, 'eventVenuesIndex'])->name('recruiter.event_venues.index');


    //Entertainer
     Route::resource('entertainer', EntertainerController::class)->parameters([
        'entertainer' => 'user_id'
    ]);
     Route::get('/entertainer/talent/add/{user_id}',[EntertainerController::class,'createTalentIndex'])->name('entertainer.talent.add');
     Route::post('/entertainer/talent/store/{user_id}',[EntertainerController::class,'storeTalent'])->name('entertainer.talent.store');
     Route::get('/entertainer/talent/delete/{user_id}',[EntertainerController::class,'destroyTalent'])->name('entertainer.talent.delete');
     Route::get('/entertainer/talent/edit/{user_id}/{entertainer_details_id}',[EntertainerController::class,'editTalent'])->name('entertainer.talent.edit');
     Route::post('/entertainer/talent/update/{user_id}/{entertainer_details_id}',[EntertainerController::class,'updateTalent'])->name('entertainer.talent.update');
     Route::get('/entertainer/talent/photo/{user_id}/{entertainer_details_id}',[EntertainerController::class,'showPhoto'])->name('entertainer.photo.show');
     Route::get('/entertainer/talent/photo/edit/{user_id}/{entertainer_details_id}/{photo_id}',[EntertainerController::class,'editPhoto'])->name('entertainer.photo.edit');
     Route::post('/entertainer/talent/photo/update/{user_id}/{entertainer_details_id}/{photo_id}',[EntertainerController::class,'updatePhoto'])->name('entertainer.photo.update');
     Route::get('/entertainer/talent/photo/delete/xxx/{photo_id}',[EntertainerController::class,'destroyTalentPhoto'])->name('entertainer.talent.photo.delete');

     Route::get('/entertainer/talent/categories/',[EntertainerController::class,'talentCategoriesIndex'])->name('entertainer.talent.categories.index');
     Route::post('/entertainer/talent/category/',[EntertainerController::class,'talentCategoryStore'])->name('entertainer.talent.category.store');
     Route::get('/entertainer/talent/category/edit/{category_id}',[EntertainerController::class,'talentCategoryEditIndex'])->name('entertainer.talent.category.edit.index');
     Route::post('/entertainer/talent/category/update/{category_id}',[EntertainerController::class,'updateTalentCategory'])->name('entertainer.talent.category.update');
     Route::delete('/entertainer/talent/category/delete/{category_id}',[EntertainerController::class,'destroyTalentCategory'])->name('entertainer.talent.category.delete');

    Route::get('/entertainer/talent/price-packages/{user_id}/{entertainer_details_id}',[EntertainerController::class,'pricePackagesIndex'])->name('entertainer.talent.price_packages.index');
    Route::get('/entertainer/talent/price-packages/add/{user_id}/{entertainer_details_id}', [EntertainerController::class, 'createPricePackageIndex'])->name('entertainer.talent.price_packages.add');
    Route::post('/entertainer/talent/price-packages/store/{user_id}/{entertainer_details_id}', [EntertainerController::class, 'storePricePackage'])->name('entertainer.talent.price_packages.store');
    Route::get('/entertainer/talent/price-packages/edit/{user_id}/{price_package_id}', [EntertainerController::class, 'editPricePackageIndex'])->name('entertainer.talent.price_packages.edit');
    Route::post('/entertainer/talent/price-packages/update/{user_id}/{price_package_id}', [EntertainerController::class, 'updatePricePackage'])->name('entertainer.talent.price_packages.update');
    Route::delete('/entertainer/talent/price-packages/delete/{price_package_id}', [EntertainerController::class, 'destroyPricePackage'])->name('entertainer.talent.price_packages.delete');
     //Venue
     Route::resource('venue', VenueController::class)->parameters([
        'venue' => 'user_id'
    ]);
     Route::get('/venue-providers/venue/add/{user_id}',[VenueController::class,'createVenueIndex'])->name('venue-providers.venue.add');
     Route::post('/venue-providers/venue/store/{user_id}',[VenueController::class,'storeVenue'])->name('venue-providers.venue.store');
     Route::get('/venue-providers/venue/delete/{user_id}',[VenueController::class,'destroyVenue'])->name('venue-providers.venue.delete');
     Route::get('/venue-providers/venue/edit/{user_id}/{venue_id}',[VenueController::class,'editVenue'])->name('venue-providers.venue.edit');
     Route::post('/venue-providers/venue/update/{user_id}',[VenueController::class,'updateVenue'])->name('venue-providers.venue.update');

     Route::get('/venue-providers/venue/categories/',[VenueController::class,'venueCategoriesIndex'])->name('venue-providers.venue.categories.index');
     Route::post('/venue-providers/venue/category/',[VenueController::class,'venueCategoryStore'])->name('venue-providers.venue.category.store');
     Route::get('/venue-providers/venue/category/edit/{category_id}',[VenueController::class,'venueCategoryEditIndex'])->name('venue-providers.venue.category.edit.index');
     Route::post('/venue-providers/venue/category/update/{category_id}',[VenueController::class,'updateVenueCategory'])->name('venue-providers.venue.category.update');
     Route::delete('/venue-providers/venue/category/delete/{category_id}',[VenueController::class,'destroyVenueCategory'])->name('venue-providers.venue.category.delete');

     Route::get('/venue-providers/venue/photo/{user_id}/{venue_id}',[VenueController::class,'showPhoto'])->name('venue-providers.venue.photo.show');
     Route::get('/venue-providers/venue/photo/edit/{user_id}/{venue_id}/{photo_id}',[VenueController::class,'editPhoto'])->name('venue-providers.venue.photo.edit');
     Route::post('/venue-providers/venue/photo/update/{user_id}/{venue_id}/{photo_id}',[VenueController::class,'updatePhoto'])->name('venue-providers.venue.photo.update');
     Route::get('/venue-providers/venue/photo/delete/xxx/{photo_id}',[VenueController::class,'destroyPhoto'])->name('venue-providers.venue.photo.delete');

     Route::get('/venue-providers/venue/venue-pricings/{user_id}/{venue_id}',[VenueController::class,'pricePackagesIndex'])->name('venue-providers.venue.venue_pricings.index');
    //  Route::get('/venue-providers/venue/venue-pricings/add/{venue_id}',[VenueController::class,'createPricePackageIndex'])->name('venue-providers.venue.venue_pricings.add');
     Route::get('/venue-providers/venue/venue-pricings/add/index/{user_id}/{venue_id}',[VenueController::class,'createPricePackageIndex'])->name('venue-providers.venue.venue_pricings.add');

     Route::post('/venue-providers/venue/venue-pricings/store/{user_id}/{venue_id}',[VenueController::class,'storePricePackage'])->name('venue-providers.venue.venue_pricings.store');
     Route::get('/venue-providers/venue/venue-pricings/edit/{user_id}/{venue_pricing_id}',[VenueController::class,'editPricePackageIndex'])->name('venue-providers.venue.venue_pricings.edit');
     Route::post('/venue-providers/venue/venue-pricings/update/{user_id}/{venue_pricing_id}',[VenueController::class,'updatePricePackage'])->name('venue-providers.venue.venue_pricings.update');
     Route::delete('/venue-providers/venue/venue-pricings/delete/{venue_pricing_id}',[VenueController::class,'destroyPricePackage'])->name('venue-providers.venue.venue_pricings.delete');
 //introVideo
     Route::resource('/pages/intro-video', IntrovideoController::class);
      // FeatureAdsPackages
    Route::get('/feature-ads-packages',[FeatureAdsPackagesController::class,'index'])->name('feature_ads_packages.index');
    Route::get('/feature-ads-packages/event/edit/{event_ads_package_id}',[FeatureAdsPackagesController::class,'editEventAdsPackageIndex'])->name('feature_ads_packages.event.edit.index');
    Route::post('/feature-ads-packages/event/update/{event_ads_package_id}',[FeatureAdsPackagesController::class,'updateEventAdsPackage'])->name('feature_ads_packages.event.update');
    Route::get('/feature-ads-packages/talent/edit/{talent_ads_package_id}',[FeatureAdsPackagesController::class,'editTalentAdsPackageIndex'])->name('feature_ads_packages.talent.edit.index');
    Route::post('/feature-ads-packages/talent/update/{talent_ads_package_id}',[FeatureAdsPackagesController::class,'updateTalentAdsPackage'])->name('feature_ads_packages.talent.update');
    Route::get('/feature-ads-packages/venue/edit/{venue_ads_package_id}',[FeatureAdsPackagesController::class,'editVenueAdsPackageIndex'])->name('feature_ads_packages.venue.edit.index');
    Route::post('/feature-ads-packages/venue/update/{venue_ads_package_id}',[FeatureAdsPackagesController::class,'updateVenueAdsPackage'])->name('feature_ads_packages.venue.update');
    // Notification
    Route::get('/notification',[NotificationController::class,'index'])->name('notification.index');
    // Chat
    Route::get('/chat',[ChatController::class,'index'])->name('chat.index');
    Route::post('/chat/store',[ChatController::class,'store'])->name('chat.store');
    Route::get('/chat-messages',[ChatController::class,'get_ChatMessages'])->name('chat.messages');
    Route::post('/chat-deleted',[ChatController::class,'favouriteDeleted'])->name('chat.favourite');
    Route::post('/message-deleted',[ChatController::class,'MessageDeleted'])->name('chat.messagedeleted');
    Route::post('/all-message-deleted',[ChatController::class,'AllMessageDeleted'])->name('chat.allmessagedeleted');
    Route::get('/unread-message',[ChatController::class,'unreadMessage']);


    Route::get('/payment',[PaymentController::class,'index'])->name('payment.index');
    Route::get('/feature-payment',[PaymentController::class,'feature'])->name('payment.feature');
    Route::get('/ticket-payment',[PaymentController::class,'ticketPayment'])->name('payment.ticketPayment');
    Route::post('/payment-status/{id}',[PaymentController::class,'status'])->name('payment.status');


    Route::get('/account-deletion-request', [AdminController::class, 'accountDeletionRequest']);
    Route::post('/delete-account', [AdminController::class, 'deleteAccount']);
    Route::post('/reject-account', [AdminController::class, 'rejectAccount']);

    Route::get('/event-deletion-request', [AdminController::class, 'eventDeletionRequest']);

});
// Web Front-End Routes

/////////// Auth //////////////
Route::post('/recruiter-signup',[WebAuthController::class,'register'])->name('recruiter.signup');
Route::get('/recruiter-login', [WebAuthController::class, 'showLoginForm'])->name('recruiter.login.form');
Route::post('/recruiter-login',[WebAuthController::class,'login'])->name('recruiter.login');
Route::post('/send-otp', [WebAuthController::class, 'sendOtp'])->name('recruiter.sendOtp');
Route::post('/verify-otp', [WebAuthController::class, 'verifyOtp'])->name('recruiter.verifyOtp');
Route::post('/resend-otp', [WebAuthController::class, 'resendOtp'])->name('recruiter.resendOtp');
Route::post('/set-password', [WebAuthController::class, 'setPassword'])->name('recruiter.setPassword');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('recruiter.logout');

//////////////Events////////
// Route::get('/event', [EventController::class, 'searchEvent'])->name('web.event');
   Route::get('/events', [EventController::class, 'searchEvent'])->name('web.events');
     Route::middleware(['checkauth'])->group(function () {
     
     Route::get('/event/{id}', [EventController::class, 'eventDetail'])->name('event.detail');
     Route::post('/event/{id}/generate-ticket', [EventController::class, 'createTicket'])->name('generate.ticket');
     Route::get('/event/{id}/generate-ticket', [EventController::class, 'generateTicket'])->name('event.generateTicket');
     Route::get('/mytickets', [WebRecruiterController::class, 'ticket'])->name('web.recruiter.myticket');
     // Show create event form
     Route::get('/eventcreate', [WebRecruiterController::class, 'create'])->name('event.create');

     // Handle form submit
     Route::post('/eventcreate', [WebRecruiterController::class, 'store'])->name('events.store');
     Route::get('/myevents', [WebRecruiterController::class, 'myEvents'])->name('web.recruiter.myevents');
     Route::get('/myprofile/{id}', [webRecruiterController::class, 'showmyprofile'])->name('profile.show');
     Route::post('/profile-update/{id}', [WebRecruiterController::class, 'update'])->name('profile.update');
    
     // Dashboard
    Route::get('/dashboard', [WebRecruiterController::class, 'dashboard']);
});

// Google OAuth
Route::get('auth/google', [WebAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [WebAuthController::class, 'handleGoogleCallback']);


/*Team A routes
 * */

Require __DIR__.'/frontend.php';


