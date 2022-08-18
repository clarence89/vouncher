<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role as Role1;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class AdminController extends Controller
{
    public function getUsers(Request $request){

        if ($request->type == 'users') {
            $user = User::doesntHave('permissions')->where('id','!=', $request->user()->id)->withCount('vouchers')->with('roles',function($query){
                $query->where('name','super-admin');
            })->orderBy('vouchers_count','DESC')->paginate(5);
            return  $user;
        }else if ($request->type == 'moderators'){
            $user = User::whereHas('permissions')->with('roles',function($query){
                $query->where('name','super-admin');
            })->paginate(5);
            return $user;
        }
    }
    public function admin_usermoderated(Request $request){
        $user = User::where('id', $request->user_id)->first();
        return $user->roles()->get();
    }
    public function create_group(Request $request){
       $validator =  Validator::make($request->all(),[
            'group_name' =>'required|unique:roles,name',
        ]);

        if ($validator->fails()) {
            return 'error';
        }
        Role1::create(['name'=>$request->group_name,'guard_name' =>'web']);
        return 'success';
    }
    public function generate_all_excel(Spreadsheet $spreadsheet)
    {
        $user = User::with('vouchers')->whereHas('vouchers')->get();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Vouchers');
        $sheet->mergeCells('A1:B2');
        $sheet->getStyle('A1')->applyFromArray(
            array(
                'fill' => array(
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => array('rgb' => '1D4F25')
                ), 'font' => array(
                    'color' => array('rgb' => 'FFFFFF')
                )
            )
        )->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle('A1')->getFont()->setName('Arial')->setSize(16);
        $sheet->setCellValue('A3', 'Username');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->setCellValue('B3', 'Voucher Code');
        $sheet->getStyle('B3')->getAlignment()->setHorizontal('center')->setVertical('center');
        $row = 4;
        foreach($user as $data){
            $sheet->setCellValue('A'.$row, $data->name);
            $value =  $sheet->getCell('A'.$row)->getValue();
            $width = mb_strwidth ($value);
            $sheet->getColumnDimension('A')->setWidth( $width+10);

          foreach ($data->vouchers as $voucher) {
            $sheet->setCellValue('B'.$row, $voucher->voucher_code);
            $value =  $sheet->getCell('B'.$row)->getValue();
            $width = mb_strwidth ($value);
            $sheet->getColumnDimension('B')->setWidth( $width+10);
            $row++;
        }
        $row++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('all users.xlsx'));
        return response()->download(storage_path('all users.xlsx'));
    }
    public function update_group(Request $request,Spreadsheet $spreadsheet)
    {
        $validator =  Validator::make($request->all(),[
            'group_name' =>'required|unique:roles,name',
        ]);

        if ($validator->fails()) {
            return 'error';
        }
        $role = Role1::where('id',$request->group_id)->first();
        $role->name = $request->group_name;
        $role->save();
        return 'success';
    }
    public function delete_group(Request $request,Spreadsheet $spreadsheet)
    {
        $role= Role::where('id',$request->group_id)->first();
        foreach($role->users()->get() as $value){
            $value->removeRole($role);
        }
        $role->delete();

        return 'success';
    }
}
