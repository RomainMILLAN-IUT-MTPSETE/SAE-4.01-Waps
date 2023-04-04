<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;
use SplPriorityQueue;

class PlusCourtChemin {
    private array $heuristique;
    private array $distances;
    private array $noeudsALaFrontiere = [];

    public function __construct(
        private int $noeudRoutierDepartGid,
        private int $noeudRoutierArriveeGid
    ) {
    }

    public function calculer(): ?float {
        $noeudRoutierRepository = new NoeudRoutierRepository();
        $this->distances = [$this->noeudRoutierDepartGid => 0];
        $this->heuristique[$this->noeudRoutierDepartGid] = $this->calculerHeuristique($noeudRoutierRepository->recupererParClePrimaire($this->noeudRoutierDepartGid));
        $frontiere = [$this->noeudRoutierDepartGid => $this->distances[$this->noeudRoutierDepartGid] + $this->heuristique[$this->noeudRoutierDepartGid]];
        while (!empty($frontiere)) {
            $noeudRoutierGidCourant = array_keys($frontiere, min($frontiere))[0];
            if ($noeudRoutierGidCourant === $this->noeudRoutierArriveeGid) return $this->distances[$noeudRoutierGidCourant];
            unset($frontiere[$noeudRoutierGidCourant]);
            $noeudRoutierCourant = $noeudRoutierRepository->recupererParClePrimaire($noeudRoutierGidCourant);
            $voisins = $noeudRoutierCourant->getVoisins();
            foreach ($voisins as $voisin) {
                $noeudVoisinGid = $voisin["noeud_routier_gid"];
                $distanceTroncon = $voisin["longueur"];
                $distanceProposee = $this->distances[$noeudRoutierGidCourant] + $distanceTroncon;
                if (!isset($this->distances[$noeudVoisinGid]) || $distanceProposee < $this->distances[$noeudVoisinGid]) {
                    $this->distances[$noeudVoisinGid] = $distanceProposee;
                    $this->heuristique[$noeudVoisinGid] = $this->calculerHeuristique($noeudRoutierRepository->recupererParClePrimaire($noeudVoisinGid));
                    $frontiere[$noeudVoisinGid] = $distanceProposee + $this->heuristique[$noeudVoisinGid];
                }
            }
        }
    }

    private function calculerHeuristique($noeud): float {
        $noeudRoutierRepository = new NoeudRoutierRepository;
        $longitudeDepart = $noeud->getLongitude();
        $latitudeDepart = $noeud->getLatitude();
        $longitudeArrivee = $noeudRoutierRepository->recupererParClePrimaire($this->noeudRoutierArriveeGid)->getLongitude();
        $latitudeArrivee = $noeudRoutierRepository->recupererParClePrimaire($this->noeudRoutierArriveeGid)->getLatitude();
        return $this->distanceEntreDeuxPoints($latitudeDepart, $longitudeDepart, $latitudeArrivee, $longitudeArrivee);
    }

    function distanceEntreDeuxPoints($lat1, $lon1, $lat2, $lon2) {
        $rayonTerre = 6371000; 
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $deltaPhi = deg2rad($lat2 - $lat1);
        $deltaLambda = deg2rad($lon2 - $lon1);
        $a = sin($deltaPhi/2) * sin($deltaPhi/2) + cos($radLat1) * cos($radLat2) * sin($deltaLambda/2) * sin($deltaLambda/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $rayonTerre * $c;
        return $distance;
    }

    private function noeudALaFrontiereDeDistanceMinimale() {
        $noeudRoutierDistanceMinimaleGid = -1;
        $distanceMinimale = PHP_INT_MAX;
        foreach ($this->noeudsALaFrontiere as $noeudRoutierGid => $valeur) {
            if ($this->distances[$noeudRoutierGid] < $distanceMinimale) {
                $noeudRoutierDistanceMinimaleGid = $noeudRoutierGid;
                $distanceMinimale = $this->distances[$noeudRoutierGid];
            }
        }
        return $noeudRoutierDistanceMinimaleGid;
    }

}