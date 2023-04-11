CREATE EXTENSION postgis;

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

alter table noeud_commune
    owner to postgres;

create table noeud_routier(
                              gid        integer,
                              id_rte500  varchar(24),
                              nature     varchar(80),
                              insee_comm varchar(5),
                              geom       geometry(Point, 4326)
);

alter table noeud_routier
    owner to postgres;

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

alter table troncon_route
    owner to postgres;

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

alter table utilisateur
    owner to postgres;

insert into utilisateur (login, nom, prenom, mdp_hache, est_admin, email, email_a_valider, nonce)
values (1, 'Waps', 'Developpeur', '', '0', 'dev@waps.fr', '', '');