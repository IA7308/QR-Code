<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SheepController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShelterController;
use App\Http\Controllers\SymptomController;
use App\Http\Controllers\FeedTypeController;
use App\Http\Controllers\HealthRecordController;
use App\Http\Controllers\WeightRecordController;
use App\Http\Controllers\FeedingRecordController;
use App\Http\Controllers\ProfitLossRecordController;
use App\Http\Controllers\ReproductionRecordController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\QrTemplateController;
use App\Http\Controllers\QrUnitController;
use App\Http\Controllers\QrAdminPageController;
use App\Http\Controllers\QrClientRegistrationController;
use App\Http\Controllers\QrClientPortalController;
use App\Http\Controllers\QrBillingAdminController;
use App\Http\Controllers\UniversalQrController;
use App\Http\Controllers\QrNfcController;

Route::get('/', function () {
    return view('welcome');
});

// Halaman publik yang menjadi tujuan permanen QR setiap tempat.
Route::get('/p/{place}', [PlaceController::class, 'publicShow'])->name('places.public');
Route::post('/p/{place}/review', [PlaceController::class, 'guestReview'])
    ->middleware('throttle:10,1')
    ->name('places.public.review');

Route::get('/q/{token}', [QrUnitController::class, 'publicShow'])
    ->where('token', '[a-f0-9]{48}')
    ->name('qr.public.show');
Route::post('/q/{token}', [QrUnitController::class, 'activatePublic'])
    ->where('token', '[a-f0-9]{48}')
    ->middleware('throttle:10,1')
    ->name('qr.public.activate');
Route::post('/q/{token}/select-location', [QrUnitController::class, 'selectSharedDestination'])
    ->where('token', '[a-f0-9]{48}')
    ->middleware('throttle:20,1')->name('qr.shared.select');
Route::post('/q-place-autocomplete', [QrUnitController::class, 'autocomplete'])
    ->middleware('throttle:60,1')->name('qr.places.autocomplete');
Route::get('/n/{token}', [QrUnitController::class, 'publicShow'])
    ->where('token', '[a-f0-9]{48}')->name('qr.nfc.show');
Route::post('/n/{token}', [QrUnitController::class, 'activatePublic'])
    ->where('token', '[a-f0-9]{48}')->middleware('throttle:10,1')->name('qr.nfc.activate');
Route::get('/qr-nfc', [UniversalQrController::class, 'nfcReader'])->name('qr.nfc-reader.show');

Route::get('/review-nearby', [UniversalQrController::class, 'show'])->name('qr.universal.show');
Route::post('/review-nearby/locate', [UniversalQrController::class, 'locate'])
    ->middleware('throttle:10,1')->name('qr.universal.locate');

