<?php

namespace App\Http\Controllers\Settings;

use App\Domain\Settings\Models\CompanyProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\CompanyProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CompanyProfileController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('settings/CompanyProfile', [
            'company' => CompanyProfile::current(),
        ]);
    }

    public function update(CompanyProfileUpdateRequest $request): RedirectResponse
    {
        CompanyProfile::current()->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Company profile updated.')]);

        return to_route('company-profile.edit');
    }
}
