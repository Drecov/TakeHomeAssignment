CREATE TABLE IF NOT EXISTS account (
            id VARCHAR(100) PRIMARY KEY,
            acc_number INT NOT NULL UNIQUE,
            balance DECIMAL(10,2) NOT NULL
        ) ENGINE=InnoDB;