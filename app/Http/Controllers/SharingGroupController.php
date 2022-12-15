<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Models\GroupMember;

use App\Models\SharingGroup;

use App\Models\StreamCredential;

use App\Models\User;

use App\Models\Transaction;

use Illuminate\Support\Facades\Http;

use DateTime;

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
        $sharingGroup = SharingGroup::select('id', 'packet', 'duration', 'price')
        ->where('sharing_groups.id', '=', $id)
        ->get();

        if(count($sharingGroup) < 1) {
            return redirect()->route('dashboard')->with('error', 'Grup sharing tidak ditemukan');
        }

        $existedMembership = GroupMember::select('user_id', 'group_id')
        ->where('user_id', '=', Auth::id())
        ->where('group_id', '=', $id)
        ->get();

        if(count($existedMembership) == 0) {
            $membership = GroupMember::create([
                'user_id' => Auth::id(),
                'group_id' => $id
            ]);

            if(isset($membership) != 1) {
                return redirect()->route('detail-sharing-group', ['id' => $id])->with('error', 'Terjadi kesalahan saat bergabung');
            }
        }

        return view('bayar', ['sharingGroup' => $sharingGroup[0]]);
    }

    public function showPaymentMethod($id) {
        return view('metode-pembayaran', ['id' => $id]);
    }

    public function getPaymentCredentials($id) {
        $sharingGroup = SharingGroup::select('id', 'price')
        ->where('sharing_groups.id', '=', $id)
        ->get();

        if(count($sharingGroup) < 1) {
            return redirect()->route('dashboard')->with('error', 'Grup sharing tidak ditemukan');
        }

        $membership = GroupMember::select('id')
        ->where('user_id', '=', Auth::id())
        ->where('group_id', '=', $id)
        ->get();

        $admin = 5000;
        $totalPrice = $sharingGroup[0]['price'];

        $transaction = Transaction::create([
            'total_price' => $totalPrice,
            'payment_method' => request()['method'],
            'member_id' => $membership[0]['id'],
            'status' => 'NOT SET'
        ]);

        $AUTH_STRING = "Basic " . base64_encode(env('MIDTRANS_SERVER_KEY') . ":");

        $payment_type = array(
            "BCA Transfer" => "bank_transfer",
            "BNI Transfer" => "bank_transfer",
            "BRI Transfer" => "bank_transfer",
            "Mandiri Transfer" => "echannel",
            "Shopeepay Transfer" => "gopay",
            "Gopay Transfer" => "gopay"
        );

        $req_body = [
            "payment_type" => $payment_type[request()['method']],
            "transaction_details" => [
                'order_id' => $transaction['id'],
                "gross_amount" => $totalPrice
            ]
        ];

        if($payment_type[request()['method']] == 'bank_transfer') {
            $req_body['bank_transfer']['bank'] = explode(' ', strtolower(request()['method']))[0];
        }

        if($payment_type[request()['method']] == 'echannel') {
            $req_body['echannel']['bill_info1'] = $totalPrice;
        }
        
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => $AUTH_STRING
        ])
        ->withBody(json_encode($req_body), 'application/json')
        ->post('https://api.sandbox.midtrans.com/v2/charge');

        $response = $response->json();

        if($response['status_code'] == '201') {
            if($payment_type[request()['method']] == 'bank_transfer') {
                $transaction->virtual_account = $response['va_numbers'][0]['va_number'];
            } else if ($payment_type[request()['method']] == 'echannel') {
                $transaction->virtual_account = $response['bill_key'] . ' ' . $response['biller_code'];
            } else if ($payment_type[request()['method']] == 'gopay') {
                $transaction->qr_link = $response['actions'][0]['url'];
                $transaction->deep_link = $response['actions'][1]['url'];
            } else {
                $transaction->virtual_account = $response->json()['permata_va_number'];
            } 

            $transaction->transaction_id = $response['transaction_id'];
            $payment_expiry_time = new DateTime();
            $payment_expiry_time->modify('+1 day');
            $transaction->payment_expiry_time = $payment_expiry_time->format('Y-m-d H:i:s');
            $transaction->payment_method = request()['method'];
            $transaction->status = 'WAITING_FOR_PAYMENT';
        }
        
        $transaction->save();

        if($transaction->transaction_id) {
            return redirect()->route('payment-instruction', ['id' => $transaction['transaction_id']]);
        } else {
            return redirect()->route('pick-payment', ['id' => $id])->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function getPaymentInstruction($id) {
        $transaction = Transaction::where('transaction_id', '=', $id)->get();
        $membership = GroupMember::where('id', '=', $transaction[0]['member_id'])->get();

        return view('instruksi-pembayaran', ['transaction' => $transaction[0], 'member' => $membership[0]]);
    }

    public function getPaymentNotification() {
        $notif = request()->input();

        $transaction = $notif['transaction_status'];
        $type = $notif['payment_type'];
        $transaction_id = $notif['transaction_id'];
        $fraud = $notif['fraud_status'];

        $order = Transaction::where('transaction_id', '=', $transaction_id)->first();

        if ($transaction == 'settlement') {
            $order->status = 'PAYMENT_DONE';
        }
        else if($transaction == 'pending') {
            $order->status = 'WAITING_FOR_PAYMENT';
        }
        else if ($transaction == 'deny') {
            $order->status = 'REJECTED';
        }
        else if ($transaction == 'expire') {
            $order->status = 'CANCELED';
        }
        else if ($transaction == 'cancel') {
            $order->status = 'CANCELED';
        }

        $order->save();

        $groupSharing = GroupMember::leftJoin('sharing_groups', 'sharing_groups.id', '=', 'group_members.group_id')
        ->where('group_members.id', '=', $order->member_id)
        ->first();

        $owner = User::where('id', '=', $groupSharing['owner_id'])->first();
        $owner->balance = $owner->balance + $order->total_price;
        $owner->save();

        return response('ok', 200);
    }
}