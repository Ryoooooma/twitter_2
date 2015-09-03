create database twitter_2;


use twitter_2;


create table users (
	id int primary key auto_increment not null,
	user_name varchar(255) not null,
	password varchar(255) not null,
	created datetime,
	modified datetime
);


create table posts (
	id int primary key auto_increment not null,
	user_id int not null,
	body varchar(255),
	created datetime
);


create table followings (
	user_id int not null,
	following_id int not null,
	primary key (`user_id`, `following_id`)
);