<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/login', function () {
    // Store the requested URL in the session
    session()->put('intended_url', url()->previous());

    // Redirect to the OIDC provider for authentication
    return Socialite::driver('openidconnect')
        ->scopes(['profile', 'email', 'groups'])
        ->redirect();
})->name('login');

Route::get('/auth/callback', function () {
    // Retrieve the requested URL from the session
    $intendedUrl = session('intended_url');

    // Get user information from the OIDC provider and update or create the user in the database.
    $oidcUser = Socialite::driver('openidconnect')->stateless()->user();
    $user = User::where('oidc_sub', $oidcUser->id)->first()
        ?? User::where('email', $oidcUser->email)->first()
        ?? new User;

    $user->fill([
        'oidc_sub' => $oidcUser->id,
        'username' => $oidcUser->user['preferred_username'],
        'name' => $oidcUser->name,
        'firstname' => $oidcUser->user['given_name'],
        'lastname' => $oidcUser->user['family_name'],
        'email' => $oidcUser->email,
        'groups' => json_encode($oidcUser->user['groups']) ?? json_encode([]),
        'avatar' => $oidcUser->user['picture'] ?? null,
        'oidc_token' => $oidcUser->token,
        'oidc_refresh_token' => $oidcUser->refreshToken,
        'oidc_id_token' => $oidcUser->accessTokenResponseBody['id_token'],
    ])->save();

    // Log the user in
    Auth::login($user);

    // Redirect to the requested URL
    return redirect()->intended($intendedUrl);
});

Route::get('/auth/logout', function () {
    $idToken = auth()->user()->oidc_id_token;

    // Log out the user from the application
    Auth::logout();

    // Tell the OIDC provider to log out the user and redirect to the last page visited in the application
    return Socialite::driver('openidconnect')->logout($idToken, url()->previous());
})->name('logout');

Route::post('/auth/backchannel-logout', function (Request $request) {
    $logoutToken = $request->input('logout_token');

    if (! $logoutToken) {
        return response()->json(['error' => 'invalid_request'], 400);
    }

    try {
        $claims = Socialite::driver('openidconnect')->verifyLogoutToken($logoutToken);
    } catch (Throwable $e) {
        return response()->json(['error' => 'invalid_token'], 400);
    }

    DB::table('sessions')
        ->whereIn('user_id', User::where('oidc_sub', $claims['sub'] ?? null)->pluck('id'))
        ->delete();

    return response('', 200)->header('Cache-Control', 'no-cache, no-store');
})->name('backchannel-logout');
