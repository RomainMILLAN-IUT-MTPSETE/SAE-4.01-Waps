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
        $noeudRoutierArriveeLatitude = $this->noeudRoutierArrivee->getLatitude();
        $noeudRoutierArriveeLongitude = $this->noeudRoutierArrivee->getLongitude();
        $this->distances = [$noeudRoutierDepartGid => 0];
        $this->heuristique[$noeudRoutierDepartGid] = $this->calculerHeuristique($noeudRoutierArriveeLatitude, $noeudRoutierArriveeLongitude, $this->noeudRoutierDepart->getLatitude(), $this->noeudRoutierDepart->getLongitude());
        $frontiere = new SplPriorityQueue();
        $frontiere->insert($noeudRoutierDepartGid, -$this->heuristique[$noeudRoutierDepartGid]);

        while (!$frontiere->isEmpty()) {
            $noeudRoutierGidCourant = $frontiere->extract();
            if ($noeudRoutierGidCourant === $noeudRoutierArriveeGid){
                return [
                    'distances' => $this->distances[$noeudRoutierGidCourant],
                ];
            }
            $voisins = $this->noeudRoutierRepository->getVoisins($noeudRoutierGidCourant);
            foreach ($voisins as $voisin) {
                $noeudVoisinGid = $voisin["noeud_routier_gid"];
                $distanceTroncon = $voisin["longueur"];
                $distanceProposee = $this->distances[$noeudRoutierGidCourant] + $distanceTroncon;
                if (!isset($this->distances[$noeudVoisinGid]) || $distanceProposee < $this->distances[$noeudVoisinGid]) {
                    $this->distances[$noeudVoisinGid] = $distanceProposee;
                    $this->heuristique[$noeudVoisinGid] = $this->calculerHeuristique($noeudRoutierArriveeLatitude, $noeudRoutierArriveeLongitude, $voisin["latitude"], $voisin["longitude"]);
                    $frontiere->insert($noeudVoisinGid, -($distanceProposee + $this->heuristique[$noeudVoisinGid]));
                }
            }
        }

        return [
            'distances' => 0,
        ];
    }

    private function calculerHeuristique($latitudeArrivee, $longitudeArrivee, $latitude, $longitude): float {
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
