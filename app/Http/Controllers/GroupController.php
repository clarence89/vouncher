<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GroupController extends Controller
{
    public function get_group(Request $request)
    {
        $user = $request->user();
        if ($request->user()->hasRole('super-admin')) {
            $roles = Role::where('name', '!=', 'super-admin')->paginate(5);
            return $roles;
        } else {
            $roles = $user->roles()->paginate(5);
        }
        return  $roles;
    }
    public function get_groupuser(Request $request)
    {
        $users = Role::where('name', $request->group_name)->first();
        if ($request->user()->hasRole('super-admin')) {
            return  $users->users()->withCount('roles')->withCount('permissions')->withCount('vouchers')->orderBy('permissions_count', 'DESC')->orderBy('vouchers_count', 'DESC')->paginate(5);
        } else {
            return  $users->users()->withCount('vouchers')->doesntHave('permissions')->orderBy('vouchers_count', 'DESC')->paginate(5);
        }
    }
    public function remove_user_group(Request $request)
    {
        $users = User::where('id', $request->user_id)->first();
        $users->removeRole($request->group_name);
        return  $users;
    }
    public function get_user(Request $request)
    {
        $users = User::doesntHave('permissions')->withCount('roles')->has('roles', 0)->get();
        return  $users;
    }
    public function get_user_moderator(Request $request)
    {
        if ($request->user()->hasRole('super-admin')) {
            $group_name = $request->group_name;
            $users = User::whereHas('permissions')->withCount('roles')->withCount('permissions')->whereDoesntHave('roles', function ($query) use ($group_name) {
                $query->where('name', $group_name);
            })->get();
            return  $users;
        }
        return "Error";
    }
    public function patch_users(Request $request)
    {
        $users = User::whereIn('id', $request->users)->get();
        foreach ($users as  $user) {
            $role = Role::where('name',$request->group_name)->first();
            $user->assignRole($role);
        }
        return  $users;
    }
    public function get_usercodes(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        return $user->vouchers;
    }
    public function generate_group_excel(Request $request, Spreadsheet $spreadsheet)
    {
        $group_name = $request->group_name;
        $user = User::whereHas('roles', function ($query) use ($group_name) {
            $query->where('name', $group_name);
        })->with('vouchers')->whereHas('vouchers')->get();


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
        foreach ($user as $data) {
            $sheet->setCellValue('A' . $row, $data->name);
            $value =  $sheet->getCell('A' . $row)->getValue();
            $width = mb_strwidth($value);
            $sheet->getColumnDimension('A')->setWidth($width + 10);

            foreach ($data->vouchers as $voucher) {
                $sheet->setCellValue('B' . $row, $voucher->voucher_code);
                $value =  $sheet->getCell('B' . $row)->getValue();
                $width = mb_strwidth($value);
                $sheet->getColumnDimension('B')->setWidth($width + 10);
                $row++;
            }
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path($group_name . '.xlsx'));
        return response()->download(storage_path($group_name . '.xlsx'));
    }
}
