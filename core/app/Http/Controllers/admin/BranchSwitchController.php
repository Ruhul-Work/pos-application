<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\backend\Branch;
use App\Support\BranchScope;

use Illuminate\Http\Request;


class BranchSwitchController extends Controller
{
     public function switch(Request $req)
    {
        $user = $req->user();

        $data = $req->validate([
            'mode'      => 'required|in:all,one',
            'branch_id' => 'nullable|integer',
        ]);

        // super হলে যেকোনো ব্রাঞ্চ/All
        if ($user?->isSuper()) {
            if ($data['mode'] === 'all') {
                BranchScope::setAll();
            } else {
                $bid = (int) ($data['branch_id'] ?? 0);
                abort_unless(Branch::where('is_active',1)->whereKey($bid)->exists(), 422, 'Invalid branch');
                BranchScope::setBranch($bid);
            }
            return response()->json(['ok'=>true, 'scope'=>session('branch_scope')]);
        }

        // normal user: সবসময় নিজের ব্রাঞ্চ
        BranchScope::setBranch((int) $user->branch_id);
        return response()->json(['ok'=>true, 'scope'=>session('branch_scope')]);
    }
}
