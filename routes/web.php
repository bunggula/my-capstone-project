<?php
use App\Http\Controllers\AuthController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Secretary\EventController;
use App\Http\Controllers\Captain\EventApprovalController;
use App\Http\Controllers\Secretary\ResidentController;
use App\Http\Controllers\Captain\CaptainResidentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Secretary\FormatController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ABC\AbcResidentController;
use App\Http\Controllers\ABC\AnnouncementController;
use App\Http\Controllers\SecretaryResidentController;
use App\Http\Controllers\ABC\AbcDashboardController;
use App\Http\Controllers\Secretary\DocumentRequestController;
use App\Http\Controllers\ABCProposalController;
use App\Http\Controllers\SecretaryReportController;
use App\Http\Controllers\Captain\CaptainReportController;
use App\Http\Controllers\Captain\BarangayInfoController;
use App\Http\Controllers\Secretary\OfficialController;
use App\Http\Controllers\Secretary\WasteReportController;
use App\Http\Controllers\Secretary\ServicesReportController;
use App\Http\Controllers\Captain\ProposalController;
use App\Http\Controllers\Auth\ResidentResetPasswordController;
use App\Http\Controllers\DocumentVerificationController;
use App\Http\Controllers\Captain\ConcernController;
use App\Http\Controllers\Captain\DocumentController;
use App\Http\Controllers\ABC\ServicesController;
use App\Http\Controllers\Captain\CaptainAnnouncementController;
use App\Http\Controllers\VawcReportController;
use App\Http\Controllers\Captain\BarangayDocumentController;
use App\Http\Controllers\Secretary\BrcoReportController;
use App\Http\Controllers\ABC\AbcConcernController;
use App\Http\Controllers\ABC\AllController;
use App\Http\Controllers\Captain\NewController;
use App\Http\Controllers\Secretary\BlotterController;
use App\Http\Controllers\Secretary\FaqController;
use App\Http\Controllers\Captain\SecretaryAccountController;
use App\Http\Controllers\BarangayController;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/verify/{reference_code}', [DocumentVerificationController::class, 'verify'])->name('document.verify');
// Resource route for VAWC Reports
Route::resource('vawc_reports', VawcReportController::class);



// vawc_report_cases table



//resetPassResident

Route::get('resident/password/reset/{token}', [ResidentResetPasswordController::class, 'showResetForm'])->name('resident.password.reset');
Route::post('resident/password/reset', [ResidentResetPasswordController::class, 'reset'])->name('resident.password.update');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');



