CREATE TABLE be_users (
	oauth2_clients int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_toujou_oauth2_server_client (
	identifier varchar(32) DEFAULT '' NOT NULL,
	user_uid int(11) DEFAULT '0' NOT NULL,
	user_table varchar(8) DEFAULT '' NOT NULL,
    name varchar(255) DEFAULT '' NOT NULL,
    secret varchar(100) DEFAULT '' NOT NULL,
    redirect_uris text,
    description text
);

CREATE TABLE tx_toujou_oauth2_server_access_token (
	identifier varchar(255) DEFAULT '' NOT NULL,
    revoked datetime DEFAULT NULL,
    client_id varchar(32) DEFAULT '' NOT NULL,
    expiry_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    scopes varchar(255) DEFAULT '' NOT NULL,

    PRIMARY KEY (identifier)
);
