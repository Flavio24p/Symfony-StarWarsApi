<?php

namespace App\Controller;

use App\Entity\Vehicles;
use App\Repository\VehiclesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;

class VehiclesController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/vehicles", name="vehicles", methods={"GET"})
     */
    public function fetchVehicles(VehiclesRepository $vehiclesRepository)
    {
        $response = $this->client->request(
            'GET',
            'https://swapi.dev/api/vehicles/'
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        $result = $content["results"];
        $count = $content["count"];

        $em = $this->getDoctrine()->getManager();
        $nrOfVehiclesFetched = 0;

        foreach ($result as $r) {
            //this query will help avoid dublicating data on Vehicles db table
            $query = $vehiclesRepository->findOneBy(['url' => $r['url']]);
            if($query){
                continue;
            }

            $vehicles = new Vehicles();
            $vehicles->setName($r['name']);
            $vehicles->setModel($r['model']);
            $vehicles->setManufacturer($r['manufacturer']);
            $vehicles->setCostInCredits($r['cost_in_credits']);
            $vehicles->setLength($r['length']);
            $vehicles->setMaxAtmospheringSpeed($r['max_atmosphering_speed']);
            $vehicles->setCrew($r['crew']);
            $vehicles->setPassegers($r['passengers']);
            $vehicles->setCargoCapacity($r['cargo_capacity']);
            $vehicles->setConsumables($r['consumables']);
            $vehicles->setVehicleClass($r['vehicle_class']);
            $vehicles->setPilots($r['pilots']);
            $vehicles->setFilms($r['films']);
            $vehicles->setCreated($r['created']);
            $vehicles->setEdited($r['edited']);
            $vehicles->setUrl($r['url']);
            $vehicles->setCount($count);
            $nrOfVehiclesFetched++;

            $em->persist($vehicles);
            $em->flush();
        }

        return new JsonResponse([
            'success' => true,
            'message' => [$nrOfVehiclesFetched.' fetched vehicles'],
            'data' => $content
        ]);
    }

    /**
     * @Route("/getVehiclesUnits", name="vehicles_get_units", methods={"GET"})
     */
    public function getVehicles(VehiclesRepository $vehiclesRepository)
    {
        $vehicleName = 'Sand Crawler';
        //search for vehicle with this name

        $vehicle = $vehiclesRepository->findOneBy(['name' => $vehicleName]);

        if (!$vehicle) {
            return new JsonResponse([
                'success' => false,
                'message' => ['No Vehicle were found with this name, please try again!']
            ]);
        }

        $units = $vehicle->getCount();

        return new JsonResponse([
            'success' => true,
            'message' => ['Successfully found the vehicle ' . $vehicleName . ' that has:' . $units . ' units'],
            'data' => $units
        ]);
    }

    /**
     * @Route("/setVehicleUnits", name="vehicles_set_units")
     */
    public function setVehicleUnit(VehiclesRepository $vehiclesRepository)
    {
        $vehicleName = 'Sand Crawler';
        $units = 10;

        $vehicle = $vehiclesRepository->findOneBy(['name' => $vehicleName]);

        if (!$vehicle) {
            return new JsonResponse([
                'success' => false,
                'message' => ['No Vehicle were found with this name, please try again!']
            ]);
        }
        $vehicle->setCount($units);

        $em = $this->getDoctrine()->getManager();
        $em->persist($vehicle);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'message' => ['Successfully updated units for' . $vehicleName . ' vehicle by: ' . $units . ' units'],
            'data' => $vehicle
        ]);
    }

    /**
     * @Route("/vehicle_increment", name="vehicle_increment")
     */
    public function incrementUnits(VehiclesRepository $vehiclesRepository)
    {
        $vehicleName = 'Sand Crawler';
        $vehicle = $vehiclesRepository->findOneBy(['name' => $vehicleName]);

        //set a number that you want to increment the units by:
        $incrementBy = 3;

        if (!$vehicle) {
            return new JsonResponse([
                'success' => false,
                'message' => ['No Vehicle found with this name, please try again']
            ]);
        }

        $units = $vehicle->getCount();
        $newNrOfUnits = $units + $incrementBy;
        $vehicle->setCount($newNrOfUnits);

        $em = $this->getDoctrine()->getManager();
        $em->persist($vehicle);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'message' => ['Successfully increment number of units by: ' . $incrementBy . ' this vehicle now has:' . $newNrOfUnits . ' nr of units!'],
            'data' => $vehicle
        ]);
    }

    /**
     * @Route("/vehcile_decrement", name="vehicle_decrement")
     */
    public function decrementUnits(VehiclesRepository $vehiclesRepository)
    {
        $vehcileName = 'Sand Crawler';
        $vehicle = $vehiclesRepository->findOneBy(['name' => $vehcileName]);

        //set the value you want to decrement the number of units
        $decrementBy = 2;

        if (!$vehicle) {
            return new JsonResponse([
                'success' => false,
                'message' => ['No Vehicle found with this name, please try again']
            ]);
        }

        if ($vehicle->getCount() == 0) {
            return new JsonResponse([
                'success' => false,
                'message' => ['Number of units its 0 so it cant be decremented, try again later']
            ]);
        }

        $units = $vehicle->getCount();
        $newUnits = $units - $decrementBy;
        if ($newUnits < 0) {
            return new JsonResponse([
                'success' => false,
                'message' => ['Cannot be decremented because the number of units cannot be lower than 0']
            ]);
        }

        $vehicle->setCount($newUnits);
        $em = $this->getDoctrine()->getManager();
        $em->persist($vehicle);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'message' => ['Successfully decremented nr of units by: ' . $decrementBy . ' now the vehicle has: ' . $newUnits . ' units'],
            'data' => $vehicle
        ]);
    }
}