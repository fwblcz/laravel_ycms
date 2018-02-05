<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\AdminUser as User;
use App\Http\Requests\AdminUserRequest;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Repositories\UserRepository;

class AdminUserController extends Controller
{


    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        //parent::__construct();
        $this->userRepository = $userRepository;
        view()->share('roles', Role::all());    // 共享视图

    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::paginate(10);
        return view('admin.admin_user.index')->with("users",$users);
    }




    /**
     * User create.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.admin_user.create');
    }



    public function store(AdminUserRequest $request)
    {
        $input = $request->all();

        //print_r($input);

        $this->createUser($input);
        return redirect("admin/admin_user");

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
        $obj = User::findOrFail($id);

        return view('admin.admin_user.edit')->with("obj",$obj);
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
        $user = User::findOrFail($id);

        $this->updateUser($user,$request->except("id"));
        return redirect("admin/admin_user");
    }


    /**
     * @param  $id
     * @param  DeleteUserRequest $request
     * @return mixed
     */
    public function destroy($id)
    {
        $result = User::find($id)->delete();
        return response()->json(["code"=>200,"message"=>"删除成功","data"=>[]]);
        //var_dump($result);
//        if($result == true){
//
//        }
//        return response()->json(["code"=>400,"message"=>"删除失败","data"=>[]]);

    }



    protected function createUser(array $data)
    {


        $user =  User::create([
           // 'username' => $data['username'],
            'username' => $data["username"],
            'nickname' => "",
            'password' => bcrypt($data['password']),
            "register_time" => date("Y-m-d H:i:s",time()),
            "last_login_time"=> date("Y-m-d H:i:s",time()),

        ]);

        if(!empty($data["role_id"])){
            $user->roles()->detach();
            $user->attachRole($data["role_id"]);
        }

        return $user;
    }

    protected function updateUser(User $user ,array $data)
    {

        if($data["password"] == ""){
            unset($data["password"]);
        }else{
            $data["password"] = bcrypt($data['password']);
        }

        $user->update($data);

        if(isset($data["role_id"])){
            $user->roles()->detach();
            if($data["role_id"] != "0"){
                $user->attachRole($data["role_id"]);
            }
        }

        return $user;
    }




}
