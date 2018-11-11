<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Sms as Sms_model;
use JsonSchema;


class Sms extends Controller
{
    private $sms_model;
    private $count_list = ['tp', 'tc', 'bhp', 'bhc', 'fp', 'fc', 'f(h)', 'f(1)', 'f(2)', 'ucp', 'ucc', 'uc(h)', 'ulp', 'ulc', 'ul(h)', 'uop', 'uoc', 'uo(h)', 'uo(1)', 'uz',
        'ornj', 'lmnj', 'm', 'm(h)', 'm(1)', 'mkp', 'mkt', 'mk(h)', 'lyp', 'lyc', 'a(h)', 'a(1.5)'];

    function __construct()
    {
        $this->middleware('auth');
        $this->sms_model = new Sms_model();
    }

    /**
     * prepare data
     * @param array $input
     * @return array
     */
    private function prepareData(array $input,$number,$identifier,$additionals=[])
    {
        $result_array = [];

        foreach ($input as $val) {
            $val = strtolower($val);
            if (strpos($val, '-')) {
                $dt = explode("-", $val);
                $result_array[$dt[0]] = count($dt) - 1 > 1 ?
                    ($dt[0]==='pdn'?
                        explode(',',implode('-', array_slice($dt, 1))) : implode('-', array_slice($dt, 1)))
                    : $dt[1];
            } else {
                $error_message = !empty($val) ? 'Invalid SMS Format from String Contains (' . $val . ') !!' : 'Invalid SMS Format !!';
                return [
                    'status' => false,
                    'message' => $error_message,
                    'additional'=> $additionals,
                    'identifier'=>$identifier
                ];
            }

        }

        return ['status' => true, 'data' => $result_array];
    }

    /**
     * validate data
     * @param $identifier
     * @param $input_array
     * @return array
     */
    private function validateData($identifier, $input_array,$additionals=[])
    {
        $result_array = self::prepareData($input_array,$additionals['sender'],$identifier,$additionals);
        $result_array['type'] = strtolower($identifier);

        if ($result_array['status'] === false) {

            return $result_array;
        }
        //validation
        $data = json_decode(json_encode($result_array['data']));
        $validator = new JsonSchema\Validator;
        $validator->validate($data, (object)['$ref' => 'file://' . realpath('resources/schemas/' . strtolower($identifier) . '.json')]);

        if (!$validator->isValid()) {
            foreach ($validator->getErrors() as $error) {
                $error_message = sprintf("%s %s in $identifier SMS \n", $error['property'], $error['constraint']);
                return [
                    'status' => false,
                    'message' => $error_message,
                    'additional'=> $additionals,
                    'identifier'=>$identifier
                ];
            }
        } else {

            return $result_array;
        }
    }

    private function packageInformation($packages){
       $result_array=[];
       foreach ($packages as $package){
            $package_data = explode('-',$package);
            if(count($package_data)<3){
                return $result_array =['status'=>false];
            }
            $result_array[$package_data[0]]=[
                  'quantity'  => $package_data[1],
                  'no_of_memo' => $package_data[2]
            ];
       }
       return $result_array;
    }

    private function validatePromotionData($identifier, $input_array,$additionals=[]){
        $result_array = self::prepareData($input_array,$additionals['sender'],$identifier,$additionals);
        $result_array['type'] = strtolower($identifier);

        $data = json_decode(json_encode($result_array['data']));
        $validator = new JsonSchema\Validator;
        $validator->validate($data, (object)['$ref' => 'file://' . realpath('resources/schemas/' . strtolower($identifier) . '.json')]);


        if (!$validator->isValid()) {
            foreach ($validator->getErrors() as $error) {
                $error_message = sprintf("%s %s in $identifier SMS \n", $error['property'], $error['constraint']);
                return [
                    'status' => false,
                    'message' => $error_message,
                    'additional'=> $additionals,
                    'identifier'=>$identifier
                ];
            }
        } else {
            //$validate_promotion_data= $this->packageInformation($result_array['data']['pdn']);
            return $result_array;


        }
    }

    /**
     * parse data
     * @param $input_data
     * @return array
     */
    private function parseData($input_data,$additionals=[])
    {

        if (strpos($input_data, '/') === false) {

            return [
                   'status' => false,
                   'message' => 'Invalid message string',
                   'additional'=>[
                       'sender'=>$additionals['sender'],
                       'id'=>$additionals['id']
                   ],
                   'identifier'=>''
            ];
        } else {

            list($identifier, $input) = explode('/', $input_data, 2);
            $input_array = (explode("/", $input));

            switch (strtolower($identifier)) {
                case ORDER:

                    $res = $this->validateData($identifier, $input_array,['sender'=>$additionals['sender'],'id'=>$additionals['id']]);
                    break;
                case SALE:

                    $res = $this->validateData($identifier, $input_array,['sender'=>$additionals['sender'],'id'=>$additionals['id']]);
                    break;
                case PRIMARY:

                    $res = $this->validateData($identifier, $input_array,['sender'=>$additionals['sender'],'id'=>$additionals['id']]);
                    break;
                case PROMOTION:

                    $res= $this->validatePromotionData($identifier,$input_array,['sender'=>$additionals['sender'],'id'=>$additionals['id']]);
                    break;
                default:

                    return [
                        'status' => false,
                        'message' => 'Invalid message format!',
                        'additional'=>[
                            'sender'=>$additionals['sender'],
                            'id'=>$additionals['id']
                        ],
                        'identifier'=>$identifier
                    ];

            }
            $res['additional'] = $additionals;
            $res['identifier'] = $identifier;
            return $res;
        }

    }

    /**
     * parse SMS
     * @param $id
     * @return array\
     */
    public function parseSms($id)
    {
        $data = $this->sms_model->getSms($id);

        if (!empty($data) && !empty($data->sms_content)) {

            return $this->parseData($data->sms_content,['id'=>$id,'sender'=>$data->sender]);
        }

    }
}
