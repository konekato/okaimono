<?php
    require_once('../config/config.php');
    
    try {
        $pdo = new PDO(DSN, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $pdo->exec("create table if not exists users(
                    id int not null auto_increment primary key,
                    username varchar(255) not null,
                    password varchar(255) not null,
                    created_at timestamp not null default current_timestamp
                    )");
                    
        $pdo->exec("create table if not exists things(
                    id int not null auto_increment primary key,
                    name varchar(255) not null,
                    amount int not null default 1,
                    unit varchar(255),
                    detail TEXT,
                    deadline date,
                    created_at timestamp not null default current_timestamp,
                    is_done BOOLEAN not null default false,
                    user_id int,
                    CONSTRAINT fk_user_id
                        FOREIGN KEY (user_id) 
                        REFERENCES users (id)
                        ON DELETE SET NULL ON UPDATE CASCADE
                    )");
                    
                    
    } catch (Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }
?>