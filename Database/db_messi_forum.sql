CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255),
    registration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'blocked') DEFAULT 'active'
);


-- Categories Table
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    parent_category_id INT NULL,
    FOREIGN KEY (parent_category_id) REFERENCES categories(category_id)
);

-- Threads Table
CREATE TABLE threads (
    thread_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    user_id INT,
    category_id INT,
    creation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

-- Posts Table (updated with 'title' and 'image' fields)
CREATE TABLE posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    thread_id INT,
    user_id INT,
    title VARCHAR(255) NOT NULL, -- Added title column
    content TEXT,
    image VARCHAR(255), -- Added image column (nullable)
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    vote_count INT DEFAULT 0,
    FOREIGN KEY (thread_id) REFERENCES threads(thread_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Comments Table
CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    content TEXT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(post_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Admins Table
CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    role ENUM('superadmin', 'moderator') DEFAULT 'moderator',
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Admin Actions Table
CREATE TABLE admin_actions (
    action_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT,
    action_type VARCHAR(255) NOT NULL,
    action_description TEXT,
    action_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admins(admin_id)
);

-- Votes Table
CREATE TABLE votes (
    vote_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    vote_type INT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(post_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    UNIQUE INDEX user_post_unique (user_id, post_id)
);


-- Full-Text Indexes (optional for improved search capabilities)
ALTER TABLE posts ADD FULLTEXT(content);
ALTER TABLE threads ADD FULLTEXT(title);

INSERT INTO admins (user_id, role) VALUES (1, 'moderator');
ALTER TABLE users AUTO_INCREMENT = 1;

ALTER TABLE users
CHANGE COLUMN profile_pic profile_pic_blob BLOB;

ALTER TABLE users ADD COLUMN profile_pic_type VARCHAR(50);

ALTER TABLE posts ADD COLUMN category_id INT(11);



ALTER TABLE threads ADD body TEXT;

ALTER TABLE posts ADD is_hidden TINYINT(1) DEFAULT 0 NOT NULL;
ALTER TABLE threads ADD COLUMN is_hidden TINYINT(1) NOT NULL DEFAULT 0;

INSERT INTO categories (name, description) VALUES ('WeLoveMESSI', 'A category dedicated to Lionel Messi fans.');
INSERT INTO categories (name, description) VALUES ('MessiGOATArgument', 'A category for debating why Messi is considered the Greatest Of All Time.');
INSERT INTO categories (name, description) VALUES ('WhyMessitheGOAT', 'A category for sharing reasons why Messi is the best.');