// Dashboard (Semua User)
Route::get('/dashboard', fn () => redirect()->route(auth()->user()?->role === 'qr_client' ? 'client.dashboard' : 'places.index'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/client/register', [QrClientRegistrationController::class, 'create'])->name('client.register');
    Route::post('/client/register', [QrClientRegistrationController::class, 'store'])->middleware('throttle:5,1')->name('client.register.store');
});
Route::middleware(['auth', 'role:qr_client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/', [QrClientPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/plans', [QrClientPortalController::class, 'plans'])->name('plans');
    Route::post('/plans/{qrPlan}/subscribe', [QrClientPortalController::class, 'subscribe'])->name('subscribe');
    Route::post('/subscriptions/{qrSubscription}/cancel', [QrClientPortalController::class, 'cancelSubscription'])->name('subscriptions.cancel');
    Route::get('/invoices/{qrInvoice}', [QrClientPortalController::class, 'invoice'])->name('invoices.show');
    Route::post('/invoices/{qrInvoice}/proof', [QrClientPortalController::class, 'submitProof'])->name('invoices.proof');
    Route::get('/invoices/{qrInvoice}/proof-file', [QrClientPortalController::class, 'proofFile'])->name('invoices.proof-file');
    Route::get('/units', [QrClientPortalController::class, 'units'])->name('units');
    Route::get('/units/{qrUnit}/print', [QrClientPortalController::class, 'printUnit'])->name('units.print');
});

// === GRUP RUTE ADMIN (Hanya untuk Aksi Delete dan Sensitif) ===
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('admin/qr-clients', [QrBillingAdminController::class, 'clients'])->name('admin.qr-clients.index');
    Route::patch('admin/qr-clients/{qrClient}/status', [QrBillingAdminController::class, 'updateClientStatus'])->name('admin.qr-clients.status');
    Route::get('admin/qr-plans', [QrBillingAdminController::class, 'plans'])->name('admin.qr-plans.index');
    Route::post('admin/qr-plans', [QrBillingAdminController::class, 'storePlan'])->name('admin.qr-plans.store');
    Route::patch('admin/qr-plans/{qrPlan}', [QrBillingAdminController::class, 'updatePlan'])->name('admin.qr-plans.update');
    Route::get('admin/qr-invoices', [QrBillingAdminController::class, 'invoices'])->name('admin.qr-invoices.index');
    Route::get('admin/qr-invoices/{qrInvoice}/proof', [QrBillingAdminController::class, 'proofFile'])->name('admin.qr-invoices.proof');
    Route::post('admin/qr-invoices/{qrInvoice}/verify', [QrBillingAdminController::class, 'verify'])->name('admin.qr-invoices.verify');
    Route::post('admin/qr-invoices/{qrInvoice}/reject', [QrBillingAdminController::class, 'reject'])->name('admin.qr-invoices.reject');

    Route::get('admin/dynamic-qr', [QrAdminPageController::class, 'dashboard'])->name('admin.qr.dashboard');
    Route::get('admin/dynamic-qr/analytics', [QrAdminPageController::class, 'analytics'])->name('admin.qr.analytics');
    Route::get('admin/dynamic-qr/help', [QrAdminPageController::class, 'help'])->name('admin.qr.help');
    Route::get('admin/dynamic-qr/universal', [UniversalQrController::class, 'adminPrint'])->name('admin.qr.universal');
    Route::get('admin/dynamic-qr/qr-nfc/universal', [UniversalQrController::class, 'adminPrintNfcReader'])->name('admin.qr.nfc-reader');
    Route::get('admin/dynamic-qr/qr-nfc', [QrNfcController::class, 'index'])->name('admin.qr-nfc.index');
    Route::post('admin/dynamic-qr/qr-nfc', [QrNfcController::class, 'store'])->name('admin.qr-nfc.store');
    Route::delete('admin/dynamic-qr/qr-nfc/{qrTemplate}', [QrNfcController::class, 'destroy'])->name('admin.qr-nfc.destroy');

    Route::resource('admin/qr-templates', QrTemplateController::class)
        ->parameters(['qr-templates' => 'qrTemplate'])
        ->names('admin.qr-templates');
    Route::post('admin/qr-templates/{qrTemplate}/produce', [QrTemplateController::class, 'produce'])
        ->name('admin.qr-templates.produce');
    Route::get('admin/qr-templates/{qrTemplate}/print', [QrUnitController::class, 'printTemplate'])
        ->name('admin.qr-templates.print');
    Route::get('admin/qr-units', [QrUnitController::class, 'index'])->name('admin.qr-units.index');
    Route::get('admin/qr-units/batch/{batch}/print', [QrUnitController::class, 'printBatch'])->name('admin.qr-units.batch.print');
    Route::get('admin/qr-units/batch/{batch}/export', [QrUnitController::class, 'exportBatch'])->name('admin.qr-units.batch.export');
    Route::get('admin/qr-units/{qrUnit}', [QrUnitController::class, 'show'])->name('admin.qr-units.show');
    Route::delete('admin/qr-units/{qrUnit}', [QrUnitController::class, 'destroy'])->name('admin.qr-units.destroy');
    Route::post('admin/qr-units/{qrUnit}/disable', [QrUnitController::class, 'disable'])->name('admin.qr-units.disable');
    Route::post('admin/qr-units/{qrUnit}/enable', [QrUnitController::class, 'enable'])->name('admin.qr-units.enable');
    Route::post('admin/qr-units/{qrUnit}/reset', [QrUnitController::class, 'reset'])->name('admin.qr-units.reset');

    // DELETE (Semua aksi penghapusan)
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('sheep/{sheep}', [SheepController::class, 'destroy'])->name('sheep.destroy');
    Route::delete('shelters/{shelter}', [ShelterController::class, 'destroy'])->name('shelters.destroy');
    Route::delete('symptoms/{symptom}', [SymptomController::class, 'destroy'])->name('symptoms.destroy');
    Route::delete('feed-types/{feed_type}', [FeedTypeController::class, 'destroy'])->name('feed-types.destroy');
    Route::delete('feeding-records/{feeding_record}', [FeedingRecordController::class, 'destroy'])->name('feeding-records.destroy');
    Route::delete('profit-loss/{profit_loss}', [ProfitLossRecordController::class, 'destroy'])->name('profit-loss.destroy');

    // DELETE Timbangan, Kesehatan, Reproduksi (Resource Nested)
    Route::delete('weights/{weight}', [WeightRecordController::class, 'destroy'])->name('weights.destroy');
    Route::delete('health-records/{health_record}', [HealthRecordController::class, 'destroy'])->name('health-records.destroy');
    Route::delete('reproduction-records/{reproduction_record}', [ReproductionRecordController::class, 'destroy'])->name('reproduction-records.destroy');
    Route::delete('places/{place}', [PlaceController::class, 'destroy'])->name('places.destroy');
    Route::post('places/{place}/template/confirm-payment', [PlaceController::class, 'confirmTemplatePayment'])
        ->name('places.template.confirm-payment');

    Route::resource('users', UserController::class);

    Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/{partner}', [PartnerController::class, 'show'])->name('partners.show');
});

