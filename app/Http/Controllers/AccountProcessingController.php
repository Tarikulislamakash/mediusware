<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;

class AccountProcessingController extends Controller
{

    public function dashboard()
    {
        $transactions = auth()->user()->transactions;
        return view('dashboard', compact('transactions'));
    }



    public function deposits()
    {
        $deposits = auth()->user()->transactions()->where('transaction_type', 1)->get();
        return view('deposits', compact('deposits'));
    }



    public function deposit()
    {
        return view('deposit');
    }



    public function depositSubmit(Request $request)
    {
        Transactions::create([
            'user_id' => auth()->user()->id,
            'transaction_type' => 1,
            'amount' => $request->amount,
            'date' => now(),
        ]);

        auth()->user()->update([
            'balance' => auth()->user()->balance + $request->amount,
        ]);

        return redirect()->route('dashboard')->with('deposit_success', 'Balance deposited successfully');
    }



    public function withdraws()
    {
        $withdraws = auth()->user()->transactions()->where('transaction_type', 2)->get();
        return view('withdraws', compact('withdraws'));
    }



    public function withdraw()
    {
        return view('withdraw');
    }



    public function withdrawSubmit(Request $request)
    {
        $user = auth()->user();
        $withdrawAmount = $request->amount;
        $accountType = $user->account_type;
        $withdrawFeeRate = 0;
        $totalWithdrawalAmount = 0;
        $withdrawFee = 0;

        // Determine the withdrawal fee rate based on the account type
        if ($accountType == 1)
        {
            $currentMonthWithdrawn = $user->transactions()
                ->where('transaction_type', 2)
                ->whereMonth('date', now()->month)
                ->sum('amount');

            $freeWithdrawLimit = 5000;

            // Check if it's a Friday, the first 1K withdrawal, or within the 5K free limit
            if (now()->dayOfWeek === 5 || ( $withdrawAmount <= 1000 && ($currentMonthWithdrawn + $withdrawAmount) <= $freeWithdrawLimit ))
            {
                $withdrawFeeRate = 0;
            }
            else
            {
                $withdrawFeeRate = 0.015;
            }

            $withdrawFee = ($withdrawAmount <= 1000 ? $withdrawAmount : $withdrawAmount - 1000) * $withdrawFeeRate;
            $totalWithdrawalAmount = $withdrawAmount + $withdrawFee;

        }
        elseif ($accountType == 2)
        {
            $totalWithdrawn = $user->transactions()
                ->where('transaction_type', 2)
                ->sum('amount');

            if ($totalWithdrawn >= 50000)
            {
                $withdrawFeeRate = 0.015;
            }
            else
            {
                $withdrawFeeRate = 0.025;
            }

            $withdrawFee = $withdrawAmount * $withdrawFeeRate;
            $totalWithdrawalAmount = $withdrawAmount + $withdrawFee;
        }

        if ($totalWithdrawalAmount <= $user->balance)
        {
            // Create the withdrawal transaction with fee
            Transactions::create([
                'user_id' => $user->id,
                'transaction_type' => 2,
                'amount' => $totalWithdrawalAmount,
                'fee' => $withdrawFee,
                'date' => now(),
            ]);

            // Update the user's balance (deduct withdrawal amount and fee)
            $user->update([
                'balance' => $user->balance - $totalWithdrawalAmount,
            ]);
        }
        else
        {
            return redirect()->route('dashboard')->with('withdraw_failed', 'Insufficient balance.');
        }


        return redirect()->route('dashboard')->with('withdraw_success', 'Balance withdrawal done successfully');

    }

}
