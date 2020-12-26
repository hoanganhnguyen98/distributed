<?php

namespace App\Http\Controllers\Distributed;

use App\Http\Controllers\Distributed\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Model\Task;
use App\Model\TaskType;
use App\Model\Employee;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request as ApiRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Distributed\EmployeeController;

class ZoneAreaController extends BaseController
{
    public $area_id;

    public function __construct()
    {
        $this->area_id = null;
    }

    public function updateTimes($apiToken, $projectType, $incident_id)
    {
        $object_ids = $this->getObjectFromIncident($apiToken, $projectType, $incident_id);

        if (count($object_ids)) {
            foreach ($objects_ids as $object_id) {
                $zone_id = $this->getZoneFromObject($object_id);

                $zoneTimes = $this->getZoneTimesById($zone_id, $apiToken, $projectType);

                if ($zoneTimes !== null) {
                    $newZoneTimes = $zoneTimes + 1;
                    $this->updateZoneTimes($zone_id, $newZoneTimes, $apiToken, $projectType);
                }

                if ($this->area_id !== null) {
                    $areaTimes = $this->getAreaTimesById($apiToken, $projectType);

                    if ($areaTimes !== null) {
                        $newAreaTimes = $areaTimes + 1;
                        $this->updateAreaTimes($times, $apiToken, $projectType);
                    }
                }
            }
        }
    }

    public function getObjectFromIncident($apiToken, $projectType, $incident_id)
    {
        $url = 'https://it4483.cf/api/incidents/'.$incident_id;
        $headers = [
            'api-token' => $apiToken,
            'project-type' => $projectType
        ];

        $client = new \GuzzleHttp\Client();

        $validResponse = true;

        try {
            $response = $client->get($url, [
                'headers' => $headers
            ]);
        } catch (\Throwable $th) {

            $validResponse = false;
        }

        $images = [];
        $videos  = [];

        if ($validResponse) {
            $responseStatus = $response->getStatusCode();
            $data = json_decode($response->getBody()->getContents(), true);

            if ($responseStatus !== 200) {
                $validResponse = false;
            }

            $existed_task = Task::where('incident_id', $incident_id)->first();

            if (!$existed_task) {
                $validResponse = false;
            }

            if ($validResponse) {
                $images = $data['image'];
                $videos = $data['videos'];
            }
        }

        $object_ids = [];
        if (count($images)) {
            foreach ($images as $image) {
                $object_id = $image['monitoredObjectId'];

                if($object_id) {
                    $object_ids[] = $object_id;
                }
            }
        }

        if (count($videos)) {
            foreach ($videos as $video) {
                $object_id = $video['monitoredObjectId'];

                if($object_id) {
                    $object_ids[] = $object_id;
                }
            }
        }

        if (count($object_ids)) {
            $object_ids = array_unique($object_ids);
        }

        return $object_ids;
    }

    public function getZoneFromObject($object_id)
    {
        $url = 'https://dsd05-monitored-object.herokuapp.com/monitored-object/detail-monitored-object/'.$object_id;

        // $headers = [
        //     'api-token' => $apiToken,
        //     'project-type' => $projectType
        // ];

        $client = new \GuzzleHttp\Client();

        $validResponse = true;

        try {
            $response = $client->get($url, [
                // 'headers' => $headers
            ]);
        } catch (\Throwable $th) {

            $validResponse = false;
        }

        $zone_id = null;
        if ($validResponse) {
            $responseStatus = $response->getStatusCode();
            $data = json_decode($response->getBody()->getContents(), true);

            if ($responseStatus !== 200) {
                $validResponse = false;
            }

            if ($validResponse) {
                $zones = $data['content']['monitoredZone'];

                if ($zones) {
                    $zone_id = $zones[0];
                }
            }
        }

        return $zone_id;
    }

    public function getZoneTimesById($zone_id, $apiToken, $projectType)
    {
        $url = 'https://monitoredzoneserver.herokuapp.com/monitoredzone/zoneinfo/'.$zone_id;

        $headers = [
            'token' => $apiToken,
            'projecttype' => $projectType,
        ];

        $client = new \GuzzleHttp\Client();

        $validResponse = true;

        try {
            $response = $client->get($url, [
                'headers' => $headers
            ]);
        } catch (\Throwable $th) {

            $validResponse = false;
        }

        $area_times = null;
        if ($validResponse) {
            $responseStatus = $response->getStatusCode();
            $data = json_decode($response->getBody()->getContents(), true);

            if ($responseStatus !== 200) {
                $validResponse = false;
            }

            if ($validResponse) {
                $zone = $data['content']['zone'];

                if ($zone !== null) {
                    $this->area_id = $data['content']['zone']['area'];
                    $area_times = $data['content']['zone']['times'];
                }
            }
        }

        return $area_times;
    }

    public function getAreaTimesById($apiToken, $projectType)
    {
        $url = 'https://monitoredzoneserver.herokuapp.com/area/areainfo/'.$this->area_id;

        $headers = [
            'token' => $apiToken,
            'projecttype' => $projectType
        ];

        $client = new \GuzzleHttp\Client();

        $validResponse = true;

        try {
            $response = $client->get($url, [
                'headers' => $headers
            ]);
        } catch (\Throwable $th) {

            $validResponse = false;
        }

        $zone_times = null;
        if ($validResponse) {
            $responseStatus = $response->getStatusCode();
            $data = json_decode($response->getBody()->getContents(), true);

            if ($responseStatus !== 200) {
                $validResponse = false;
            }

            if ($validResponse) {
                if ($data['content']['area'] !== null) {
                    $zone_times = $data['content']['area']['times'];
                }
            }
        }

        return $zone_times;
    }

    public function updateZoneTimes($zone_id, $times, $apiToken, $projectType)
    {
        $url = 'https://monitoredzoneserver.herokuapp.com/monitoredzone/'.$zone_id;

        $headers = [
            'token' => $apiToken,
            'projecttype' => $projectType,
            'Content-Type' => 'application/json'
        ];

        $body = [
            'times' => $times
        ];

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->put($url, [
                'headers' => $headers,
                'json' => $body
            ]);
        } catch (\Throwable $th) {
        }
    }

    public function updateAreaTimes($times, $apiToken, $projectType)
    {
        $url = 'https://monitoredzoneserver.herokuapp.com/area/'.$this->area_id;

        $headers = [
            'token' => $apiToken,
            'projecttype' => $projectType,
            'Content-Type' => 'application/json'
        ];

        $body = [
            'times' => $times
        ];

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->put($url, [
                'headers' => $headers,
                'json' => $body
            ]);
        } catch (\Throwable $th) {
        }
    }
}
