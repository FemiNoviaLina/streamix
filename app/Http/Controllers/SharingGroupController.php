<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SharingGroup;

class SharingGroupController extends Controller
{
    public function createGroupSharing() {
        $validated = request()->validate([
            'platform' => 'required|in:Netflix,Disney+,Spotify,Joox',
            'quota' => 'required|integer|min:1|max:20',
            'price' => 'required|min:0|max:1000000',
            'packet' => 'required',
            'duration' => 'required|integer|min:1|max:100'
        ]);
        
        if ($validator->fails()) {
            $error = $validator->messages()->get('*');
            return view('create-group-sharing-form')->with('error', $error);
        } 
        
        $sharingGroup = SharingGroup::create($validated);
        return redirect()->route('group-sharing-details', ['id' => $sharingGroup->id]);
    }

    public function showDashboard() {
        $sharingGroups = SharingGroup::all();

        return view('dashboard', ['sharingGroups' => $sharingGroups]);
    }

    public function getGroupsharingDetails($id) {
        $sharingGroup = SharingGroup::where('id', $id);

        if($sharingGroup) {
            return redirect()->route('dashboard')->with('error', 'Grup sharing tidak ditemukan');
        }

        return view('group-sharing-details', ['sharingGroup' => $sharingGroup]);
    }
}