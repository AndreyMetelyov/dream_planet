<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GemTypes;
use App\Coefficient;

use App\Http\Requests\GemTypesAddRequest;

class GemTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function showAllGems()
    {
        //$gemTypes = GemTypes::all();
        $gemTypes = GemTypes::getActiveGemTypes();
        $coeffs = Coefficient::all()->first();
        return view('gemTypes.showAllGemTypes', compact('gemTypes', 'coeffs'));
    }
    public function showOneGem($id)
    {
        $gemType = GemTypes::find($id);
        return view('gemTypes.showOneGemType', compact('gemType'));
    }
    public function addGemTypeForm()
    {
        return view('gemTypes.addGemType');
    }
    public function addGemTypeFormSubmit(GemTypesAddRequest $req)
    {
        $gem = new GemTypes();
        $gem->type = $req->input('type');
        $gem->save();
        return redirect()->route('gemTypes')->with('success', 'Gem Type has been added successfully');
        //$validation = $req->validate();
        //dd($req->input());
    }
    public function editGemTypeForm($id)
    {
        $gemType = GemTypes::find($id);
        return view('gemTypes.editGemType', compact('gemType'));
    }
    public function editGemTypeFormSubmit($id, GemTypesAddRequest $req)
    {
        $gem = GemTypes::find($id);
        $gem->type = $req->input('type');
        $gem->save();
        return redirect()->route('gemType', $id)->with('success', 'Gem Type has been edited successfully');
        //$validation = $req->validate();
        //dd($req->input());
    }
    public function deleteGemType($id)
    {
        $gem = GemTypes::find($id);
        $gem->active = false;
        $gem->save();
        return redirect()->route('gemTypes')->with('success', 'Gem Type has been deleted successfully');
    }
}
