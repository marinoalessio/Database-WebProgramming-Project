CREATE TABLE users (
    id integer primary key auto_increment,
    username varchar(16) not null unique,
    password varchar(255) not null,
    email varchar(255) not null unique,
    name varchar(255) not null,
    surname varchar(255) not null,
    avatar varchar(255),
    since timestamp not null default current_timestamp,
    nfollowing integer default 0,
	nreviews integer default 0
)Engine = InnoDB;

CREATE TABLE cookies(
    id integer auto_increment primary key,
    hash varchar(255) not null,
    user integer not null,
    expires bigint not null,
    foreign key(user) references users(id) on delete cascade on update cascade
)Engine = InnoDB;

create table artworks(
    id integer primary key, -- già è identificativo unico
    title varchar(255) not null,
    artists varchar(255) not null,
    img varchar(255) not null,
    publication_year smallint not null, 
    place_of_origin varchar(255) not null,
    description varchar(255) not null,
    category varchar(255) not null -- department_title
)Engine = InnoDB;

create table reviews(
    user_id integer,
    artwork_id integer,
    publication_date timestamp not null default current_timestamp,
    stars tinyint not null,
    body varchar(750) not null,
    n_likes integer default 0,
    foreign key(user_id) references users(id) on delete cascade on update cascade,
    foreign key(artwork_id) references artworks(id) on delete cascade on update cascade,
    primary key(user_id, artwork_id)
)Engine = InnoDB;

create table likes(
    user_review integer,
    artwork_review integer,
    user_id integer,
    foreign key(user_review, artwork_review) references reviews(user_id, artwork_id) on delete cascade on update cascade,
    primary key(user_review, artwork_review, user_id)
)Engine = InnoDB;

DELIMITER //
CREATE TRIGGER likes_trigger
AFTER INSERT ON likes
FOR EACH ROW
BEGIN
UPDATE reviews 
SET n_likes = n_likes + 1
WHERE user_id = new.user_review and artwork_id = new.artwork_review;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER unlikes_trigger
AFTER DELETE ON likes
FOR EACH ROW
BEGIN
UPDATE reviews 
SET n_likes = n_likes - 1
WHERE user_id = old.user_review and artwork_id = old.artwork_review;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER reviews_trigger
AFTER INSERT ON reviews
FOR EACH ROW
BEGIN
UPDATE users 
SET nreviews = nreviews + 1
WHERE id = new.user_id;
END //
DELIMITER ;

create table guides(
    cf varchar(255) primary key,
    name varchar(255),
    surname varchar(255),
    qualification varchar(255),
    img varchar(255)
)Engine = InnoDB;

create table directors(
    cf varchar(255) primary key,
    name varchar(255),
    surname varchar(255),
    qualification varchar(255),
    img varchar(255)
)Engine = InnoDB;

create table events(
    id integer primary key auto_increment,
    title varchar(255) not null,
    date_and_time datetime not null,
    duration integer, -- time in seconds
    tags varchar(750),
    cover varchar(255),
    director varchar(255),
    guide varchar(255),
    foreign key(director) references directors(cf) on delete cascade on update cascade,
    foreign key(guide) references guides(cf) on delete cascade on update cascade,
    unique(title, date_and_time)
)Engine = InnoDB;

create table highlight(
    id_event integer,
    id_user integer,
    since timestamp not null default current_timestamp,
    foreign key(id_event) references events(id) on delete cascade on update cascade,
    foreign key(id_user) references users(id) on delete cascade on update cascade,
    primary key(id_event, id_user)
)Engine = "InnoDB"

create table subscription(
    user_id integer,
    cf_director varchar(255),
    since timestamp not null default current_timestamp,
    foreign key(user_id) references users(id),
    foreign key(cf_director) references directors(cf),
    primary key(user_id, cf_director)
)Engine = InnoDB;

DELIMITER //
CREATE TRIGGER follow_trigger
AFTER INSERT ON subscription
FOR EACH ROW
BEGIN
UPDATE users 
SET nfollowing = nfollowing + 1
WHERE id = new.user_id;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER unfollow_trigger
AFTER DELETE ON subscription
FOR EACH ROW
BEGIN
UPDATE users 
SET nfollowing = nfollowing - 1
WHERE id = old.user_id;
END //
DELIMITER ;

