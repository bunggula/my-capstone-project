<?php
    use App\Http\Controllers\Api\ResidentController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Api\ApiAuthController;
    use App\Http\Controllers\BarangayController;
    use App\Http\Controllers\Api\ResidentForgotPasswordController;
    use App\Http\Controllers\ABC\AnnouncementController;
    use App\Http\Controllers\Secretary\EventController;
    use App\Http\Controllers\Secretary\FormatController;
    use App\Http\Controllers\DocumentRequestController;
    use App\Http\Controllers\DocumentTemplateController;
    use App\Http\Controllers\Api\BarangayDocumentsController;
    use App\Http\Controllers\Api\ConcernController;
    use App\Http\Controllers\Api\FaqController;
    Route::get('/ping', function () {
        return response()->json(['message' => 'pong']);
    });

 Route::get('/barangays', [BarangayController::class, 'getBarangays']);

    Route::post('/register', [ResidentController::class, 'store']);
    Route::post('/login', [ResidentController::class, 'login']);
    Route::get('/announcements', [AnnouncementController::class, 'apiIndex']);
    // In api.php
Route::get('/events', [EventController::class, 'apiIndex']);
    Route::get('/formats', [FormatController::class, 'apiFormats']);
    Route::get('/formats/{id}', [FormatController::class, 'show']);
    Route::middleware('auth:sanctum')->get('/resident/profile', [ResidentController::class, 'profile']);
    Route::middleware('auth:sanctum')->put('/residents/{id}', [ResidentController::class, 'update']);
    Route::middleware('auth:sanctum')->post('/document-requests', [DocumentRequestController::class, 'store']);
  
    Route::get('/barangay-officials/{id}', [BarangayController::class, 'getOfficials']);

 Route::middleware('auth:sanctum')->get('/faqs', [FaqController::class, 'index']);

    Route::get('/document-requests', [DocumentRequestController::class, 'index']);
    Route::get('/document-requests/view/{id}', [DocumentRequestController::class, 'show']);
    Route::put('/document-requests/{id}', [DocumentRequestController::class, 'update']);
    Route::delete('/document-requests/{id}', [DocumentRequestController::class, 'destroy']);
  // ✅ KEEP ONLY THIS
  Route::get('/document-templates', [DocumentTemplateController::class, 'index']);
  Route::middleware('auth:sanctum')->post('/document-requests', [DocumentRequestController::class, 'store']);
  Route::middleware('auth:sanctum')->get('/document-requests/history', [DocumentRequestController::class, 'userHistory']);
  Route::post('/register-with-proof', [ResidentController::class, 'registerWithProof']);
  //profile pic
  Route::middleware('auth:sanctum')->post('/resident/upload-profile-picture', [ResidentController::class, 'uploadProfilePicture']);
  //concern
  Route::middleware('auth:sanctum')->post('/concerns', [ConcernController::class, 'store']);
  Route::middleware('auth:sanctum')->get('/user/concerns', [ConcernController::class, 'residentConcerns']);


  Route::post('/forgot-password', [ResidentForgotPasswordController::class, 'sendResetLinkEmail']);
  Route::middleware('auth:sanctum')->get('/resident/documents', [BarangayDocumentsController::class, 'index']);
  Route::get('/documents/{id}/fields', [BarangayDocumentsController::class, 'fields']);
  Route::get('/documents/{id}/purposes', [BarangayDocumentsController::class, 'purposes']);
  Route::middleware('auth:sanctum')->post('/resident/change-password', [ResidentController::class, 'changePassword']);
  Route::middleware('auth:sanctum')->post('/resident/change-password-with-old', [ResidentController::class, 'changePasswordWithOld']);
// routes/api.php
Route::get('/document-requests/check-duplicate', [DocumentRequestController::class, 'checkDuplicate']);


