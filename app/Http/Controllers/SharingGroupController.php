<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Models\GroupMember;

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

    public function showSharingGroupDetails($id) {
        $platform = request()->query('platform', 'Netflix');
        $sharingGroup = SharingGroup::leftJoin('users', 'sharing_groups.owner_id', '=', 'users.id')
        ->leftJoin('group_members', 'group_members.group_id', '=', 'sharing_groups.id')
        ->select('sharing_groups.id', 'sharing_groups.price', 'sharing_groups.packet', 'sharing_groups.quota', 'sharing_groups.owner_id','users.name', 'group_members.user_id as member')
        ->where('sharing_groups.id', '=', $id)
        ->get();

        if(count($sharingGroup) < 1) {
            return redirect()->route('dashboard')->with('error', 'Grup sharing tidak ditemukan');
        }

        // echo $sharingGroup;

        return view('detail', ['sharingGroup' => $sharingGroup]);
    }

    public function showJoinConfirmation($id) {
        $existedMembership = GroupMember::select('user_id', 'group_id')
        ->where('user_id', '=', Auth::id())
        ->where('group_id', '=', $id)
        ->get();

        if(count($existedMembership) > 1) {
            return redirect()->route('detail-sharing-group',  ['id' => $id])->with('error', 'Anda telah bergabung ke grup sharing ini');
        }

        $membership = GroupMember::create([
            'user_id' => Auth::id(),
            'group_id' => $id
        ]);

        if(isset($membership) != 1) {
            return redirect()->route('detail-sharing-group', ['id' => $id])->with('error', 'Terjadi kesalahan saat bergabung');
        }

        $sharingGroup = SharingGroup::leftJoin('users', 'sharing_groups.owner_id', '=', 'users.id')
        ->leftJoin('group_members', 'group_members.group_id', '=', 'sharing_groups.id')
        ->select('sharing_groups.id', 'sharing_groups.price', 'sharing_groups.packet', 'sharing_groups.quota', 'sharing_groups.owner_id','users.name', 'group_members.user_id as member')
        ->where('sharing_groups.id', '=', $id)
        ->get();

        if(count($sharingGroup) < 1) {
            return redirect()->route('dashboard')->with('error', 'Grup sharing tidak ditemukan');
        }

        return view('bayar', ['sharingGroup' => $sharingGroup]);
    }
}