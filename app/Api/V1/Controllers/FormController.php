<?php 

namespace App\Api\V1\Controllers;
use JWTAuth;
use App\Book;
use App\Http\Requests;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\FormModel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FormController extends Controller {

	 
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
			return FormModel::where("SOLD",0)->get()->toArray();
			 
	}

	 
	 

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $pin,$serial,$name,$phone,$bank)
	{
		/*$this->validate($request, [
             
            'serial'=>'required',
            'pin'=>'required',
            'bank'=>'required',
            
            'phone' => 'required',
            'name' => 'required',
              
        ]);*/
        if(!empty($pin)&&!empty($serial)&&!empty($name)&&!empty($phone)&&!empty($bank)){
			 
		    $bankId=$this->currentUser()->name;
          $form= FormModel::where("serial",$serial)->where("PIN",$pin)->update(array("SOLD"=>1,"SOLD_BY"=>$bankId,"NAME"=>$name,"PHONE"=>$phone));

             
        if(!$form){
            throw new NotFoundHttpException;
        }
         
        elseif($form){
            return response()->json(['status'=>'success','data'=>'Transaction complete successfully'],200);
      
        }
        else{
           // return $this->response->error('Transaction couldn not complete successfully');
            return response()->json(['status'=>'error','data'=>'Transaction could not complete successfully'], 502);
        }
    }
    else{
    	 return response()->json(['status'=>'validationErr','data'=>'All fields required'], 402);
      
    }
             
	}

	private function currentUser() {
        return JWTAuth::parseToken()->authenticate();
    }
     public function generateAccounts() {
        ini_set('max_execution_time', 3000); //300 seconds = 5 minutes
         $form=  Models\ExcelForm::where('id','!=','0')->get();
         foreach($form as $users=>$row){
             
             $student=$row->username;
             $password=  strtoupper(str_random(9));
             $hashedPassword = bcrypt($password);
             
             $FormTable=new FormModel();
              $FormTable->serial=$row->serial;
            $FormTable->PIN=$row->pin;
           $FormTable->password=bcrypt($row->pin);
           $FormTable->SOLD_BY=$row->bank;
           $FormTable->FORM_TYPE=$row->type;
              $FormTable->save();
         } 
         
    }
}
