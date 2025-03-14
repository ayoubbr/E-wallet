<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\User;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransferController extends Controller
{

    public function transfer(Request $request)
    {
        $transferData = $request->validate([
            'amount' => 'required',
            'receiver_email' => 'required',
            'receiver_firstname' => 'required',
            'receiver_lastname' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $sender = auth()->user();
            $receiver = User::where('email', $transferData['receiver_email'])->first();

            if ($sender == null) {
                return response()->json(['message' => 'Sender email not found!']);
            } else if ($receiver == null) {
                return response()->json(['message' => 'Receiver email not found!']);
            } else if ($receiver->firstname != $transferData['receiver_firstname'] || $receiver->lastname != $transferData['receiver_lastname']) {
                return response()->json(['message' => 'Receiver data do not match any user!']);
            } else if ($sender->wallet == null) {
                return response()->json(['message' => 'Sender wallet not found!']);
            } else if ($receiver->wallet == null) {
                return response()->json(['message' => 'Receiver wallet not found!']);
            } else {
                $amount =  $transferData['amount'];
                if ($sender->wallet->balance < $amount) {
                    return response()->json(['message' => 'Your fonds are not enough!']);
                } else {
                    Transfer::create([
                        'serial' => strtoupper(Str::random(10)),
                        'status' => 'done',
                        'amount' => $amount,
                        'sender_wallet' => $sender->wallet->id,
                        'receiver_wallet' => $receiver->wallet->id

                    ]);

                    $sender->wallet->balance -= $amount;
                    $sender->wallet->save();

                    $receiver->wallet->balance += $amount;
                    $receiver->wallet->save();
                }
            }

            DB::commit();
            return response()->json([
                'sender_wallet' => $sender->wallet,
                'receiver_wallet' => $receiver->wallet
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function rollback(Request $request)
    {
        $rollbackData = $request->validate([
            'serial' => 'required'
        ]);

        $transfer = Transfer::where('serial', $rollbackData['serial'])->first();

        if (is_null($transfer)) {
            return response()->json(['message' => 'Data not found!']);
        } else {

            $sender_wallet = $transfer->sender_w;
            $receiver_wallet = $transfer->receiver_w;

            if (is_null($sender_wallet)) {
                return response()->json([
                    'message' => 'Sender wallet not found!'
                ]);
            } else if (is_null($receiver_wallet)) {
                return response()->json([
                    'message' => 'Receiver wallet not found!'
                ]);
            } else {

                $sender_wallet->balance += $transfer->amount;
                $receiver_wallet->balance -= $transfer->amount;

                $transfer->status = 'rolledback';
                $transfer->admin_id = auth()->user()->id;

                $sender_wallet->save();
                $receiver_wallet->save();
                $transfer->save();


                return response()->json([
                    'transfer' => $transfer
                ]);
            }
        }
    }
}