// === GRUP RUTE UTAMA (CRUD Non-Delete - Semua Boleh Akses) ===
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'legacy-user'])->group(function () {

    // Daftar tempat, tautan Google, dan QR ulasan
    Route::post('places/qr', [PlaceController::class, 'createQrDraft'])->name('places.drafts.store');
    Route::get('places/{place}/qr/download', [PlaceController::class, 'downloadQr'])->name('places.qr.download');
    Route::resource('places', PlaceController::class)->except(['destroy']);

    // 1. Domba
    Route::resource('sheep', SheepController::class)->except(['destroy']);
    Route::get('/sheep/{sheep}/print', [SheepController::class, 'printCard'])->name('sheep.print');

    // 2. Kandang
    Route::resource('shelters', ShelterController::class)->except(['show', 'destroy']);
    Route::get('/shelters/{shelter}/capacity', [ShelterController::class, 'getCapacity'])->name('shelters.capacity');

    // 3. Timbangan
    Route::resource('sheep.weights', WeightRecordController::class)
        ->shallow()
        ->only(['create', 'store']);

    // 4. Gejala (Master Data)
    Route::resource('symptoms', SymptomController::class)->except(['show', 'destroy']);

    // 5. Kesehatan
    Route::resource('sheep.health-records', HealthRecordController::class)
        ->shallow()
        ->only(['create', 'store', 'edit', 'update']);

    // 6. Reproduksi
    Route::resource('sheep.reproduction-records', ReproductionRecordController::class)
        ->shallow()
        ->only(['create', 'store', 'edit', 'update']);

    // 7. Jenis Pakan
    Route::resource('feed-types', FeedTypeController::class)->except(['show', 'destroy']);

    // 8. Pemberian Pakan
    Route::resource('feeding-records', FeedingRecordController::class)->except(['destroy']);

    // 9. Keuangan
    Route::resource('profit-loss', ProfitLossRecordController::class)->except(['show', 'edit', 'update', 'destroy']);

        // Rute untuk Request Penempatan (Hanya bisa diakses Mitra untuk ACC/Reject)
    Route::get('/placement-requests', [App\Http\Controllers\PlacementRequestController::class, 'index'])
        ->name('placement-requests.index');

    Route::post('/placement-requests/{id}/approve', [App\Http\Controllers\PlacementRequestController::class, 'approve'])
        ->name('placement-requests.approve');

    Route::post('/placement-requests/{id}/reject', [App\Http\Controllers\PlacementRequestController::class, 'reject'])
        ->name('placement-requests.reject');
});

require __DIR__ . '/auth.php';
