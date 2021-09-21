<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\StorageImageTrait;
use Illuminate\Http\Request;
use App\Traits\DeleteModelTrait;
use Illuminate\Support\Facades\Log;


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
        $dataInssert =   $this->user->fill($request->all());
        $dataInssert->password = bcrypt($request->password);
        $dataImageUser = $this->storageTraitUpload($request, 'image', 'users');
        if (!empty($dataImageUser)) {
            $dataInssert['image'] =  $dataImageUser['file_path'];
        }
        $dataInssert =  $this->user->save();
        return response()->json([
            'code' => 201,
            'data' => $dataInssert,
        ], status: 201);
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
            'code' => 201,
            'data' => $user,
        ], status: 201);
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
        $dataUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => bcrypt($request->password),
        ];
        $dataImageUser = $this->storageTraitUpload($request, 'image', 'slider');
        if (!empty($dataImageUser)) {
            $dataUpdate['image'] = $dataImageUser['file_path'];
        }
        $UserUpdate =  $this->user->find($id)->update($dataUpdate);
        return response()->json([
            'code' => 201,
            'data' => $UserUpdate,
        ], status: 201);
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
