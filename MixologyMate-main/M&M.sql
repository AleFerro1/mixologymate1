create database if not exists mixologymate;
use mixologymate;


create table if not exists utenti(
	nickname varchar(30) primary key not null,
	password varchar(255) not null,
	immagine varchar(200)
);
 
create table if not exists drink(
	idDrink int(5) primary key auto_increment not null,
	creatore varchar(30),
	immagine varchar(200),
    dataCreazione date,
	foreign key (creatore) references utenti(nickname)
); 

create table if not exists gestioneDrink(
	nome varchar(20) primary key not null,
	descrizione varchar(250) not null,
    idDrink int(5) not null,
    foreign key (idDrink) references drink(idDrink)
);

create table if not exists recensioni(
	idRecensione int(5) primary key auto_increment not null,
	descrizione varchar(250) not null,
	nicknameCreatore varchar(30),
	numeroStelle int(1) not null,
    dataCreazione date,
	idDrink int(5) not null,
	foreign key (nicknameCreatore) references utenti(nickname),
	foreign key (idDrink) references drink(idDrink)
); 

create table if not exists ingredienti(
	idIngrediente int(2) primary key auto_increment not null,
	nomeIngrediente varchar(50) not null
); 

create table if not exists associazioneIngredienti(
	idDrink int(5),
	idIngrediente int(3),
	idQuantità varchar(10) not null,
	foreign key (idIngrediente) references ingredienti(idIngrediente),
	foreign key (idDrink) references drink(idDrink)
);

create table if not exists preferiti(
	idDrink int(5),
	nickname varchar(30), 
	foreign key (nickname) references utenti(nickname),
	foreign key (idDrink) references drink(idDrink)
);


