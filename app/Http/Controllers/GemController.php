<?php

namespace App\Http\Controllers;

use App\Coefficient;
use Illuminate\Http\Request;
use App\Models\Gem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\GemTypes;
use Exception;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\User;

class GemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function activeGems()
    {
        return DB::table('gems')->where('gems.active', true);
    }

    public function showAllGems(Request $req)
    {
        $query = $req->query();
        $q = [];
        $fillFields = [
            'earner' => null, 'approver' => null, 'owner' => null, 'gemType' => null,
            'status' => null, 'assign_date' => null, 'confirm_date' => null, 'assign' => null, 'confirm' => null
        ];
        foreach ($query as $key => $value) {
            $fillFields[$key] = $value;
            if ($value == 'All' || is_null($value) || $key == '_token') continue;
            if ($key == 'earner')   $q[] = ['ern.name', 'like', '%' . $value . '%'];
            if ($key == 'approver') $q[] = ['app.name', 'like', '%' . $value . '%'];
            if ($key == 'owner') $q[] = ['own.name', 'like', '%' . $value . '%'];
            if ($key == 'gemType') $q[] = ['gem_types.type', 'like', '%' . $value . '%'];
            if ($key == 'status') $q[] = ['gems.status', '=', $value];
            if ($key == 'assign_date' &&  $query['assign'] == 'Before') $q[] = ['assign_date', '<', $value];
            if ($key == 'assign_date' &&  $query['assign'] == 'After') $q[] = ['assign_date', '>', $value];
            if ($key == 'confirm_date' &&  $query['confirm'] == 'Before') $q[] = ['confirm_date', '<', $value];
            if ($key == 'confirm_date' &&  $query['confirm'] == 'After') $q[] =  ['confirm_date', '>', $value];
        }
        //dd($fillFields);
        $gems = DB::table('gems')
            ->leftJoin('users as ern', 'earner', '=', 'ern.id')
            ->leftJoin('users as app', 'approver', '=', 'app.id')
            ->leftJoin('users as own', 'owner', '=', 'own.id')
            ->leftJoin('gem_types', 'gemtype', '=', 'gem_types.id')
            ->select('gems.*', 'ern.name as ename', 'app.name as aname', 'own.name as oname', 'gem_types.type')
            ->where('gems.active', true)
            ->where($q)
            ->orderBy('extract_date', 'desc')
            ->get();


        $gemtypes = GemTypes::getActiveGemTypes();
        $status = ['assigned', 'confirmed', 'not_assigned'];
        $dates = ['Before', 'After'];
        $columnNames = [
            '#', 'gemtype', 'extract_date', 'assign_date', 'confirm_date',
            'earner', 'approver', 'owner', 'method', 'status'
        ];
        $user = new User();
        $elfs = $user->showUsersByGroup('elf');
        $gnomes = $user->showUsersByGroup('gnome');
        $mgnomes = $user->showUsersByGroup('gnome', true);
        return view('gems.showAllGems', compact('gems', 'columnNames', 'gemtypes', 'status', 'dates', 'elfs', 'gnomes', 'mgnomes', 'fillFields'));
    }
    public function addGemForm()
    {
        $gemTypes = GemTypes::getActiveGemTypes();
        return view('gems.addGem', compact('gemTypes'));
    }
    public function addGemFormSubmit(Request $req)
    {
        $user = Auth::user();
        if ($user->group != 'gnome') {
            throw new Exception('Only gnome can add new gem');
        }
        $countRows = (count($req->input()) - 1) / 2;

        for ($i = 0; $i < $countRows; $i++) {

            $countValue = $req->input('count' . $i);
            $typeName = $req->input('type' . $i);
            $type = GemTypes::getId($typeName);
            for ($j = 0; $j < $countValue; $j++) {
                $gem = new Gem();
                $gem->gemtype = $type->id;
                $gem->earner = $user->id;
                $gem->save();
            }
        }
        return redirect()->route('gems')->with('success', 'Gems has been added successfully');
    }
    public function deleteGem($id)
    {
        $gem = Gem::find($id);
        $gem->active = false;
        $gem->save();
        return redirect()->route('gems')->with('success', 'Gem has been deleted successfully');
    }

    public function assignGems(Request $req)
    {
        $obj = Gem::select(DB::raw('owner, COUNT(owner) as count'))->groupBy('owner')->get();
        $user = new User();

        $elfs = $user->showUsersByGroup('elf');
        //$gems = Gem::where('status', 'not_assigned')->get();
        $gems = DB::table('gems')
            ->leftJoin('users as ern', 'earner', '=', 'ern.id')
            ->leftJoin('users as own', 'owner', '=', 'own.id')
            ->leftJoin('gem_types', 'gemtype', '=', 'gem_types.id')
            ->select('gems.*', 'ern.name as ename', 'own.name as oname', 'gem_types.type')
            ->where('status', 'not_assigned')
            ->where('gems.active', true)
            ->get();

        $assoc = $this->initAssoc($obj, $elfs);

        foreach ($gems as $gem) {
            $mas = [];
            foreach ($elfs as $elf) {
                $mas[$gem->id . '-' . $elf->id] = $this->assignAlgorithm($gem, $elf, $assoc);
            }
            //  var_dump($mas);
            $max = array_search(max($mas), $mas);
            $output = explode("-", $max);

            $gemId = $output[0];
            $elfId = $output[1];

            foreach ($elfs as $elf) {
                if ($elf->id == $elfId) {
                    if (is_null($elf->newGems)) $elf->newGems = [$elfId];
                    else {
                        $qwe = $elf->newGems;
                        $qwe[] = $elfId;
                        $elf->newGems = $qwe;
                    }
                    break;
                }
            }
            $gem->owner = $elfId;
            $gem->oname = $elf->name;
            if (is_null($this->getCurrent($assoc, $elfId))) $assoc[] = ['count' => 1, 'owner' => $elfId];
            else for ($i = 0; $i < count($assoc); $i++) {
                if ($assoc[$i]['owner'] == $elfId) $assoc[$i]['count']++;
            }
            // var_dump($assoc);
        }
        $columnNames = [
            '#', 'gem id', 'gem type', 'extract date', 'earner', 'owner', 'new owner'
        ];

        $reqParams = $this->handleRequestParams($req);

        foreach ($reqParams as $r) {
            foreach ($gems as $gem) {
                if ($r['gemId'] == $gem->id) {
                    $gem->owner = $r['newOwnerId'];
                    $gem->oname = $r['newOwnerName'];
                    break;
                }
            }
        }
        $user = new User();
        $elfs = $user->showUsersByGroup('elf');

        return view('gems.assignGems', compact('gems', 'columnNames', 'req', 'elfs'));
    }
    public function assignAlgorithm($gem, $elf, $assoc)
    {
        $f1 = $this->fairDistribution($gem, $elf, $assoc);
        $f2 = $this->weeklyJoy($gem, $elf);
        $f3 = $this->elfsPreferences($gem, $elf);

        $coeffs = Coefficient::all()->first();
        $k1 = $coeffs->coeff_1;
        $k2 = $coeffs->coeff_2;
        $k3 = $coeffs->coeff_3;
        //  echo  'f1=' . $f1, '; f2=' . $f2, '; f3=' . $f3;
        return $k1 * $f1 + $k2 * $f2 + $k3 * $f3;
    }
    public function fairDistribution($gem, $elf, $assoc)
    {
        /*
        //кол-во драгоценностей у каждого эльфа
        $obj = Gem::select(DB::raw('COUNT(owner) as count'))->groupBy('owner')->get();
        $current = Gem::select(DB::raw('COUNT(owner) as count'))->where('owner', $elf->id)->get();
        
        $mas = [];
        foreach ($obj as $o) {
            $mas[] = $o->count;
        }
        */
        if (empty($assoc)) return 1;

        $mas = [];
        foreach ($assoc as $o) {
            $mas[] = $o['count'];
        }

        $current = $this->getCurrent($assoc, $elf->id);
        //$cur = $current;

        $min = min(array_values($mas));
        $max = max(array_values($mas));
        if ($min != $max) {
            $max -= $min;
            $current -= $min;
        } else return 1;
        $res = (1 - ($current * 1 / $max));
        //if ($gem->id == 3) dd($elf->id, $assoc, $min, $max, $current, $cur, $res);
        return $res;
    }
    public function weeklyJoy($gem, $elf)
    {
        if (is_null($elf->newGems)) return 1;
        return 0;
    }
    public function elfsPreferences($gem, $elf)
    {
        $pref = $this->getElfGemsPreferences($elf->id, $gem->gemtype);
        if (count($pref) > 0) return $pref[0]->coeff;
        else return 0;
    }
    public function getListOfNotAssignedGems()
    {
        /*
        $allGemsCount = [];
        foreach ($elfs as $elf) {
            if (!is_null($elf->received_gems)) $allGemsCount[] = $elf->received_gems;
        }
        $mas['Two' . '-' . 'Second'] = 11;
        $mas['Third' . '-' . 'three'] = 9;
        $mas['fourth' . '-' . 'four'] = 5;
        $min = min(array_values($allGemsCount));
        $max = max(array_values($allGemsCount));
        */
    }
    public function getElfGemsPreferences($elfId, $gemtypeId)
    {
        return DB::table('elfs_gemtypes')
            ->where('userId', $elfId)
            ->where('gemtypeId', $gemtypeId)
            ->get();
    }
    public function getCurrent($assoc, $id)
    {
        foreach ($assoc as $m) {
            if ($m['owner'] == $id) return $m['count'];
        }
        return null;
    }
    public function isElfExistInList($list, $elfId)
    {
        foreach ($list as $l) {
            if ($l['owner'] == $elfId) return true;
        }
        return false;
    }
    public function initAssoc($obj, $elfs)
    {
        $assoc = [];
        foreach ($obj as $o) {
            if (!is_null($o->owner)) {
                $assoc[] = ['count' => $o->count, 'owner' => $o->owner];
            }
        }
        foreach ($elfs as $elf) {
            if (!$this->isElfExistInList($assoc, $elf->id)) {
                $assoc[] = ['count' => 0, 'owner' => $elf->id];
            }
        }
        return $assoc;
    }
    public function handleRequestParams($req)
    {
        $query = $req->query();
        $q = [];
        $user = new User();

        foreach ($query as $key => $value) {
            if (is_null($value) || $key == '_token') continue;
            $user = $user->showUserByName($value);
            if (!is_null($user)) {
                $q[] = ['gemId' => $key, 'newOwnerName' => $value, 'newOwnerId' => $user->id];
            }
        }
        return $q;
    }
    public function assignGemsSubmit(Request $req)
    {
        $params = $req->input();
        $paramsMas = [];

        foreach ($params as $key => $value) {
            if ($key == '_token') continue;

            $output = explode("-", $key);
            $current = $output[1];

            $paramsMas[$current][$output[0]] = $value;
        }
        foreach ($paramsMas as $p) {
            $gem = Gem::find($p['gemId']);
            $approver = Auth::user();
            $user = new User();

            if (!is_null($approver)) $gem->approver = $approver->id;
            else throw new Exception('no loggined user');

            $gem->assign_date = DB::raw('CURRENT_TIMESTAMP');
            $gem->status = 'assigned';

            if (is_null($p['newOname'])) {
                $gem->method = 'auto';
                $u = $user->showUserByName($p['oname']);
                if (is_null($u->id)) throw new Exception('user with this name dont exist');
                $gem->owner = $u->id;
            } else {
                $gem->method = 'manual';
                $u = $user->showUserByName($p['newOname']);
                if (is_null($u->id)) throw new Exception('user with this name dont exist');
                $gem->owner = $u->id;
            }
            $gem->save();
        }
        return redirect()->route('gems')->with('success', 'Gems has been assigned successfully');
    }
    public function acceptGem($id)
    {
        $owner = Auth::user();
        $gem = Gem::find($id);

        if ($owner->id != $gem->owner) throw new Exception('Only owner of gem can accept it');
        $gem->status = 'confirmed';
        $gem->confirm_date = DB::raw('CURRENT_TIMESTAMP');
        $gem->save();
        return redirect()->route('gems')->with('success', 'Gem have been accepted successfully');
    }
}
