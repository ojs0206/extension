<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function configSuccessArray($msg = "", $param = "")
    {
        return ["type" => "ok", "msg" => $msg, "param" => $param];
    }

    protected function configFailArray($msg = "", $param = "")
    {
        return ["type" => "fail", "msg" => $msg, "param" => $param];
    }

    public function getAdminId()
    {
        return session()->get(SESS_UID);
    }

    public function getDataTableParams(Request $request)
    {
        $search_key = $request->get("search")["value"];
        $search_column = $request->get("aoSearchCols");
        $temp_sort = $request->get("order");
        $sort_num = $temp_sort[0]["column"];
        $sort_dir = $temp_sort[0]["dir"];
        $sort_col_name = $request->get("columns")[$sort_num]["name"];
        $start_val = $request->get("start");
        $length_val = $request->get("length");

        $approved = $request->get("approved");
        $noapproved = $request->get("noapproved");
        $delete = $request->get("delete");
        $undefind = $request->get("undefind");

        $dt_data = array();
        $dt_data["search_key"] = $search_key;
        $dt_data["search_column"] = $search_column;
        $dt_data["sort_col_num"] = $sort_num;
        $dt_data["sort_col"] = $sort_col_name;
        $dt_data["sort_direction"] = $sort_dir;
        $dt_data["start"] = $start_val;
        $dt_data["length"] = $length_val;

        $dt_data["approved"] = $approved;
        $dt_data["noapproved"] = $noapproved;
        $dt_data["delete"] = $delete;
        $dt_data["undefind"] = $undefind;

        return $dt_data;
    }

    public function dataTableFormat($data, $total)
    {
        if(count($data) > 0)
        {
            $start = request('start');

            $i = 1;
            if(is_array($data[0]))
            {
                foreach ($data as &$one)
                {
                    $one["no"] = $start + $i;
                    $i++;
                }
            }
            else
            {
                foreach ($data as &$one)
                {
                    $one->no = $start + $i;
                    $i++;
                }
            }
        }

        $result = array(
            "data" => $data,
            "draw" => request("draw") + 1,
            "recordsFiltered" => $total,
            "recordsTotal" => $total
        );

        return $result;
    }

    public function download()
    {
        $file_name = Request("source");
        $down_file = Request("convert");

        $file_path = config("filesystems.disks.excel_export_path") . "/" . $file_name;

        return response()->download($file_path, $down_file);
    }

    public function download1()
    {
        $file_name = Request("source");
        $down_file = Request("convert");


        return response()->download($file_name, $down_file);
    }
}
