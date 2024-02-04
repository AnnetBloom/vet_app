<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreSettingsRequest;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;

use function Otis22\VetmanagerUrl\url;

class UserSettingsController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user()->load('keySettings');
        return view('user.api_settings', ['user' => $user]);
    }

    public function store(StoreSettingsRequest $request): RedirectResponse 
    {
        $user = Auth::user()->load('keySettings');
        $settings = $user->keySettings ?: new UserSetting;
        $settings->fill($request->validated());
        $user->keySettings()->save($settings);

        $request->user()->refresh();
        session()->flash('success', __('main.saved'));
        return redirect(route('user_settings'));
    }

    public function getUserUrl(Request $request)
    {
        $validated = $request->validate([
            'domen' => 'required|string|max:100',
        ]);
        $url = url($validated['domen'])->asString();
      
        return response()->json(['url' => $url]);
    }
}
