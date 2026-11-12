<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $pegawai = $user->pegawai()->with('unitKerja')->first();
        $unitKerjas = \App\Models\UnitKerja::orderBy('nama_unit')->get();

        return view('profile.edit', [
            'user' => $user,
            'pegawai' => $pegawai,
            'unitKerjas' => $unitKerjas,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->pegawai) {
            $pegawaiData = [];
            if ($request->filled('jabatan')) {
                $pegawaiData['jabatan'] = $request->input('jabatan');
            }
            if ($request->has('unit_kerja_id')) {
                $pegawaiData['unit_kerja_id'] = $request->input('unit_kerja_id') ?: null;
            }
            if ($request->filled('name')) {
                $pegawaiData['nama'] = $request->input('name');
                $pegawaiData['nama_lengkap'] = $request->input('name');
            }
            if ($request->has('no_hp')) {
                $pegawaiData['no_hp'] = $request->input('no_hp');
            }
            if ($request->has('alamat')) {
                $pegawaiData['alamat'] = $request->input('alamat');
            }
            if ($request->hasFile('foto_profil')) {
                $file = $request->file('foto_profil');
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                if (!file_exists(public_path('uploads'))) {
                    mkdir(public_path('uploads'), 0777, true);
                }
                $file->move(public_path('uploads'), $filename);
                $pegawaiData['foto_profil'] = $filename;
            }
            if (!empty($pegawaiData)) {
                $user->pegawai->update($pegawaiData);
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
