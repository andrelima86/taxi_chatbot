drop table if exists tbl_users;
create table tbl_users(
	user_id int(11) unsigned not null primary key auto_increment,
	user_fb_id varchar(50) not null,
	user_phone varchar(20) null,
	user_address varchar(100) null
);

drop table if exists tbl_intents;
create table tbl_intents(
	intent_id int(11) unsigned not null primary key auto_increment,
	intent_name varchar(50) not null,
	intent_params varchar(255) null
);

drop table if exists tbl_responses;
create table tbl_responses(
	response_id int(11) unsigned not null primary key auto_increment,
	response_name varchar(50) not null,
	response_template varchar(255) not null
);

drop table if exists tbl_messages;
create table tbl_messages(
	message_id int(11) unsigned not null primary key auto_increment,
	message_fb_id varchar(50) not null,
	message_text text not null,
	message_payload text null,
	message_timestamp datetime not null default current_timestamp
);

drop table if exists tbl_sessions;
create table tbl_sessions(
	session_id varchar(50) not null primary key,
	seesion_created datetime not null default current_timestamp,
	session_data varchar(20000) not null,
	session_closed enum('TRUE', 'FALSE')
);

drop table if exists tbl_user_messages;
create table tbl_user_messages(
	user_id int(11) unsigned not null,
	message_id int(11) unsigned not null,
	primary key(user_id, message_id),
	foreign key (user_id) references tbl_users(user_id) on delete cascade on update cascade,
	foreign key (message_id) references tbl_messages(message_id) on delete cascade on update cascade
);

drop table if exists tbl_intent_responses;
create table tbl_intent_responses(
	intent_id int(11) unsigned not null,
	response_id int(11) unsigned not null,
	primary key(intent_id, response_id),
	foreign key (intent_id) references tbl_intents(intent_id) on delete cascade on update cascade,
	foreign key (response_id) references tbl_responses(response_id) on delete cascade on update cascade
);

drop table if exists tbl_session_messages;
create table tbl_session_messages(
	session_id varchar(50) not null,
	message_id int(11) unsigned not null,
	primary key(session_id, message_id),
	foreign key (session_id) references tbl_sessions(session_id) on delete cascade on update cascade,
	foreign key (message_id) references tbl_messages(message_id) on delete cascade on update cascade
);


/*intents: greeting, taxi_request, waiting_for_taxi, abuse, quotation*/
insert into tbl_intents (intent_name, intent_params)
	values ('greeting', '');
insert into tbl_intents (intent_name, intent_params)
	values ('taxi_request', "{'to', 'from', 'when'}");				

/*response templates*/

/*greeting intent*/
insert into tbl_responses (response_name, response_template)
	values ('greeting', "{'Where can Alfred take you today?'}");

insert into tbl_responses (response_name, response_template)
	values ('greeting', "{'Hello (contact), where would you like to go?'}");

insert into tbl_responses (response_name, response_template)
	values ('greeting', "{'Hey (contact) where to?'}");

insert into tbl_responses (response_name, response_template)
	values ('greeting', "{Where are we off to now (contact)'}");

insert into tbl_responses (response_name, response_template)
	values ('greeting', "{'Hi (contact)'}");

insert into tbl_responses (response_name, response_template)
	values ('greeting', "{'Hello (contact)'}");	

insert into tbl_responses (response_name, response_template)
	values ('greeting', "{'What's up (contact)?'}");

insert into tbl_responses (response_name, response_template)
	values ('greeting', "{'How can Alfred help you today?'}");	

/*taxi request*/
insert into tbl_responses (response_name, response_template)
	values ('taxi_request_to', "{'Where can we pick you up, send me your location'}");

insert into tbl_responses (response_name, response_template)
	values ('taxi_request_to', "{'Where are you right now, send me your location'}");	
	
insert into tbl_responses (response_name, response_template)
	values ('taxi_request_to', "{'Send me your location'}");	

insert into tbl_intents (intent_name, intent_params)
	values ('taxi_request_from', "{'You would like to go to (location)?'}");	

insert into tbl_responses (response_name, response_template)
	values ('taxi_request_when', "{'Where can Alfred take you today?'}");

insert into tbl_intents (intent_name, intent_params)
	values ('taxi_request_confirm', 'Where are you right now?');
			
