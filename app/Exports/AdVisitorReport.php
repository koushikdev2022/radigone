<?php

namespace App\Exports;

use App\Answer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class AdVisitorReport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromCollection, WithHeadings
{
    protected $ad_type;
    protected $survey_id;
    public function __construct($ad_type, $survey_id)
    {
        $this->ad_type = $ad_type;
        $this->survey_id = $survey_id;
    }
    public function headings():array{
        if($this->ad_type == 1) {
            return[
                'Name',
                'Contact',
                'Email',
                'Age',
                'Gender',
                'Whatsapp',
                'Anniversary Date',
                'City'
            ];
        }elseif($this->ad_type == 2) {
            return[
                'Name',
                'Contact',
                'Email',
            ];
        }elseif($this->ad_type == 3) {
            return[
                'Name',
                'Contact',
                'Email',
                'age',
                'Martial Status',
                'Birthday',
                'Whatsapp',
                'Martial Status',
                'Anniversary Date',
                'City',
            ];
        }elseif($this->ad_type == 4) {
            return[
                'Name',
                'Contact',
                'Email',
                'age',
                'Martial Status',
                'Birthday',
                'Whatsapp',
                'Anniversary Date',
                'Profession',
                'Annual Income',
                'City',
                'Country',
            ];
        }

    }

    public function collection()
    {
        if($this->ad_type == 1) {
            return Answer::where('survey_id', $this->survey_id)->groupBy('user_id')->with('user')->get()->map(function ($data) {
                return [
                    'Name' => $data->user->firstname.' '.$data->user->lastname,
                    'Contact' => substr($data->user->mobile, 2),
                    'Email' => $data->user->email,
                    'Age' => $data->user->age,
                    'Gender' => $data->user->gander,
                    'Whatsapp' => $data->user->whatsaap,
                    'Anniversary Date' => $data->user->anniversary_date,
                    'City' => $data->user->address->city,
                ];
            });
        }elseif($this->ad_type == 2) {
            return Answer::where('survey_id', $this->survey_id)->groupBy('user_id')->with('user')->get()->map(function ($data) {
                return [
                    'Name' => $data->user->firstname.' '.$data->user->lastname,
                    'Contact' => substr($data->user->mobile, 2),
                    'Email' => $data->user->email,
                ];
            });
        }elseif($this->ad_type == 3) {
            return Answer::where('survey_id', $this->survey_id)->groupBy('user_id')->with('user')->get()->map(function ($data) {
                return [
                    'Name' => $data->user->firstname.' '.$data->user->lastname,
                    'Contact' => substr($data->user->mobile, 2),
                    'Email' => $data->user->email,
                    'Age' => $data->user->age,
                    'Martial Status' => $data->user->marital,
                    'Birthday' => $data->user->dob,
                    'Whatsapp' => $data->user->whatsaap,
                    'Anniversary Date' => $data->user->anniversary_date,
                    'City' => $data->user->address->city,
                ];
            });
        }elseif($this->ad_type == 4) {
            return Answer::where('survey_id', $this->survey_id)->groupBy('user_id')->with('user')->get()->map(function ($data) {
                return [
                    'Name' => $data->user->firstname.' '.$data->user->lastname,
                    'Contact' => substr($data->user->mobile, 2),
                    'Email' => $data->user->email,
                    'Age' => $data->user->age,
                    'Martial Status' => $data->user->marital,
                    'Birthday' => $data->user->dob,
                    'Whatsapp' => $data->user->whatsaap,
                    'Anniversary Date' => $data->user->anniversary_date,
                    'Profession' => $data->user->occupation,
                    'Annual Income' => $data->user->annual_income,
                    'City' => $data->user->address->city,
                    'Country' => $data->user->address->country,
                ];
            });
        }
    }
}
