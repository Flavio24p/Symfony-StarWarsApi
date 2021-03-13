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
    public function fetchVehicles()
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

        if ($result == null) {
            return new JsonResponse([
                'success' => true,
                'data' => ['Cannot fetch any vehicles because the request is empty']
            ]);
        }

        $em = $this->getDoctrine()->getManager();
        $nrOfVehiclesFetched = 0;

        foreach ($result as $r) {
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
            $nrOfVehiclesFetched++;

            $em->persist($vehicles);
            $em->flush();
        }

        return new JsonResponse([
            'success' => true,
            'data' => ['Successfully fetched ' . $nrOfVehiclesFetched . ' from the galaxy into our little system :D']
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

        if ($vehicle == null) {
            return new JsonResponse([
                'success' => true,
                'data' => ['No Vehicle were found with this name, please try again!']
            ]);
        }

        $units = $vehicle->getCount();

        return new JsonResponse([
            'success' => true,
            'data' => ['Successfully found the vehicle ' . $vehicleName . ' that has:' . $units . ' units']
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

        if ($vehicle == null) {
            return new JsonResponse([
                'success' => true,
                'data' => ['No Vehicle were found with this name, please try again!']
            ]);
        }
        $vehicle->setCount($units);

        $em = $this->getDoctrine()->getManager();
        $em->persist($vehicle);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'data' => ['Successfully updated units for' . $vehicleName . ' vehicle by: ' . $units . ' units']
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

        if ($vehicle == null) {
            return new JsonResponse([
                'success' => true,
                'data' => ['No Vehicle found with this name, please try again']
            ]);
        }

        $units = $vehicle->getCount();
        if ($units == null) {
            return new JsonResponse([
                'success' => true,
                'data' => ['Number of units for this vehicle is not updated, try again after updating the units number for this vehicle']
            ]);
        }
        $newNrOfUnits = $units + $incrementBy;
        $vehicle->setCount($newNrOfUnits);

        $em = $this->getDoctrine()->getManager();
        $em->persist($vehicle);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'data' => ['Successfully increment number of units by: ' . $incrementBy . ' this vehicle now has:' . $newNrOfUnits . ' nr of units!']
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

        if ($vehicle == null) {
            return new JsonResponse([
                'success' => true,
                'data' => ['No Vehicle found with this name, please try again']
            ]);
        }

        if ($vehicle->getCount() == null) {
            return new JsonResponse([
                'success' => true,
                'data' => ['Number of units for this vehicle is not updated, try again after updating the units number for this vehicle']
            ]);
        }

        if ($vehicle->getCount() == 0) {
            return new JsonResponse([
                'success' => true,
                'data' => ['Number of units its 0 so it cant be decremented, try again later']
            ]);
        }

        $units = $vehicle->getCount();
        $newUnits = $units - $decrementBy;
        if ($newUnits < 0) {
            return new JsonResponse([
                'success' => true,
                'data' => ['Cannot be decremented because the number of units cannot be lower than 0']
            ]);
        }

        $vehicle->setCount($newUnits);
        $em = $this->getDoctrine()->getManager();
        $em->persist($vehicle);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'data' => ['Successfully decremented nr of units by: ' . $decrementBy . ' now the vehicle has: ' . $newUnits . ' units']
        ]);
    }
}