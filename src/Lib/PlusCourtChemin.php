<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;
use App\PlusCourtChemin\Modele\HTTP\Cookie;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;
use SplPriorityQueue;

class PlusCourtChemin {
    private array $heuristique;
    private array $distances;
    private array $noeudsALaFrontiere = [];

    public function __construct(
        private NoeudRoutier $noeudRoutierDepart,
        private NoeudRoutier $noeudRoutierArrivee,
        private NoeudRoutierRepository $noeudRoutierRepository
    ) {
    }

    public function calculer(): ?array {
        $noeudRoutierDepartGid = $this->noeudRoutierDepart->getGid();
        $noeudRoutierArriveeGid = $this->noeudRoutierArrivee->getGid();
        $this->distances = [$noeudRoutierDepartGid => 0];
        $this->heuristique[$noeudRoutierDepartGid] = $this->calculerHeuristique($this->noeudRoutierDepart->getLatitude(), $this->noeudRoutierDepart->getLongitude());
        $frontiere = new SplPriorityQueue();
        $frontiere->insert($noeudRoutierDepartGid, -$this->heuristique[$noeudRoutierDepartGid]);
        $parcours = [];
        while (!$frontiere->isEmpty()) {
            $noeudRoutierGidCourant = $frontiere->extract();
            $parcours[] = [
                'latitude' => $this->noeudRoutierRepository->getLatitudeLongitude($noeudRoutierGidCourant)["latitude"],
                'longitude' => $this->noeudRoutierRepository->getLatitudeLongitude($noeudRoutierGidCourant)["longitude"]
            ];
            if ($noeudRoutierGidCourant === $noeudRoutierArriveeGid){ 
                return [
                    'distances' => $this->distances[$noeudRoutierGidCourant],
                    'parcours' => $parcours
                ];
            }
            $voisins = $this->noeudRoutierRepository->getVoisins($noeudRoutierGidCourant);
            foreach ($voisins as $voisin) {
                $noeudVoisinGid = $voisin["noeud_routier_gid"];
                $distanceTroncon = $voisin["longueur"];
                $distanceProposee = $this->distances[$noeudRoutierGidCourant] + $distanceTroncon;
                if (!isset($this->distances[$noeudVoisinGid]) || $distanceProposee < $this->distances[$noeudVoisinGid]) {
                    $this->distances[$noeudVoisinGid] = $distanceProposee;
                    $this->heuristique[$noeudVoisinGid] = $this->calculerHeuristique($voisin["latitude"], $voisin["longitude"]);
                    $frontiere->insert($noeudVoisinGid, -($distanceProposee + $this->heuristique[$noeudVoisinGid]));
                }
            }
        }
    }
    

    private function calculerHeuristique($latitude, $longitude): float {
        $longitudeArrivee = $this->noeudRoutierArrivee->getLongitude();
        $latitudeArrivee = $this->noeudRoutierArrivee->getLatitude();
        return $this->distanceEntreDeuxPointsHaversine($latitude, $longitude, $latitudeArrivee, $longitudeArrivee);
    }

    function distanceEntreDeuxPointsHaversine($lat1, $lon1, $lat2, $lon2) {
        $rayonTerre = 6371; 
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $deltaPhi = deg2rad($lat2 - $lat1);
        $deltaLambda = deg2rad($lon2 - $lon1);
        $c = 2 * asin(sqrt(sin($deltaPhi/2) * sin($deltaPhi/2) + cos($radLat1) * cos($radLat2) * sin($deltaLambda/2) * sin($deltaLambda/2)));        
        $distance = $rayonTerre * $c;
        return $distance;
    }

}
