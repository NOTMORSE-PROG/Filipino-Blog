FILIPINO BLOG DB:


CREATE DATABASE FilipinoBlog;

USE FilipinoBlog;

CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    fullName VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE user_profiles (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) DEFAULT NULL,
    picture_path VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    PRIMARY KEY (id),
    KEY user_id (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE posts (
    id INT(11) NOT NULL AUTO_INCREMENT,  
    user_id INT(11) DEFAULT NULL, 
    title VARCHAR(255) NOT NULL,  
    content TEXT DEFAULT NULL,  
    category VARCHAR(255) DEFAULT NULL, 
    tags VARCHAR(255) DEFAULT NULL, 
    featured_image VARCHAR(255) DEFAULT NULL, 
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,  
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  
    PRIMARY KEY (id),  -- Primary key
    KEY user_id (user_id)  -- Index for user_id
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE comments (
    id INT(11) NOT NULL AUTO_INCREMENT,
    post_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0,
    is_deleted TINYINT(1) DEFAULT 0,
    PRIMARY KEY (id),
    KEY post_id (post_id),
    KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

