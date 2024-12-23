<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            'search' => ['string', 'nullable'],
            'page' => ['integer', 'nullable'],
            'sortBy' => ['string', 'nullable', Rule::in('name', 'email', 'created_at')]
        ]);

        $page = $request->page ?? 1;
        $limit = 10;

        $query = User::query();

        if($request->search && $request->search != '')
        {
            $query->whereLike('name', "%".$request->search."%")->orWhereLike('email', "%".$request->search."%");
        }

        $query->withCount('orders')
                ->active()
                ->offset(($page - 1) * $limit)
                ->limit($limit)
                ->orderBy($request->sortBy ?? 'created_at', 'desc');



        return [
            'page' => (int) $request->page ?? 1,
            'users' => $query->get()
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string', 'min:8'],
            'name' => ['required', 'string', 'min:3', 'max:50']
        ]);


        $user = User::create($validated);

        return User::find($user)->makeHidden('active');
    }
}
