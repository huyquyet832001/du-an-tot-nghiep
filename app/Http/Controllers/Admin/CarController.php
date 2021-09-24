<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarImage;
use App\Traits\DeleteModelTrait;
use App\Traits\StorageImageTrait;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use StorageImageTrait;
    use DeleteModelTrait;
    private $car;
    public function __construct(Car $car)
    {
        $this->car = $car;
    }
    public function index()
    {
        return $this->car->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $dataCartCreate = [
                'name' => $request->name,
                'description' => $request->description,
                'license_plates' => $request->license_plates,
                'number_seats' => $request->number_seats,
                'status' => $request->status,
                'color' => $request->color,
                'car_phone' => $request->car_phone,

            ];
            $dataUpload = $this->storageTraitUpload($request, 'image', 'cart');
            if (!empty($dataUpload)) {
                $dataCartCreate['image'] = $dataUpload['file_path'];
            }
            $car = $this->car->create($dataCartCreate);
            //insert data car_image
            if ($request->hasFile('image_path')) {
                foreach ($request->image_path as $fileItem) {
                    $dataCartImageDetail = $this->storageTraitUploadMutiple($fileItem, 'cart');
                    $image =   CarImage::create([
                        'car_id' => $car->id,
                        'image_path' => $dataCartImageDetail['file_path'],
                    ]);
                }
            }
            $car->policies()->attach($request->policy_id);
            DB::commit();
            return response()->json([
                'code' => 201,
                'data' => $car,
            ], status: 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("message" . $exception->getMessage() . 'Line:' . $exception->getLine());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
