<?php

use App\Http\Controllers\BotManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestManagementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

if (env('APP_ENV') !== 'local') {
    URL::forceScheme('https');
}

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [BotManagementController::class, 'dashboard'])->name('dashboard');

    Route::get('/faqs', [BotManagementController::class, 'faqs'])->name('bot.faqs');
    Route::post('/faqs', [BotManagementController::class, 'storeFaq'])->name('bot.faqs.store');
    Route::patch('/faqs/{faq}', [BotManagementController::class, 'updateFaq'])->name('bot.faqs.update');
    Route::delete('/faqs/{faq}', [BotManagementController::class, 'destroyFaq'])->name('bot.faqs.destroy');

    Route::get('/official-links', [BotManagementController::class, 'links'])->name('bot.links');
    Route::post('/official-links', [BotManagementController::class, 'storeLink'])->name('bot.links.store');
    Route::patch('/official-links/{officialLink}', [BotManagementController::class, 'updateLink'])->name('bot.links.update');
    Route::delete('/official-links/{officialLink}', [BotManagementController::class, 'destroyLink'])->name('bot.links.destroy');

    Route::get('/contacts', [BotManagementController::class, 'contacts'])->name('bot.contacts');
    Route::post('/contacts', [BotManagementController::class, 'storeContact'])->name('bot.contacts.store');
    Route::patch('/contacts/{contact}', [BotManagementController::class, 'updateContact'])->name('bot.contacts.update');
    Route::delete('/contacts/{contact}', [BotManagementController::class, 'destroyContact'])->name('bot.contacts.destroy');
    Route::post('/contacts/{contact}/schedules', [BotManagementController::class, 'storeContactSchedule'])->name('bot.contacts.schedules.store');
    Route::delete('/contacts/schedules/{contactSchedule}', [BotManagementController::class, 'destroyContactSchedule'])->name('bot.contacts.schedules.destroy');

    Route::get('/human-contact-requests', [BotManagementController::class, 'humanContacts'])->name('bot.human-contacts');
    Route::patch('/human-contact-requests/{humanContactRequest}', [BotManagementController::class, 'updateHumanContact'])->name('bot.human-contacts.update');
    Route::delete('/human-contact-requests/{humanContactRequest}', [BotManagementController::class, 'destroyHumanContact'])->name('bot.human-contacts.destroy');

    Route::get('/bot-settings', [BotManagementController::class, 'settings'])->name('bot.settings');
    Route::patch('/bot-settings/{botSetting}', [BotManagementController::class, 'updateSetting'])->name('bot.settings.update');
    Route::post('/knowledge-categories', [BotManagementController::class, 'storeCategory'])->name('bot.categories.store');

    Route::get('/bot-status', [BotManagementController::class, 'getBotStatus'])->name('bot.status');
    Route::get('/notifications', [BotManagementController::class, 'notifications'])->name('notifications.index');
    Route::post('/bot/logout', [BotManagementController::class, 'logoutBot'])->name('bot.logout');
    Route::post('/bot/restart', [BotManagementController::class, 'restartBot'])->name('bot.restart');

    Route::get('/requests', [RequestManagementController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [RequestManagementController::class, 'create'])->name('requests.create');
    Route::post('/requests', [RequestManagementController::class, 'store'])->name('requests.store');
    Route::get('/requests/{botRequest}', [RequestManagementController::class, 'show'])->name('requests.show');
    Route::patch('/requests/{botRequest}', [RequestManagementController::class, 'update'])->name('requests.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
