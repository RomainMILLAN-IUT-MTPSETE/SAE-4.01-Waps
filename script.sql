create table noeud_commune(
    gid        integer,
    id_rte500  varchar(24),
    insee_comm varchar(5),
    nom_chf    varchar(200),
    statut     varchar(30),
    nom_comm   varchar(100),
    superficie double precision,
    population double precision,
    id_nd_rte  varchar(24),
    geom       geometry(Point, 4326)
);

create table noeud_routier(
    gid        integer,
    id_rte500  varchar(24),
    nature     varchar(80),
    insee_comm varchar(5),
    geom       geometry(Point, 4326)
);

create index idx_geom_noeud_routier
    on noeud_routier using gist (geom);

create index idx_gid_noeud_routier
    on noeud_routier (gid);

create table troncon_route(
    gid        integer,
    id_rte500  varchar(24),
    vocation   varchar(80),
    nb_chausse varchar(80),
    nb_voies   varchar(80),
    etat       varchar(80),
    acces      varchar(80),
    res_vert   varchar(80),
    sens       varchar(80),
    num_route  varchar(24),
    res_europe varchar(200),
    longueur   double precision,
    class_adm  varchar(20),
    geom       geometry(LineString, 4326)
);

create index idx_geom_troncon_route
    on troncon_route using gist (geom);

create index idx_gid_troncon_route
    on troncon_route (gid);

CREATE TABLE utilisateur (
    login integer NOT NULL,
    nom varchar(255) NOT NULL,
    prenom varchar(255) NOT NULL,
    mdp_hache varchar(255) NOT NULL,
    est_admin bool NOT NULL,
    email varchar(255) NOT NULL,
    email_a_valider varchar(255) NOT NULL,
    nonce varchar(255) NOT NULL,
    CONSTRAINT utilisateur_pkey PRIMARY KEY (login)
);

insert into utilisateur (login, nom, prenom, mdp_hache, est_admin, email, email_a_valider, nonce)
values (1, 'Waps', 'Developpeur', '', '0', 'dev@waps.fr', '', '');

create materialized view noeud_routier_mt as
SELECT nr.gid,
       nr.geom
FROM noeud_routier nr
GROUP BY nr.gid, nr.geom;

create index idx_noeud_routier_mt_geom
    on noeud_routier_mt using gist (geom);

create index idx_noeud_routier_mt_gid
    on noeud_routier_mt (gid);

create materialized view troncon_route_mt as
SELECT tr.gid,
       tr.geom,
       tr.longueur
FROM troncon_route tr
GROUP BY tr.gid, tr.geom, tr.longueur;

create index idx_troncon_route_mt_geom
    on troncon_route_mt using gist (geom);

create materialized view voisins as
SELECT nrdeb.gid AS noeud,
       nrfin.gid AS voisin,
       tr.gid    AS troncon_gid,
       tr.longueur
FROM troncon_route_mt tr
         CROSS JOIN LATERAL ( SELECT nr.gid
                              FROM noeud_routier_mt nr
                              ORDER BY (nr.geom <-> st_startpoint(tr.geom))
                              LIMIT 1) nrdeb
         CROSS JOIN LATERAL ( SELECT nr.gid
                              FROM noeud_routier_mt nr
                              ORDER BY (nr.geom <-> st_endpoint(tr.geom))
                              LIMIT 1) nrfin;

create index idx_voisins_noeud_voisin
    on voisins (noeud, voisin);

create index idx_voisins_troncon_gid
    on voisins (troncon_gid);

create index idx_voisins_longueur
    on voisins (longueur);