<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\StorageImageTrait;
use Illuminate\Http\Request;
use App\Traits\DeleteModelTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use StorageImageTrait;
    use DeleteModelTrait;
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function index()
    {
        $users = $this->user->all();
        return response()->json([
            'data' => $users,
            'code' => '200',
        ], status: 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $dataImageUser = $this->storageTraitUpload($request, 'image', 'users');
            $dataInssert = $this->user->create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => bcrypt($request->password),
                'image' =>  $dataImageUser['file_path'],
            ]);
            $dataInssert->roles()->attach($request->role_id);
            DB::commit();
            return response()->json([
                'code' => 201,
                'data' => $dataInssert,
            ], status: 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Message :" . $exception->getMessage() . '---Line:' . $exception->getLine());
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
        $user =  $this->user->find($id);
        return response()->json([
            'code' => 200,
            'data' => $user,
        ], status: 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        try {
            DB::beginTransaction();
            $dataImageUser = $this->storageTraitUpload($request, 'image', 'users');
            $dataUpdate = $this->user->find($id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => bcrypt($request->password),
                'image' =>  $dataImageUser['file_path'],
            ]);
            $dataUpdate = $this->user->find($id);
            $dataUpdate->roles()->sync($request->role_id);
            DB::commit();
            return response()->json([
                'code' => 201,
                'data' => $dataUpdate,
            ], status: 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("Message :" . $exception->getMessage() . '---Line:' . $exception->getLine());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->deleteModelTrait($id, $this->user);
    }
}
