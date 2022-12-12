<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Models\SharingGroup;

use App\Models\StreamCredential;

class SharingGroupController extends Controller
{
    public function showCreateSharingGroupForm() {
        return view('create-sharing-group-form');
    }

    public function createSharingGroup() {
        $validated = request()->validate([
            'platform' => 'required|in:Netflix,Disney+,Spotify,Joox',
            'quota' => 'required|integer|min:1|max:20',
            'price' => 'required|min:0|max:1000000',
            'packet' => 'required',
            'duration' => 'required|integer|min:1|max:100',
            'credentials' => 'required'
        ]);

        $sharingGroupData = [
            'platform' => $validated['platform'],
            'quota' => $validated['quota'],
            'price' => $validated['price'],
            'packet' => $validated['packet'],
            'duration' => $validated['duration'],
            'owner_id' => Auth::id()
        ];

        $credentialsData = $validated['credentials'];

        DB::transaction(function () use($sharingGroupData, $credentialsData) {
            $sharingGroup = SharingGroup::create($sharingGroupData);
            
            foreach($credentialsData as $credential) {
                StreamCredential::create([
                    'email' => $credential['email'],
                    'password' => $credential['password'],
                    'group_id' => $sharingGroup['id']
                ]);
            }
        }, 5);

        return redirect()->route('dashboard');
        // return redirect()->route('group-sharing-details', ['id' => $sharingGroup->id]);
    }

    public function showDashboard() {
        $platform = request()->query('platform', 'Netflix');
        $sharingGroups = SharingGroup::leftJoin('users', 'sharing_groups.owner_id', '=', 'users.id')
        ->leftJoin('group_members', 'group_members.group_id', '=', 'sharing_groups.id')
        ->selectRaw('sharing_groups.id, sharing_groups.price, sharing_groups.packet, sharing_groups.quota, users.name, count(group_members.user_id) as member')
        ->groupBy('sharing_groups.id')
        ->groupBy('users.name')
        ->where('sharing_groups.platform', '=', $platform)
        ->havingRaw('count(group_members.user_id) < sharing_groups.quota')
        ->get();
        
        return view('dashboard', ['sharingGroups' => $sharingGroups]);
    }

    public function getSharingGroupDetails($id) {
        $sharingGroup = SharingGroup::where('id', $id);

        if($sharingGroup) {
            return redirect()->route('dashboard')->with('error', 'Grup sharing tidak ditemukan');
        }

        return view('group-sharing-details', ['sharingGroup' => $sharingGroup]);
    }
}