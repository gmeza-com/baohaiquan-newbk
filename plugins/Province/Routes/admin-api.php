<?php
use Collective\Html\FormFacade as Form;

Route::group([
    'middleware' => ['api'],
    'prefix' => 'iadmin-api'
], function () {
    Route::get('/provinces.json', function (\Illuminate\Http\Request $request) {
        $provinces = \Plugins\Province\Models\Province::where('activated', 1)->get();

        if($request->exists('form')) {
            return Form::select('province', $provinces->mapWithKeys(function ($province){
                $name = $province->type . ' ' . $province->name;
                return [$name => $name];
            }), $request->get('province'), ['class' => 'form-control']);
        } else {
            return response()->json($provinces->toArray());
        }
    });

    Route::get('/districts.json', function (\Illuminate\Http\Request $request) {
        $districts = \Plugins\Province\Models\District::query();

        if($request->has('province'))
        {
            $province =\Plugins\Province\Models\Province::select('id')
                ->where('activated', true)
                ->where('name', 'like', getOriginName('province'))->first();

            if($province) {
                $districts = $districts->where('province_id', $province->id);
            }
        }
        $districts = $districts->orderBy('name')->get();

        if($request->exists('form')) {
            return Form::select('district', $districts->mapWithKeys(function ($district){
                $name = $district->type . ' ' . $district->name;
                return [$name => $name];
            }), $request->get('district'), ['class' => 'form-control']);
        } else {
            return response()->json($districts->toArray());
        }
    });

    Route::get('/wards.json', function (\Illuminate\Http\Request $request) {
        $wards = \Plugins\Province\Models\Ward::query();

        if($request->has('district'))
        {
            $wards = $wards->where('district_id', $request->get('district'));
        }
        if($request->has('district'))
        {
            $district =\Plugins\Province\Models\District::select('id')->where('name', 'like', getOriginName('district'))->first();
            if($district) {
                $wards = $wards->where('district_id', $district->id);
            }
        }

        $wards = $wards->orderBy('name')->get();

        if($request->exists('form')) {
            return Form::select('ward', $wards->mapWithKeys(function ($ward){
                $name = $ward->type . ' ' . $ward->name;
                return [$name => $name];
            }), $request->get('district'), ['class' => 'form-control']);
        } else {
            return response()->json($wards->toArray());
        }
    });
});

function getOriginName($attribute)
{
    $name = request()->get($attribute);
    $name = str_replace(['Thành Phố', 'Tỉnh', 'Quận', 'Huyện', 'Thị Xã'], '', $name);
    return '%' . trim($name) . '%';
}