// No need for 'role:secretary' middleware kung sa login pa lang sinasala mo na.
Route::prefix('secretary')->middleware(['auth'])->name('secretary.')->group(function () {
    Route::get('/reports/{id}/print', [VAWCReportController::class, 'printReport'])->name('vawc_reports.vawc_print');
//barco
Route::get('/brco', [BrcoReportController::class, 'Index'])->name('brco.index');
Route::get('/brco/create', [BrcoReportController::class, 'Create'])->name('brco.create');
Route::post('/brco', [BrcoReportController::class, 'Store'])->name('brco.store');
// web.php
Route::get('/brco/print', [BrcoReportController::class, 'print'])->name('brco.print');


Route::post('/notifications/mark-read', [NotificationController::class, 'markAllAsRead'])
    ->name('notifications.markRead');



Route::resource('brco', BrcoReportController::class);



    // Manual routes for custom paths like /add-format
    Route::get('add-format', [FormatController::class, 'index'])->name('formats.index');
    Route::get('add-format/create', [FormatController::class, 'create'])->name('formats.create');
    Route::post('add-format', [FormatController::class, 'store'])->name('formats.store');
    // Resource for the rest (edit, update, delete)
    Route::resource('formats', FormatController::class)->only(['edit', 'update', 'destroy']);
    //add resident and view added
    Route::get('/residents', [ResidentController::class, 'index'])->name('residents.index');
    Route::get('add-residents/create', [ResidentController::class, 'create'])->name('residents.create');
    Route::post('add-residents', [ResidentController::class, 'store'])->name('residents.store');
    Route::get('/residents/print', [ResidentController::class, 'print'])->name('residents.print');
    // Edit resident
    Route::get('/residents/{resident}/edit', [ResidentController::class, 'edit'])->name('residents.edit');
    Route::put('/residents/{resident}', [ResidentController::class, 'update'])->name('residents.update');
    // Delete resident
    Route::delete('/residents/{resident}', [ResidentController::class, 'destroy'])->name('residents.destroy');
    //show
    Route::get('/residents/{id}/view-modal', [ResidentController::class, 'viewModal'])->name('residents.view-modal');


    Route::get('/residents/rejected', [ResidentController::class, 'rejected'])->name('residents.rejected');
    //reports
    
    Route::get('/reports', [WasteReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [WasteReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [WasteReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/generate', [WasteReportController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/print', [WasteReportController::class, 'print'])->name('reports.print');
    Route::resource('secretary/reports', WasteReportController::class);
    Route::resource('reports', WasteReportController::class);
    Route::get('/reports/view-all', [WasteReportController::class, 'viewAll'])->name('reports.viewAll');
    


    //sevices reports
    Route::get('/services', [ServicesReportController::class, 'index'])->name('reports.services');
    Route::get('/secretary/services', [ServicesReportController::class, 'services'])->name('reports.services');
    Route::get('/secretary/services/print', [ServicesReportController::class, 'print'])->name('reports.services.print');
    Route::put('/secretary/reports/{id}', [SecretaryReportController::class, 'update'])->name('secretary.reports.update');

    //resident
    Route::get('residents/{resident}', [ResidentController::class, 'show'])->name('residents.show');
    // Add forms
    // Events List
    Route::get('/events-announcements', [EventController::class, 'index'])->name('events.index');
    // Add Event
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    // Edit Event
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');

    // Update Event
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');

    // Delete Event
    Route::post('/residents/{resident}/archive', [ResidentController::class, 'archive'])->name('residents.archive');
    Route::post('/residents/{resident}/unarchive', [ResidentController::class, 'unarchive'])->name('residents.unarchive');
    Route::delete('/events', [EventController::class, 'destroyMultiple'])->name('events.destroy-multiple');

    Route::get('/events/pending', [EventController::class, 'pending'])->name('events.pending');
    Route::get('/events/rejected', [EventController::class, 'rejected'])->name('events.rejected');
    Route::patch('/events/{event}/resubmit', [EventController::class, 'resubmit'])->name('events.resubmit');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

    Route::get('/approval-accounts', [SecretaryResidentController::class, 'pending'])->name('residents.pending');

    Route::put('/residents/{id}/approve', [SecretaryResidentController::class, 'approve'])
        ->name('residents.approve');
    
    Route::patch('/residents/{id}/reject', [SecretaryResidentController::class, 'reject'])
        ->name('residents.reject');
    
  
        //officials
        Route::resource('officials',OfficialController::class);
        Route::put('officials/{id}', [OfficialController::class, 'update'])->name('officials.update');
        Route::put('/officials/{official}', [OfficialController::class, 'update'])->name('officials.update');

        
        Route::get('/secretary/reports', [SecretaryReportController::class, 'index'])->name('secretary.reports.index');
    Route::post('/secretary/reports/upload', [SecretaryReportController::class, 'upload'])->name('secretary.reports.upload');

        //from mobile
        Route::get('/document-requests', [DocumentRequestController::class, 'index'])->name('document_requests.index');
        Route::get('/document-requests/{id}', [DocumentRequestController::class, 'show'])->name('document_requests.show');

        
        Route::post('/document-requests/{id}/status/{status}', [DocumentRequestController::class, 'updateStatus'])->name('document_requests.update_status');
    
        Route::get('/document-requests/{id}/edit', [DocumentRequestController::class, 'edit'])->name('document_requests.edit');
        Route::put('/document-requests/{id}', [DocumentRequestController::class, 'update'])->name('document_requests.update');
        Route::get('/document-requests/{id}/print', [DocumentRequestController::class, 'print'])->name('document_requests.print');
// web.php
Route::post('/document-requests/{id}/reject', [DocumentRequestController::class, 'reject'])
    ->name('document_requests.reject');

//services
    Route::get('services/approved', [DocumentRequestController::class, 'approved'])->name('services.approved');
    Route::get('services/rejected', [DocumentRequestController::class, 'rejected'])->name('services.rejected');
    Route::post('document-requests/{id}/complete', [DocumentRequestController::class, 'markAsCompleted'])->name('document_requests.mark_completed');
    Route::get('/document_requests/completed', [DocumentRequestController::class, 'completed'])->name('document_requests.completed');
    //FaqController
     Route::get('faq', [FaqController::class, 'index'])->name('faq.index');
    Route::post('faq', [FaqController::class, 'store'])->name('faq.store');
    Route::put('faq/{faq}', [FaqController::class, 'update'])->name('faq.update');
    Route::delete('faq/{faq}', [FaqController::class, 'destroy'])->name('faq.destroy');

//walk-in
    Route::get('residents/{resident}/generate', [ResidentController::class, 'generateDocument'])
    ->name('residents.generate');
    Route::post('documents/request', [DocumentRequestController::class, 'store'])
    ->name('documents.request');
    Route::get('/documents/{document}/purposes', [DocumentRequestController::class, 'getPurposes']);
    Route::get('/documents/{document}/fields', [DocumentRequestController::class, 'getFields']);

        // Blotter Logbook Routes
Route::get('/blotter', [BlotterController::class, 'index'])->name('blotter.index');
Route::post('/blotter', [BlotterController::class, 'store'])->name('blotter.store');
Route::get('/blotter/{id}', [BlotterController::class, 'show']);
Route::get('/blotter/{id}/edit', [BlotterController::class, 'edit']);
Route::put('/blotter/{blotter}', [BlotterController::class, 'update'])->name('blotter.update');
Route::get('/blotter/{blotter}/print', [BlotterController::class, 'print'])->name('blotter.print');
Route::post('/blotter/{id}/upload-signed', [BlotterController::class, 'uploadSigned'])
    ->name('blotter.uploadSigned');

});
Route::prefix('captain')->middleware(['auth'])->name('captain.')->group(function () {
    Route::get('/events', [EventApprovalController::class, 'index'])->name('events.index');
    Route::patch('/events/{event}/approve', [EventApprovalController::class, 'approve'])->name('events.approve');
    Route::patch('/events/{event}/reject', [EventApprovalController::class, 'reject'])->name('events.reject');
    Route::get('/residents', [CaptainResidentController::class, 'index'])->name('residents.index');
    Route::get('residents/print', [CaptainResidentController::class, 'print'])->name('residents.print');
    //proposal and reports
    Route::get('/proposal', [ProposalController::class, 'index'])->name('proposal.index');
    Route::get('/proposal/create', [ProposalController::class, 'create'])->name('proposal.create');
    Route::post('/proposal', [ProposalController::class, 'store'])->name('proposal.store'); // ✅ Correct
    Route::put('/proposal/{proposal}', [ProposalController::class, 'update'])->name('proposal.update');
    Route::delete('/proposal/{proposal}', [ProposalController::class, 'destroy'])->name('proposal.destroy');

    Route::get('/proposals/approved', [ProposalController::class, 'approved'])->name('proposals.approved');
    Route::get('/proposals/rejected', [ProposalController::class, 'rejected'])->name('proposals.rejected');
Route::get('/proposal/{proposal}/print', [ProposalController::class, 'print'])->name('proposal.print');

    Route::get('/abc/proposals/{proposal}', [ABCProposalController::class, 'show'])->name('abc.proposals.show');

    Route::get('/reports', [CaptainReportController::class, 'index'])->name('reports.index');
    
    Route::get('/events/approved', [EventApprovalController::class, 'approved'])->name('events.approved');
Route::get('/events/rejected', [EventApprovalController::class, 'rejected'])->name('events.rejected');

//cocerns
Route::get('/concerns', [ConcernController::class, 'index'])->name('concerns.index');
Route::get('/captain/concerns/{id}', [ConcernController::class, 'show'])->name('concerns.show');
Route::put('/captain/concerns/{concern}/status', [ConcernController::class, 'updateStatus'])->name('concerns.status.update');
//services
Route::get('/captain/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::get('/captain/documents/{documentRequest}', [DocumentController::class, 'show'])->name('documents.show');

Route::put('/concerns/{id}/status', [ConcernController::class, 'updateStatus'])->name('concerns.update-status');

Route::get('/captain/announcements', [CaptainAnnouncementController::class, 'index'])->name('announcements.index');
// Captain Proposal routes
Route::patch('/captain/proposals/{proposal}/resubmit', [ProposalController::class, 'resubmit'])
    ->name('proposal.resubmit');
 Route::get('officials', [NewController::class, 'officialsIndex'])->name('new.index');
 
 // Show edit form
Route::get('/barangay-info/edit', [BarangayInfoController::class, 'edit'])->name('barangay-info.edit');

// Update info
Route::put('/barangay-info', [BarangayInfoController::class, 'update'])->name('barangay-info.update');

// View-only (optional)
Route::get('/barangay-info', [BarangayInfoController::class, 'show'])->name('barangay-info.show');

//BarangayDocumentController
    Route::get('/manage', [BarangayDocumentController::class, 'index'])->name('manage.index');
    Route::get('/manage/create', [BarangayDocumentController::class, 'create'])->name('manage.create');
    Route::post('/manage', [BarangayDocumentController::class, 'store'])->name('manage.store');
    Route::get('/manage/{id}/edit', [BarangayDocumentController::class, 'edit'])->name('manage.edit');
    Route::put('/manage/{id}', [BarangayDocumentController::class, 'update'])->name('manage.update');
    Route::delete('/manage/{id}', [BarangayDocumentController::class, 'destroy'])->name('manage.destroy');


    Route::post('/secretary/store', 
    [SecretaryAccountController::class, 'store'])
    ->name('secretary.store');
    Route::patch('/secretary/{user}/toggle', [SecretaryAccountController::class, 'toggleStatus'])
    ->name('secretary.toggle');
   
    Route::put('secretary/{user}', [SecretaryAccountController::class, 'update'])->name('secretary.update');
Route::get('secretary', [SecretaryAccountController::class, 'index'])
    ->name('secretary.index');

});


Route::prefix('abc')->middleware(['auth'])->name('abc.')->group(function () {
  // web.php
Route::get('proposals/print/{proposal}', [ABCProposalController::class, 'print'])->name('proposals.print');

Route::get('/accounts/{id}', [UserManagementController::class, 'show'])->name('accounts.show');

    Route::get('/proposals', [ABCProposalController::class, 'index'])->name('proposals.index');
    Route::get('/proposals/{id}', [ABCProposalController::class, 'show'])->name('proposals.show');
    Route::post('/proposals/{id}/approve', [ABCProposalController::class, 'approve'])->name('proposals.approve');
    Route::post('/proposals/{id}/reject', [ABCProposalController::class, 'reject'])->name('proposals.reject');
    // Announcements
   // Add this BEFORE your Route::resource(...) line
Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::delete('/announcements/images/{id}', [AnnouncementController::class, 'deleteImage'])->name('announcements.image.delete');

    // User account management
    Route::get('/accounts', [UserManagementController::class, 'listAccounts'])->name('accounts.list');
    Route::get('/accounts/{user}/edit', [UserManagementController::class, 'editAccount'])->name('edit_account');
    Route::put('/accounts/{user}', [UserManagementController::class, 'updateAccount'])->name('update_account');
    Route::delete('/accounts/{user}', [UserManagementController::class, 'deleteAccount'])->name('delete_account');
    Route::patch('/abc/accounts/{id}/toggle-status', [AuthController::class, 'toggleStatus'])->name('toggle_status');
Route::get('/abc/accounts/all', [AllController::class, 'index'])->name('all.index');
      Route::get('/abc/all/print', [AllController::class, 'print'])->name('all.print');
    // Add ABC Account
    Route::get('/add-account', [UserManagementController::class, 'showAddAccountForm'])->name('add_account');
    Route::post('/add-account', [UserManagementController::class, 'storeAccount'])->name('store.account');
     Route::get('/barangay-officials/{id}/view', [BarangayOfficialController::class, 'show'])->name('barangay_officials.view');


    // Resident Management
    Route::get('/residents/create', [AbcResidentController::class, 'create'])->name('residents.create');
    Route::post('/residents', [AbcResidentController::class, 'store'])->name('residents.store');
    Route::get('/residents', [AbcResidentController::class, 'index'])->name('residents.index');
    Route::get('/residents/export-pdf', [AbcResidentController::class, 'exportPDF'])->name('residents.export-pdf');
    Route::get('/abcresidents', [AbcResidentController::class, 'index'])->name('residents.index');
    Route::get('/residents/print', [AbcResidentController::class, 'print'])->name('residents.print');

    // Optional: if you're using resource controller (you already included above, you can remove duplication)
    Route::resource('residents', AbcResidentController::class)->except(['create', 'store', 'index']);
    Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
    Route::get('/services/{id}', [ServicesController::class, 'show'])->name('services.show');


    Route::get('/concerns', [AbcConcernController::class, 'index'])->name('concerns.index');
//barangayName
    Route::get('/barangays', [BarangayController::class, 'index'])->name('barangays.index');
    Route::post('/barangays', [BarangayController::class, 'store'])->name('barangays.store');
    Route::put('/barangays/{id}', [BarangayController::class, 'update'])->name('barangays.update');
    Route::delete('/barangays/{id}', [BarangayController::class, 'destroy'])->name('barangays.destroy');
    Route::post('/municipality/{id}/update', [BarangayController::class, 'updateMunicipality'])->name('municipality.update');
});





Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('settings', function () {
        return redirect()->route('settings.profile');
    });

  
    Route::get('settings/profile', [SettingsController::class, 'editProfile'])->name('settings.profile');
    Route::post('settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');

    Route::get('settings/password', [SettingsController::class, 'editPassword'])->name('settings.password');
    Route::post('settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');

    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

//print

//pdf
Route::get('/residents/download-pdf', [ResidentController::class, 'downloadPdf'])->name('secretary.residents.download-pdf');

Route::get('/abc/residents/export-pdf', [AbcResidentController::class, 'exportPDF'])->name('abc.residents.export-pdf');

Route::get('/captain/residents/export/pdf', [CaptainResidentController::class, 'exportPDF'])->name('captain.residents.export.pdf');


    // ABC admin dashboard
    Route::get('/abc/dashboard', [AuthController::class, 'abcDashboard'])->name('abc.dashboard');
    Route::get('/abc/barangays', [AuthController::class, 'showBarangays'])->name('abc.barangays.index');
    Route::get('/abc/events', [AbcDashboardController::class, 'showApprovedEvents'])->name('abc.events.index');
    Route::get('/abc/events', [AuthController::class, 'index'])->name('abc.events.index');
    Route::get('/abc/events', [AuthController::class, 'events'])->name('abc.events.index');





    // ABC add account routes
    Route::get('/abc/add-account', [UserManagementController::class, 'showAddAccountForm'])->name('abc.add_account');
    Route::post('/abc/add-account', [UserManagementController::class, 'storeAccount'])->name('store.account');
    Route::get('/abc/abcresidents', [AbcResidentController::class, 'index'])->name('residents.index');
  

    // Secretary dashboard (controller-based)
    Route::get('/secretary/dashboard', [DashboardController::class, 'secretaryDashboard'])->name('secretary.dashboard');
    Route::get('/secretary/announcements', [DashboardController::class, 'viewABCAnnouncements'])->name('secretary.announcements.index');


   


    // Captain dashboard
    Route::get('/captain/dashboard', [DashboardController::class, 'captainDashboard'])->name('captain.dashboard');


    Route::post('/secretary/notifications/readAll', [App\Http\Controllers\Secretary\NotificationController::class, 'markAllAsRead'])->name('secretary.notifications.readAll');


    Route::get('/test-editor', function () {
        return view('test-ckeditor');
    });
    
   

    // Email verification notice
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Handle email verification when user clicks the email link
Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

// Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});
