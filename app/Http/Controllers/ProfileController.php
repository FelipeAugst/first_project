<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
   
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'limit'=> 'required|int',
            'orderby'=> 'required|string',
            'direction' => ['required', 'string',Rule::in(['asc','desc','ASC','DESC'])]
        ]);

        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()],422);

        }
        

        $limit= $request->input('limit');
		$orderby  = $request->input('orderby');
		$direction = $request->input('direction');

        $profiles = Profile::select("id","user_id","profile")
                            ->orderBy($orderby,$direction)
                            ->paginate($limit);

        return response()->json($profiles,200);

    }

   

   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id'=> 'int|required|exists:users,id',
            'profile' =>  ['string','required','max:255', Rule::in(['admin','user','ADMIN','USER']) ]
            ]);

            if($validator->fails()){
                return response()->json(['error'=>$validator->errors()],422);
    
            }


            $profile = new Profile($request->all());
            $profile->save();
            return response()->json(['created'=>$profile],201);




    }

    
    public function show(Request $request, $id)
    {
        $profiles = Profile::find($id);

        return response()->json($profiles,200);
    
         
    }

    

    
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(),[
            'user_id'=> 'int|required|exists:users,id',
            'profile' => 
                     ['string',
                     'required',
                     Rule::in(['admin','user','Admin','User'])
                    ]
            ]);

            if($validator->fails()){
                return response()->json(['error'=>$validator->errors()],422);
    
            }
            Profile::where('id',$id)->update($request->all());
            return response()->json(["updated"=>$id]);
        
    }

    
    public function destroy(Request $request, $id)
    {
       ;
        $profile = Profile::find($id);
      $profile->delete();

        return response()->json(['deleted'=>$profile],200);
    
    }
}
