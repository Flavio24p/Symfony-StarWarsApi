<?php

namespace App\Controller;

use App\Entity\Starships;
use App\Entity\Vehicles;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Controller extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/starships", name="starships", methods={"GET"})
     */
    public function fetchStarships(): array
    {
        $response = $this->client->request(
            'GET',
            'https://swapi.dev/api/starships/'
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
        dump($result);
        if($result != null){
            $em = $this->getDoctrine()->getManager();

            foreach($result as $r){
                $starship = new Starships();
                $starship->setName($r['name']);
                $starship->setModel($r['model']);
                $starship->setStarshipClass($r['starship_class']);
                $starship->setManufacturer($r['manufacturer']);
                $starship->setCostInCredits($r['cost_in_credits']);
                $starship->setLength($r['length']);
                $starship->setCrew($r['crew']);
                $starship->setPassengers($r['passengers']);
                $starship->setMaxAtmospherinSpeed($r['max_atmosphering_speed']);
                $starship->setHyperdriveRating($r['hyperdrive_rating']);
                $starship->setMGLT($r['MGLT']);
                $starship->setCargoCapacity($r['cargo_capacity']);
                $starship->setConsumables($r['consumables']);
                $starship->setFilms($r['films']);
                $starship->setPilots($r['pilots']);
                $starship->setUrl($r['url']);
                $starship->setCreated($r['created']);
                $starship->setEdited($r['edited']);

                dd($starship);
                $em->persist($starship);
                $em->flush();
            }
        }

        return $content;
    }

    /**
     * @Route("/vehicles", name="vehicles", methods={"GET"})
     */
    public function fetchVehicles(): array
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
        dump($result);
        if($result != null){
            $em = $this->getDoctrine()->getManager();

            foreach($result as $r){
                $vehicles = new Vehicles();
                $vehicles->setName($r['name']);

                dd($vehicles);
                $em->persist($vehicles);
                $em->flush();
            }
        }

        return $content;
    }
}