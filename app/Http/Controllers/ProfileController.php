<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;




class ProfileController extends Controller
{
   /**
    * @OA\Get(
    *   tags={"Profile"},
    *   path="/api/profile/index",
    *   summary="List all profiles.",
    *   security= {{"bearerAuth:{}"}},
    * 
    *   @OA\Parameter(
    *     name="limit",
    *     in="query",
    *     required=true,
    *     description= "limit of results returned",
    *     @OA\Schema(type="int")
    *   ),

    *  @OA\Parameter(
    *    name="orderby",
    *    in="query",
    *    required=true,
    *    description= "column to order data",
    *    @OA\Schema(type="string")
    *  ),
    * @OA\Parameter(
    *  name="direction",
    *  in= "query",
    *  required=true,
    *  description= "direction of data ordenation",
    *  @OA\Schema(type="string")
    *  ),
    * @OA\Response(response=200, 
     * description="OK", 
     *    @OA\JsonContent(
     *          allOf= { 
     *               @OA\Schema(type="array",
     *               @OA\Items(ref="#/components/schemas/profile")
     *                         )
     *                  }
     *                     )
     *                   )
     * ),
    *   @OA\Response(response=401, description="Unauthenticated"),
    *   @OA\Response(response=422, description="unprocessable content"),

    * )
    */
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

        $profiles = Profile::with('user:id,name')->select("id","user_id","profile")
                            ->orderBy($orderby,$direction)
                            ->paginate($limit);

        return response()->json($profiles,200);

    }

   

   /**
    * @OA\Post(
    *       tags={"Profile"},
    *       path="/api/profile/store",
    *       summary="Create a new profile",
    *       security= {{"bearerAuth:{}"}},
    *       @OA\RequestBody(
    *           required=true,
    *           @OA\JsonContent(
    *               allOf={
    *                       @OA\Schema(ref="#components/schemas/profile")
    *                     }
    *                         ),
    *                     ),
    *   
    *   @OA\Response(response=201, description="Created",
    *              @OA\JsonContent(
    *                     allOf={
    *                          @OA\Schema(ref="#components/schemas/profile")
    *                          }
    *                           )
    *              ),
    *   @OA\Response(response=401, description="Unauthenticated"),
    *   @OA\Response(response=422, description="Unprocessable Content")
    *      )
    */

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

    /**
     * @OA\Get(
     *   tags={"Profile"},
     *   path="/api/profile/{id}/show",
     *   security= {{"bearerAuth:{}"}},
     *   summary="Search and show a profile by id.",
     *   @OA\Parameter(
     *     name= "id",
     *     in="path",
     *     required=true
     *     
     *   ),
     *   @OA\Response(
     *     response=200, 
     *     description="OK",
     *      @OA\JsonContent(
     *              allOf={ 
     *                    @OA\Schema(ref="#components/schemas/profile") 
     *                   }
     *               )
     * 
     * ),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   
     * )
     */

    
    public function show(Request $request, $id)
    {
        $profiles = Profile::find($id);

        return response()->json($profiles,200);
    
         
    }

    
/**
 * @OA\Put(
 *   tags={"Profile"},
 *   path="/profile/{id}/update",
 *   summary="Update an existing profile",
 *    @OA\Parameter(
 *     name= "id",
 *     in="path",
 *     required=true
 *     
 *   ),
 *   @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *                 allOf={
 *                    @OA\Schema(ref="#components/schemas/profile")
 *                     }
 *         
 *                      )  
 *                  
 *     
 *        ),
 *   @OA\Response(response=200, description="Ok"),
 *   @OA\Response(response=401, description="Unauthenticated"),
 *   )
 */
    
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(),[
            'user_id'=> 'int|required|exists:users,id',
            'profile' => 
                     ['string',
                     'required',
                     Rule::in(['admin','user','ADMIN','USER'])
                    ]
            ]);

            if($validator->fails()){
                return response()->json(['error'=>$validator->errors()],422);
    
            }
            Profile::where('id',$id)->update($request->all());
            return response()->json(["updated"=>$id]);
        
    }


  /**
     * @OA\Delete(
     *   tags={"Profile"},
     *   path="/api/profile/{id}/destroy",
     *   security= {{"bearerAuth:{}"}},
     *   summary="Delete a profile by id.",
     *   @OA\Parameter(
     *     name= "id",
     *     in="path",
     *     required=true
     *     
     *   ),
     *   @OA\Response(
     *     response=200, 
     *     description="OK"
     *              ),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   
     * )
     */

    
    public function destroy(Request $request, $id)
    {
       ;
        $profile = Profile::find($id);
      $profile->delete();

        return response()->json(['deleted'=>$profile],200);
    
    }
}
