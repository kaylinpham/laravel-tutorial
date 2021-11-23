<?php

namespace App\Http\Controllers;

// USING ELOQUENT
use App\Models\Car;
use App\Models\Product;
use Illuminate\Http\Request;

use App\Rules\Uppercase;
use App\Http\Requests\CreateValidationRequest;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $cars = Car::all()->toArray();
        $cars = Car::all()->toJson();
        $cars = json_decode($cars);

        // $cars = Car::where('name', '=', 'Audi')->get();

        // $cars = Car::where('name', '=', 'Audi')->firstOrFail();

        // $cars = Car::chunk(2, function ($cars) {
        //     foreach ($cars as $car) {
        //         print_r($car);
        //     }
        // });

        // dd($cars);
        return $cars;
        // return view('cars.index');
    }

    /**
     * Show the form for creating a new resource.
     *x
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         // 'name' => 'required|unique:cars',
    //         'name' => new Uppercase,
    //         'founded' => 'required|integer|min:0|max:2021',
    //         'description' => 'required'
    //     ]); // If not valid, throw a ValidationException

    //     $car = Car::create([
    //         'name' => $request->input('name'),
    //         'founded' => $request->input('founded'),
    //         'description' => $request->input('description'),
    //     ]); // create() doesn't need save(), but make() does. So we can use make() instead but remember save().

    //     return redirect('/cars');
    // }

    public function store(Request $request)
    {
        // $request->validated(); // use with CreateValidationRequest

        // Methods we can use on $request
        // guessExtension()
        // getMimeType()
        // store()
        // asStore()
        // storePublicly()
        // move()
        // getClientOriginalName()
        // getClientMimeType()
        // guessClientExtension()
        // getSize()
        // getError()
        // isValid()
        // $test = $request->file('image')->guessExtension();

        $request->validate([
            // 'name' => 'required|unique:cars',
            'name' => new Uppercase,
            'founded' => 'required|integer|min:0|max:2021',
            'description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg|max:5048'
        ]); // If not valid, throw a ValidationException

        $newImageName = time() . '-' . $request->name . '.' . $request->image->extension();

        $request->image->move(public_path('images'), $newImageName);

        $car = Car::create([
            'name' => $request->input('name'),
            'founded' => $request->input('founded'),
            'description' => $request->input('description'),
            'image_path' => $newImageName
        ]); // create() doesn't need save(), but make() does. So we can use make() instead but remember save().

        return redirect('/cars');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $car = Car::find($id);

        $products = Product::find($id);

        dd($car->engines); // thanks to hasManyThrough()
        dd($car->productionDate); // thanks to hasOneThrough()

        return $car;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $car = Car::find($id)->first();
        dd($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateValidationRequest $request, $id)
    {
        $request->validated(); // use with CreateValidationRequest

        $car = Car::where('id', $id)->update([
            'name' => $request->input('name'),
            'founded' => $request->input('founded'),
            'description' => $request->input('description'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = Car::find($id)->first();

        $car->delete();

        return redirect('/cars');
    }
}
