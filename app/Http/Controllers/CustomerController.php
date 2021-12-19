<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CustomerSearch;
use App\Customer;
use App\Employee;
use App\Store;
use App\Menus_category;
use App\Menu;
use App\Sale;
use App\sales_menu;
use Carbon\Carbon;
use Validator;

use Log;


class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    //共通で使用する関数-------------------------

    //年齢から生年月日の範囲を取得
    function getBirthdayRange($age)
    {
        $start = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y') - $age - 1);
        $end = mktime(0, 0, 0, date('m'), date('d'), date('Y') - $age);
        return array(date('Y-m-d', $start), date('Y-m-d', $end));
    }

    //生年月日から年齢を取得
    function birthdayToAge($birthday){
        $now = date("Ymd");
        $birthday = str_replace("-", "", $birthday);//ハイフンを除去しています。
        return floor(($now - $birthday)/10000);
    }

    //直近の検索登録
    function insertLogData($id){

        //①先に同じ番号があったら消す
        CustomerSearch::where('customer_id', $id)->delete();

        //②件数が20件の場合は、最も古いレコードを消す
        $count = CustomerSearch::count();
        if($count == 20 ){
           CustomerSearch::orderBy('created_at','ASC')
           ->limit(1)
           ->delete(); 
        }

        //③インサートする
        $log = new CustomerSearch();
        $log->customer_id = $id;
        $log->save();
    }

    //直近の検索20件取得
    function selectLogData(){
        $search_data = DB::table('customer_searches')
        ->join('customers', 'customer_searches.customer_id', '=', 'customers.id')
        ->select('customers.id', 'customers.first_name', 'customers.last_name')
        ->orderBy('customer_searches.created_at','DESC')
        ->get();
        return $search_data;
    }


    //----------------------------------------
    
    public function index()
    {
        $search_data = $this->selectLogData();
        $employees = Employee::all();
        $stores = Store::all();
        $menus_categories = Menus_category::all();
        $menus = Menu::all();
        $sql = "SELECT a.id, a.first_name, a.last_name, a.first_name_kana, a.last_name_kana, a.birthday, a.gender, a.image, MAX(b.date) AS last_date, COUNT(DISTINCT(b.id)) AS sale_count FROM customers a LEFT JOIN sales b ON a.id = b.customer_id LEFT JOIN sales_menus c ON
                b.id = c.sale_id  GROUP BY a.id ORDER BY last_date DESC";
        $customers = DB::select($sql);
                //生年月日から年齢に変換したプロパティをオブジェクトに追加
        foreach($customers as $customer){
            if(!empty($customer->birthday)){
                $customer->age = $this->birthdayToAge($customer->birthday);
            }else{
                $customer->age = '';
            }
        }


        return view('customers.index')
        ->with('do_flg', 'index')
        ->with('search_data',$search_data)
        ->with('employees', $employees)
        ->with('stores', $stores)
        ->with('menus_categories', $menus_categories)
        ->with('menus', $menus)
        ->with('customers', $customers);
        
    }


    public function select(Request $request){

        //年齢から生年月日にする処理
        $age1 = $request->age1;
        $age2 = $request->age2;

        if(empty($age1) && !empty($age2)){
            $y_start = $this->getBirthdayRange($age2)[0];//1981年
            $y_end = date('Y-m-d');
        }elseif(!empty($age1) && empty($age2)){
            $y_start = '1850-01-01';
            $y_end = $this->getBirthdayRange($age1)[1];//1993年
        }elseif(empty($age1) && empty($age2)){
            $y_start = null;
            $y_end = null;
        }else{
            $y_start = $this->getBirthdayRange($age2)[0];//1981年
            $y_end = $this->getBirthdayRange($age1)[1];//1993年
        }

        //最終来店日処理
        if(empty($request->last_sale_before) && !empty($request->last_sale_after)){
            $last_sale_before = date("Y-m-d",strtotime("-60 year"));
            $last_sale_after = $request->last_sale_after;
        }elseif(!empty($request->last_sale_before) && empty($request->last_sale_after)){
            $last_sale_before = $request->last_sale_before;
            $last_sale_after = date("Y-m-d");
        }elseif(!empty($request->last_sale_before) && !empty($request->last_sale_after)){
            $last_sale_before = $request->last_sale_before;
            $last_sale_after = $request->last_sale_after;
        }else{
            $last_sale_before = null;
            $last_sale_after = null;
        }

        //来店回数処理
        if(empty($request->sale_count_before) && !empty($request->sale_count_after)){
            $sale_count_before = 0;
            $sale_count_after = $request->sale_count_after;
        }elseif(!empty($request->sale_count_before) && empty($request->sale_count_after)){
            $sale_count_before = $request->sale_count_before;
            $sale_count_after = 1000000;
        }elseif(!empty($request->sale_count_before) && !empty($request->sale_count_after)){
            $sale_count_before = $request->sale_count_before;
            $sale_count_after = $request->sale_count_after;
        }else{
            $sale_count_before = null;
            $sale_count_after =null;
        }

        //sql文生成
        $count = 0;
        $bind_arr = array();

        if(!(empty($request->name) && empty($y_start) && empty($y_end) && empty($request->gender) && empty($request->menu_id) && empty($request->employee_id) && empty($request->gender) && empty($last_sale_before) && empty($last_sale_after) && empty($request->phone_number) && empty($request->email) && empty($request->memo) && empty($sale_count_before) && empty($sale_count_after))){ 
            $sql = "SELECT a.id, a.first_name, a.last_name, a.first_name_kana, a.last_name_kana, a.birthday, a.gender, a.image, MAX(b.date) AS last_date, COUNT(DISTINCT(b.id)) AS sale_count FROM customers a LEFT JOIN sales b ON a.id = b.customer_id LEFT JOIN sales_menus c ON
            b.id = c.sale_id WHERE 1 = 1";
            if(!empty($request->name)){
                // if($count == 0){
                //     $sql .= " WHERE ( CONCAT(a.first_name, a.last_name) like CONCAT('%', :name, '%') OR CONCAT(a.first_name_kana, a.last_name_kana) like CONCAT('%', :name02, '%') OR a.nickname like CONCAT('%', :name03, '%') )";
                // }else{
                    $sql .= " AND ( CONCAT(a.first_name, a.last_name) like CONCAT('%', :name, '%') OR CONCAT(a.first_name_kana, a.last_name_kana) like CONCAT('%', :name02, '%') OR a.nickname like CONCAT('%', :name03, '%') )";
                // }
                $bind_arr['name'] = $request->name;
                $bind_arr['name02'] = $request->name;
                $bind_arr['name03'] = $request->name;
                // $count++;
            }
            if(!(empty($y_start) && empty($y_end))){
                // if($count == 0){
                    // $sql .= " WHERE a.birthday BETWEEN :y_start AND :y_end";
                // }else{
                    $sql .= " AND a.birthday BETWEEN :y_start AND :y_end";
                // }
                $bind_arr['y_start'] = $y_start;
                $bind_arr['y_end'] = $y_end;
                // $count++;
            }
            if(!empty($request->menu_id)){
                $c = count($request->menu_id);
                // if($count == 0){
                //     if($c == 1){
                //         $sql .= " WHERE c.menu_id = :menu_id";
                //         $bind_arr['menu_id'] = $request->menu_id[0];
                //     }else{
                //         for($i=0; $i<$c; $i++){
                //             if($i==0){
                //                 $sql .= " WHERE (c.menu_id = :menu_id".$i;
                //             }elseif($i == $c - 1){
                //                 $sql .= " OR c.menu_id = :menu_id".$i.")";
                //             }else{
                //                 $sql .= " OR c.menu_id = :menu_id".$i;
                //             }
                //             $key = 'menu_id'.$i;
                //             $bind_arr[$key] = $request->menu_id[$i];
                //         }
                //     }
                // }else{
                    if($c == 1){
                        $sql .= " AND c.menu_id = :menu_id";
                        $bind_arr['menu_id'] = $request->menu_id[0];
                    }else{
                        for($i=0; $i<$c; $i++){
                            if($i==0){
                                $sql .= " AND (c.menu_id = :menu_id".$i;
                            }elseif($i == $c - 1){
                                $sql .= " OR c.menu_id = :menu_id".$i.")";
                            }else{
                                $sql .= " OR c.menu_id = :menu_id".$i;
                            }
                            $key = 'menu_id'.$i;
                            $bind_arr[$key] = $request->menu_id[$i];
                        }
                    }
                // }
                // $count++;
            }
            if(!empty($request->employee_id)){
                $c = count($request->employee_id);
                // if($count == 0){
                //     if($c == 1){
                //         $sql .= " WHERE b.employee_id = :employee_id";
                //         $bind_arr['employee_id'] = $request->employee_id[0];
                //     }else{
                //         for($i=0; $i<$c; $i++){
                //             if($i==0){
                //                 $sql .= " WHERE (b.employee_id = :employee_id".$i;
                //             }elseif($i == $c - 1){
                //                 $sql .= " OR b.employee_id = :employee_id".$i.")";
                //             }else{
                //                 $sql .= " OR b.employee_id = :employee_id".$i;
                //             }
                //             $key = 'employee_id'.$i;
                //             $bind_arr[$key] = $request->employee_id[$i];
                //         }
                //     }
                // }else{
                    if($c == 1){
                        $sql .= " AND b.employee_id = :employee_id";
                        $bind_arr['employee_id'] = $request->employee_id[0];
                    }else{
                        for($i=0; $i<$c; $i++){
                            if($i==0){
                                $sql .= " AND (b.employee_id = :employee_id".$i;
                            }elseif($i == $c - 1){
                                $sql .= " OR b.employee_id = :employee_id".$i.")";
                            }else{
                                $sql .= " OR b.employee_id = :employee_id".$i;
                            }
                            $key = 'employee_id'.$i;
                            $bind_arr[$key] = $request->employee_id[$i];
                        }
                    }
                // }
                // $count++;
            }
            if(!empty($request->gender)){
                $c = count($request->gender);
                // if($count == 0){
                //     if($c == 1){
                //         $sql .= " WHERE a.gender = :gender";
                //         $bind_arr['gender'] = $request->gender[0];
                //     }else{
                //         for($i=0; $i<$c; $i++){
                //             if($i==0){
                //                 $sql .= " WHERE (a.gender = :gender".$i;
                //             }elseif($i == $c - 1){
                //                 $sql .= " OR a.gender = :gender".$i.")";
                //             }else{
                //                 $sql .= " OR a.gender = :gender".$i;
                //             }
                //             $key = 'gender'.$i;
                //             $bind_arr[$key] = $request->gender[$i];
                //         }
                //     }
                // }else{
                    if($c == 1){
                        $sql .= " AND a.gender = :gender";
                        $bind_arr['gender'] = $request->gender[0];
                    }else{
                        for($i=0; $i<$c; $i++){
                            if($i==0){
                                $sql .= " AND (a.gender = :gender".$i;
                            }elseif($i == $c - 1){
                                $sql .= " OR a.gender = :gender".$i.")";
                            }else{
                                $sql .= " OR a.gender = :gender".$i;
                            }
                            $key = 'gender'.$i;
                            $bind_arr[$key] = $request->gender[$i];
                        }
                    }
                // }
                // $count++;
            }
            if(!(empty($last_sale_before) && empty($last_sale_after))){
                // if($count == 0){
                //     $sql .= " WHERE b.date BETWEEN :last_sale_before AND :last_sale_after";
                // }else{
                    $sql .= " AND b.date BETWEEN :last_sale_before AND :last_sale_after";
                // }
                $bind_arr['last_sale_before'] = $last_sale_before;
                $bind_arr['last_sale_after'] = $last_sale_after;
                // $count++;
            }
            if(!empty($request->phone_number)){
                // if($count == 0){
                //     $sql .= " WHERE (REPLACE(a.phone_number,'-','') like CONCAT('%', :phone_number, '%') OR REPLACE(a.cell_phone_number,'-','') like CONCAT('%', :phone_number02, '%'))";
                // }else{
                    $sql .= " AND (REPLACE(a.phone_number,'-','') like CONCAT('%', :phone_number, '%') OR REPLACE(a.phone_number,'-','') like CONCAT('%', :phone_number02, '%'))";
                // }
                $bind_arr['phone_number'] = $request->phone_number;
                $bind_arr['phone_number02'] = $request->phone_number;
                // $count++;
            }
            if(!empty($request->email)){
                // if($count == 0){
                //     $sql .= " WHERE a.email like CONCAT('%', :email, '%')";
                // }else{
                    $sql .= " AND a.email like CONCAT('%', :email, '%')";
                // }
                $bind_arr['email'] = $request->email;
                // $count++;
            }
            if(!empty($request->memo)){
                // if($count == 0){
                //     $sql .= " WHERE (a.memo like CONCAT('%', :memo, '%') OR b.memo like CONCAT('%', :memo02, '%'))";
                // }else{
                    $sql .= " AND (a.memo like CONCAT('%', :memo, '%') OR b.memo like CONCAT('%', :memo02, '%'))";
                // }
                $bind_arr['memo'] = $request->memo;
                $bind_arr['memo02'] = $request->memo;
                // $count++;
            }
            
            $sql .= " GROUP BY a.id";

            if(!(empty($sale_count_before) && empty($sale_count_after))){
                $sql .= " HAVING COUNT(DISTINCT(b.id)) BETWEEN :sale_count_before AND :sale_count_after";
                $bind_arr['sale_count_before'] = $sale_count_before;
                $bind_arr['sale_count_after'] = $sale_count_after;
            }

            $sql .= " ORDER BY last_date DESC";
        }else{
            $sql = "SELECT a.id, a.first_name, a.last_name, a.first_name_kana, a.last_name_kana, a.birthday, a.gender, a.image, MAX(b.date) AS last_date, COUNT(DISTINCT(b.id)) AS sale_count FROM customers a LEFT JOIN sales b ON a.id = b.customer_id LEFT JOIN sales_menus c ON
            b.id = c.sale_id  GROUP BY a.id ORDER BY last_date DESC";
        }

        if(isset($sql)){

            $customers = DB::select($sql,$bind_arr);
                //生年月日から年齢に変換したプロパティをオブジェクトに追加
                foreach($customers as $customer){
                    if(!empty($customer->birthday)){
                        $customer->age = $this->birthdayToAge($customer->birthday);
                    }else{
                        $customer->age = '';
                    }
                }
        }else{
            $customers = 1;
        }
        
        $search_data = $this->selectLogData();
        $employees = Employee::all();
        $stores = Store::all();
        $menus_categories = Menus_category::all();
        $menus = Menu::all();
        return view('customers.index')
                ->with('do_flg', 'select')
                ->with('old', $request)
                ->with('customers',$customers)
                ->with('search_data',$search_data)
                ->with('employees', $employees)
                ->with('stores', $stores)
                ->with('menus_categories', $menus_categories)
                ->with('menus', $menus);
    }

    public function customerToSale(int $id){

        $this->insertLogData($id);
        return view('kari')->with('id',$id);
    }

    public function detail(int $id){
        $this->insertLogData($id);
        $customer = Customer::find($id);
        $sql = "SELECT a.id, a.date, a.employee_id, GROUP_CONCAT(c.name) AS menu, GROUP_CONCAT(c.price) AS menu_price, CONCAT(d.first_name,d.last_name) AS employee, a.memo, a.image1, a.image2, a.image3, a.image4
        FROM sales a
        LEFT JOIN sales_menus b ON a.id = b.sale_id
        LEFT JOIN menus c ON b.menu_id = c.id
        LEFT JOIN employees d ON a.employee_id = d.id
        WHERE a.customer_id = :id
        GROUP BY a.id ORDER BY a.date DESC";
        $sales = DB::select($sql,['id'=>$id]);
        $employees = Employee::all();
        $menus_categories = Menus_category::all();
        $menus = Menu::all();
        return view('customers.detail')
        ->with('customer',$customer)
        ->with('sales',$sales)
        ->with('employees',$employees)
        ->with('menus_categories',$menus_categories)
        ->with('menus',$menus);
    }

    /*============================
        顧客情報
    ============================*/

    public function create(Request $request){
        
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'first_name_kana' => 'required',
            'last_name_kana' => 'required',
            'gender' => 'required',
        ];

        $message = [
            'first_name.required' => '※「姓」は必須です。',
            'last_name.required' => '※「名」は必須です。',
            'first_name_kana.required' => '※「姓（カナ）」は必須です。',
            'last_name_kana.required' => '※「名（カナ）」は必須です。',
            'gender.required' => '※「性別」は必須です。',
        ];

        $validate = Validator::make($request->all(), $rules, $message);
        
    
        if ($validate->fails()) {
            return $validate->getMessageBag();

        } else {

            if ($file = $request->file('image')) {
                $fileName = time() . $file->getClientOriginalName();
                $target_path = public_path('uploads/');
                $file->move($target_path, $fileName);
            } else {
                $fileName = NULL;
            }

            $birthday = null;
            if(!empty($request->birthday_year) && !empty($request->birthday_month) && !empty($request->birthday_day)){
                $birthday = $request->birthday_year.'-'.$request->birthday_month.'-'.$request->birthday_day;
            }

            $phone_number = null;
            if(!empty($request->phone_number1) && !empty($request->phone_number2) && !empty($request->phone_number3)){
                $phone_number = $request->phone_number1.'-'.$request->phone_number2.'-'.$request->phone_number3;
            }

            $cell_phone_number = null;
            if(!empty($request->cell_phone_number1) && !empty($request->cell_phone_number2) && !empty($request->cell_phone_number3)){
                $cell_phone_number = $request->cell_phone_number1.'-'.$request->cell_phone_number2.'-'.$request->cell_phone_number3;
            }

            $customer = new Customer;
            $customer->first_name = $request->first_name;
            $customer->last_name = $request->last_name;
            $customer->first_name_kana = $request->first_name_kana;
            $customer->last_name_kana = $request->last_name_kana;
            $customer->nickname = $request->nickname;
            $customer->birthday = $birthday;
            $customer->postcode = $request->postcode;
            $customer->prefecture = $request->prefecture;
            $customer->city = $request->city;
            $customer->block = $request->block;
            $customer->phone_number = $phone_number;
            $customer->cell_phone_number = $cell_phone_number;
            $customer->email = $request->email;
            $customer->gender = $request->gender;
            $customer->image = $fileName;
            $customer->memo = $request->memo;
            $customer->save();
            return 1;
        }
    }

    public function update(Request $request){
        $customer = new Customer;

        if(!empty($request->postcode1) && !empty($request->postcode1)){
            $postcode = $request->postcode1.$request->postcode2;
        }else{
            $postcode = NULL;
        }
        if(!empty($request->birthday_year) && !empty($request->birthday_month) && !empty($request->birthday_day) ){
            $birthday = $request->birthday_year.'-'.$request->birthday_month.'-'.$request->birthday_day;
        }else{
            $birthday = NULL;
        }

        if(!(empty($request->phone_number1) && empty($request->phone_number2) && empty($request->phone_number3))){
            $phone_number = $request->phone_number1.'-'.$request->phone_number2.'-'.$request->phone_number3;
        }else{
            $phone_number = NULL;
        }

        if(!(empty($request->cell_phone_number1) && empty($request->cell_phone_number2) && empty($request->cell_phone_number3))){
            $cell_phone_number = $request->cell_phone_number1.'-'.$request->cell_phone_number2.'-'.$request->cell_phone_number3;
        }else{
            $cell_phone_number = NULL;
        }
        $update_arr = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'first_name_kana' => $request->first_name_kana,
            'last_name_kana' => $request->last_name_kana,
            'nickname' => $request->nickname,
            'birthday' => $birthday,
            'postcode' => $postcode,
            'prefecture' => $request->prefecture,
            'city' => $request->city,
            'block' => $request->block,
            'phone_number' => $phone_number,
            'cell_phone_number' => $cell_phone_number,
            'email' => $request->email,
            'gender' => $request->gender,
            'memo' => $request->memo,
        ];

        if ($file = $request->file('image')) {
            $fileName = time() . $file->getClientOriginalName();
            $target_path = public_path('uploads/');
            $file->move($target_path, $fileName);
            $update_arr['image'] = $fileName; 
        } 

        
        $customer->where('id', $request->id)->update($update_arr);

        return redirect()->route('customers.detail', ['id' => $request->id ]);
    }
    
    public function delete(Request $request){
        Customer::where('id', $request->id)->delete();
        return redirect()->route('customers');
    }
    

    /*============================
        売上情報
    ============================*/

    public function saleDisplay(Request $request){
        $sql = "SELECT a.id, a.date, a.employee_id,GROUP_CONCAT(c.name) AS menu, GROUP_CONCAT(c.price) AS menu_price, CONCAT(d.first_name,d.last_name) AS employee, a.memo, a.image1, a.image2, a.image3, a.image4
        FROM sales a
        LEFT JOIN sales_menus b ON a.id = b.sale_id
        LEFT JOIN menus c ON b.menu_id = c.id
        LEFT JOIN employees d ON a.employee_id = d.id
        WHERE a.id = :id
        GROUP BY a.id ORDER BY a.date DESC";
        $sales = DB::select($sql,['id'=>$request->id]);
        return $sales;
    }

    public function saleCreate(Request $request){
        $rules = [
            'date' => 'required',
            'employee_id' => 'required',
        ];
        
        $message = [
            'date.required' => '*売上日は必須です',
            'employee_id.required' => '*担当者は必須です',
        ];
        
        $validate = Validator::make($request->all(), $rules, $message);
        
        if ($validate->fails()) {
            return $validate->getMessageBag();
        } else {
            Log::debug('bbb');
            //以下ファイル名を取得し、uploadsフォルダに格納
            if ($file = $request->file('image1')) {
                $fileName1 = time() . $file->getClientOriginalName();
                $target_path = public_path('uploads/');
                $file->move($target_path, $fileName1);
            } else {
                $fileName1 = null;
            }
            if ($file = $request->file('image2')) {
                $fileName2 = time() . $file->getClientOriginalName();
                $target_path = public_path('uploads/');
                $file->move($target_path, $fileName2);
            } else {
                $fileName2 = null;
            }
            if ($file = $request->file('image3')) {
                $fileName3 = time() . $file->getClientOriginalName();
                $target_path = public_path('uploads/');
                $file->move($target_path, $fileName3);
            } else {
                $fileName3 = null;
            }
            if ($file = $request->file('image4')) {
                $fileName4 = time() . $file->getClientOriginalName();
                $target_path = public_path('uploads/');
                $file->move($target_path, $fileName4);
            } else {
                $fileName4 = null;
            }
            Log::debug('ccc');
            $sale = new Sale;
            Log::debug('ddd');
            $sale->date = $request->date;
            $sale->employee_id = $request->employee_id;
            $sale->customer_id = $request->customer_id;
            $sale->memo = $request->memo;
            $sale->image1 = $fileName1;
            $sale->image2 = $fileName2;
            $sale->image3 = $fileName3;
            $sale->image4 = $fileName4;
            $sale->updated_on = Carbon::now();
            $sale->save();
            $sale_id = $sale->id;
            
            if(!empty($request->menu_array)){
                $menu_arr = explode(',' ,$request->menu_array);
                foreach($menu_arr as $menu){
                    $sale_menu = new Sales_menu;
                    $sale_menu->sale_id = $sale_id;
                    $sale_menu->menu_id = $menu;
                    $sale_menu->timestamps = false;
                    $sale_menu->save();
                }
            }

            return 1;
        }
    }

    public function saleUpdate(Request $request){
        $rules = [
            'date' => 'required',
            'employee_id' => 'required',
        ];

        $message = [
            'date.required' => '*売上日は必須です',
            'employee_id.required' => '*担当者は必須です',
        ];

        $validate = Validator::make($request->all(), $rules, $message);

        if ($validate->fails()) {
            return $validate->getMessageBag();
        } else {

            if ($file = $request->file('image1')) {
                $fileName1 = time() . $file->getClientOriginalName();
                $target_path = public_path('uploads/');
                $file->move($target_path, $fileName1);
            } else {
                $fileName1 = null;
            }
            if ($file = $request->file('image2')) {
                $fileName2 = time() . $file->getClientOriginalName();
                $target_path = public_path('uploads/');
                $file->move($target_path, $fileName2);
            } else {
                $fileName2 = null;
            }
            if ($file = $request->file('image3')) {
                $fileName3 = time() . $file->getClientOriginalName();
                $target_path = public_path('uploads/');
                $file->move($target_path, $fileName3);
            } else {
                $fileName3 = null;
            }
            if ($file = $request->file('image4')) {
                $fileName4 = time() . $file->getClientOriginalName();
                $target_path = public_path('uploads/');
                $file->move($target_path, $fileName4);
            } else {
                $fileName4 = null;
            }
            
            $sale = Sale::find($request->sale_id);
            $sale->date = $request->date;
            $sale->employee_id = $request->employee_id;
            $sale->customer_id = $request->customer_id;
            $sale->memo = $request->memo;
            if(!empty($fileName1)){
                $sale->image1 = $fileName1;
            }
            if(!empty($fileName2)){
                $sale->image2 = $fileName2;
            }
            if(!empty($fileName3)){
                $sale->image3 = $fileName3;
            }
            if(!empty($fileName4)){
                $sale->image4 = $fileName4;
            }
            $sale->updated_on = Carbon::now();
            $sale->save();

            Sales_menu::where('sale_id', $request->sale_id)->delete();
            if(!empty($request->menu_array)){
                $menu_arr = explode(',' ,$request->menu_array);
                foreach($menu_arr as $menu){
                    $sale_menu = new Sales_menu;
                    $sale_menu->sale_id = $request->sale_id;
                    $sale_menu->menu_id = $menu;
                    $sale_menu->timestamps = false;
                    $sale_menu->save();
                }
            }

            return 1;
        }
    
    }

    public function saleDelete(Request $request){
        Sale::where('id', $request->id)->delete();
        return redirect()->route('customers.detail',['id' => $request->customer_id]);
    }


    
}
