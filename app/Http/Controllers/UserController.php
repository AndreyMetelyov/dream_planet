<?php

namespace App\Http\Controllers;

use App\Models\Gem;
use Illuminate\Http\Request;
use App\User;
use App\ElfsGemtypes;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function showAllUsers(Request $req)
    {
        $query = $req->query();
        $q = [];
        $fillFields = ['name' => null, 'status' => null];

        foreach ($query as $key => $value) {
            $fillFields[$key] = $value;
            if ($value == 'All' || is_null($value) || $key == '_token') continue;
            if ($key == 'name') $q[] = ['name', 'like', '%' . $value . '%'];
            if ($key == 'status' && $value == 'Active') $q[] = ['active', '=', '1'];
            if ($key == 'status' && $value == 'Deleted') $q[] = ['active', '=', '0'];
        }
        $users = User::where($q)->get();

        foreach ($users as $user) {
            if ($user->group == 'gnome') {
                $earnedGemsCount = Gem::where('earner', $user->id)->count();
                $user->earnedGemsCount = $earnedGemsCount;
            }
            if ($user->group == 'elf') {
                $ownedGemsCount = Gem::where('owner', $user->id)->count();
                $user->ownedGemsCount = $ownedGemsCount;

                $userGemtypes = DB::table('elfs_gemtypes')
                    ->leftJoin('users', 'userId', '=', 'users.id')
                    ->leftJoin('gem_types', 'gemtypeId', '=', 'gem_types.id')
                    ->where('userId', $user->id)
                    ->orderByDesc('coeff')
                    ->get();
                $user->top3 = [];
                $top3 = [];
                foreach ($userGemtypes as $gem) {
                    $top3[] = $gem->type;
                    if (count($top3) >= 3) break;
                }
                $user->top3 = $top3;
            }
        }
        return view('users.showAllUsers', compact('users', 'fillFields'));
    }
    public function showOneUser($id)
    {
        $user = User::find($id);
        if ($user->group == 'elf') {
            $userGemtypes = DB::table('elfs_gemtypes')
                ->leftJoin('users', 'userId', '=', 'users.id')
                ->leftJoin('gem_types', 'gemtypeId', '=', 'gem_types.id')
                ->where('userId', $id)
                ->get();

            $gemtypes = DB::table('gem_types')->select('id', 'type')->where('active', true)->get();
            $gt = [];
            foreach ($gemtypes as $gemtype) {
                $gt[$gemtype->type] = ['coeff' => 0, 'gemId' => $gemtype->id];
            }
            foreach ($userGemtypes as $userGemtype) {
                $gt[$userGemtype->type] = ['coeff' => $userGemtype->coeff, 'gemId' => $userGemtype->id];
            }
            $userGemtypes = $gt;

            $unconfirmedGems = DB::table('gems')
                ->leftJoin('gem_types', 'gemtype', '=', 'gem_types.id')
                ->select('gems.*', 'gem_types.type')
                ->where('owner', $id)
                ->where('status', 'assigned')
                ->get();
            $receivedGems = Gem::select(DB::raw('gem_types.type, COUNT(gem_types.type) as count'))
                ->leftJoin('gem_types', 'gemtype', '=', 'gem_types.id')
                ->where('owner', $id)
                //->where('status', 'confirmed')
                ->groupBy('gem_types.type')
                ->get();
            //receved gems ==== gem table 'owner' field
            return view('users.elfProfile', compact('user', 'userGemtypes', 'unconfirmedGems', 'receivedGems'));
        } elseif ($user->group == 'gnome') {
            $extractedGems = Gem::select(DB::raw('gem_types.type, COUNT(gem_types.type) as count'))
                ->leftJoin('gem_types', 'gemtype', '=', 'gem_types.id')
                ->where('earner', $id)
                //->where('status', 'confirmed')
                ->groupBy('gem_types.type')
                ->get();

            return view('users.gnomeProfile', compact('user', 'extractedGems'));
        } else throw new Exception('user without group');
    }
    public function editUser(Request $req)
    {
        $userId = explode('/', $req->path())[1];

        $name = $req->input('name');
        $login = $req->input('login');
        $password = $req->input('password');

        $user = User::find($userId);
        $user->name = $name;
        $user->email = $login;
        if (!is_null($password)) $user->password = password_hash($password, PASSWORD_BCRYPT);

        if ($user->group == 'gnome') {
            $isMasterGnome = $req->input('checkboxMG');
            if (!is_null($isMasterGnome)) $user->is_master_gnome = true;
            if (is_null($isMasterGnome)) $user->is_master_gnome = false;
        }

        if ($user->group == 'elf' && $userId == $req->user()->id) {
            $input = $req->input();
            $gems = [];
            $coeffsSum = 0;
            foreach ($input as $key => $value) {
                if ($key != '_token' && $key != 'name' && $key != 'login' && $key != 'password') {
                    $mas = explode('-', $key);
                    if ($mas[0] == 'gem') {
                        $gemtypeId = $mas[1];
                        $gems[] = ['gemtypeId' => $gemtypeId, 'coeff' => $value];
                        $coeffsSum += $value;
                    }
                }
            }
            if ($coeffsSum != 1) throw new Exception('Sum gemtypes coeffs must be =1');
            foreach ($gems as $gem) {
                $q = DB::table('elfs_gemtypes')
                    ->where('userId',  $userId)
                    ->where('gemtypeId', $gem['gemtypeId'])
                    ->get();

                if (count($q) > 0) {
                    $userGemtypes = DB::table('elfs_gemtypes')
                        ->where('userId', $userId)
                        ->where('gemtypeId', $gem['gemtypeId'])
                        ->update(['coeff' => $gem['coeff']]);
                } else {
                    DB::table('elfs_gemtypes')->insert([
                        ['userId' => $userId, 'gemtypeId' => $gem['gemtypeId'], 'coeff' => $gem['coeff']]
                    ]);
                }
            }
            explode('/', $req->path())[1];
        }
        $user->save();
        return redirect()->route('user', $userId)->with('success', 'User has been edited successfully');
    }
    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->active = false;
        $user->deleted_at = DB::raw('CURRENT_TIMESTAMP');
        $user->save();
        return redirect()->route('users')->with('success', 'User has been deleted successfully');
    }
}
