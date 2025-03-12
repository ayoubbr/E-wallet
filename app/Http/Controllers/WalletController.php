<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    public function deposit(Request $request)
    {
        $depositData = $request->validate([
            'amount' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $sender = auth()->user();

            if ($sender == null) {
                return response()->json(['message' => 'Sender email not found!']);
            } else if ($sender->wallet == null) {
                return response()->json(['message' => 'Sender wallet not found!']);
            } else {
                $amount =  $depositData['amount'];

                $transfer = Transfer::create([
                    'serial' => strtoupper(Str::random(10)),
                    'status' => 'done',
                    'amount' => $amount,
                    'sender_wallet' => $sender->wallet->id,
                    'receiver_wallet' => $sender->wallet->id

                ]);

                $sender->wallet->balance += $amount;
                $sender->wallet->save();
            }

            DB::commit();
            return response()->json([
                'sender_wallet' => $sender->wallet,
                'transfer' => $transfer
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function withdraw(Request $request)
    {
        $withdrawalData = $request->validate([
            'amount' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $sender = auth()->user();

            if ($sender == null) {
                return response()->json(['message' => 'User not found!']);
            } else if ($sender->wallet == null) {
                return response()->json(['message' => 'User wallet not found!']);
            } else {
                $amount =  $withdrawalData['amount'];

                $transfer = Transfer::create([
                    'serial' => strtoupper(Str::random(10)),
                    'status' => 'done',
                    'amount' => $amount,
                    'sender_wallet' => $sender->wallet->id,
                    'receiver_wallet' => $sender->wallet->id
                ]);

                $sender->wallet->balance -= $amount;
                $sender->wallet->save();
            }

            DB::commit();
            return response()->json([
                'sender_wallet' => $sender->wallet,
                'transfer' => $transfer
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
