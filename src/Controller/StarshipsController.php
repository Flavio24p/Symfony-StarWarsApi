<?php

namespace App\Controller;

use App\Entity\Starships;
use App\Repository\StarshipsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class StarshipsController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/starships", name="starships", methods={"GET"})
     */
    public function fetchStarships(StarshipsRepository $starshipsRepository)
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
        $count = $content["count"];

        $em = $this->getDoctrine()->getManager();
        $countStarships = 0;

        foreach ($result as $r) {
            $query = $starshipsRepository->findOneBy(['url' => $r['url']]);
            if($query){
                continue;
            }

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
            $starship->setCount($count);
            $countStarships++;

            $em->persist($starship);
            $em->flush();
        }

        return new JsonResponse([
            'success' => true,
            'message' => ['Successfully fetched ' . $countStarships . ' Starships!'],
            'data' => $content
        ]);
    }

    /**
     * @Route("/getStarshipsUnits", name="startships_get_units", methods={"GET"})
     */
    public function getStarships(StarshipsRepository $starshipsRepository)
    {
        $startshipName = 'Death Star';
        //search for starship with this name

        $starship = $starshipsRepository->findOneBy(['name' => $startshipName]);

        if (!$starship) {
            return new JsonResponse([
                'success' => true,
                'message' => ['No Starships were found with this name, please try again!'],
            ]);
        }

        $units = $starship->getCount();

        return new JsonResponse([
            'success' => true,
            'message' => ['Successfully found the starships ' . $startshipName . ' that has:' . $units . ' units'],
            'data' => $units
        ]);
    }

    /**
     * @Route("/setStarshipsUnits", name="startships_set_units")
     */
    public function setStarshipUnit(StarshipsRepository $starshipsRepository)
    {
        $startshipName = 'Death Star';
        $units = 10;

        $starship = $starshipsRepository->findOneBy(['name' => $startshipName]);

        if (!$starship) {
            return new JsonResponse([
                'success' => true,
                'message' => ['No Starships were found with this name, please try again!']
            ]);
        }
        $starship->setCount($units);

        $em = $this->getDoctrine()->getManager();
        $em->persist($starship);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'message' => ['Successfully updated units for' . $startshipName . ' starships by: ' . $units . ' units'],
            'data' => $starship
        ]);
    }

    /**
     * @Route("/starships_increment", name="startships_increment")
     */
    public function incrementUnits(StarshipsRepository $starshipsRepository)
    {
        $startshipName = 'Death Star';
        $starship = $starshipsRepository->findOneBy(['name' => $startshipName]);

        //set a number that you want to increment the units by:
        $incrementBy = 3;

        if (!$starship) {
            return new JsonResponse([
                'success' => true,
                'message' => ['No Starships found with this name, please try again']
            ]);
        }

        $units = $starship->getCount();
        if (!$units) {
            return new JsonResponse([
                'success' => true,
                'message' => ['Number of units for this spaceship is not updated, try again after updating the units number for this spaceship']
            ]);
        }
        $newNrOfUnits = $units + $incrementBy;
        $starship->setCount($newNrOfUnits);

        $em = $this->getDoctrine()->getManager();
        $em->persist($starship);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'message' => ['Successfully increment number of units by: ' . $incrementBy . ' this spacheship now has:' . $newNrOfUnits . ' nr of units!']
        ]);
    }

    /**
     * @Route("/starships_decrement", name="startships_decrement")
     */
    public function decrementUnits(StarshipsRepository $starshipsRepository)
    {
        $starshipName = 'Death Star';
        $starship = $starshipsRepository->findOneBy(['name' => $starshipName]);

        //set the value you want to decrement the number of units
        $decrementBy = 2;

        if (!$starship) {
            return new JsonResponse([
                'success' => true,
                'message' => ['No Starships found with this name, please try again']
            ]);
        }

        if ($starship->getCount() == 0) {
            return new JsonResponse([
                'success' => true,
                'message' => ['Number of units its 0 so it cant be decremented, try again later']
            ]);
        }

        $units = $starship->getCount();
        $newUnits = $units - $decrementBy;
        if ($newUnits < 0) {
            return new JsonResponse([
                'success' => true,
                'message' => ['Cannot be decremented because the number of units cannot be lower than 0']
            ]);
        }

        $starship->setCount($newUnits);
        $em = $this->getDoctrine()->getManager();
        $em->persist($starship);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'message' => ['Successfully decremented nr of units by: ' . $decrementBy . ' now the spaceship has: ' . $newUnits . ' units'],
            'data' => $starship
        ]);
    }
}