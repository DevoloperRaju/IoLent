CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    userid BIGINT UNIQUE NOT NULL,

    status VARCHAR(50) DEFAULT '0',
    active_robot INT DEFAULT 0,
    deactive_robot INT DEFAULT 0,

    total_mining_income DECIMAL(15,6) DEFAULT 0,
    total_mining_collected INT DEFAULT 0,
    last_mining_collect TIMESTAMP NULL DEFAULT NULL,

    total_referral_income DECIMAL(15,6) DEFAULT 0,
    others_income DECIMAL(15,6) DEFAULT 0,

    total_withdrawal DECIMAL(15,6) DEFAULT 0,
    total_deposit DECIMAL(15,6) DEFAULT 0,

    total_point_transfer DECIMAL(15,6) DEFAULT 0,
    total_point_convert DECIMAL(15,6) DEFAULT 0,

    achievement VARCHAR(50),
    account_created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    pin VARCHAR(4) DEFAULT NULL,

    -- এখানে TEXT এর বদলে VARCHAR(500) ব্যবহার করা হয়েছে
    image VARCHAR(500) DEFAULT 'https://i.ibb.co.com/Y7pnhBVt/IMG-20240730-221052-1.jpg',
    
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,

    address_id INT,
    phone_number VARCHAR(20) UNIQUE,

    taka DECIMAL(15,6) DEFAULT 0,
    point DECIMAL(15,6) DEFAULT 0,

    current_cycle_earn DECIMAL(15,6) DEFAULT 0,
    login_streake INT DEFAULT 0,

    lcr_buy_robot INT DEFAULT 0,
    lcr_invite_freinds INT DEFAULT 0,

    tc_point_transfer INT DEFAULT 0,
    lcr_point_transfer INT DEFAULT 0,

    tc_withdrawal INT DEFAULT 0,
    lcr_withdrawal INT DEFAULT 0,

    tc_deposit INT DEFAULT 0,
    lcr_deposit INT DEFAULT 0,

    last_mining_earn DECIMAL(10,6) DEFAULT 0,
    my_position INT DEFAULT 0,

    daily_bonus_day DATE,
    total_refer INT DEFAULT 0,
    total_gen_refer TEXT,

    last_changed_pin BIGINT,
    last_login BIGINT,

    insentive DECIMAL(15,6) DEFAULT 0,
    dob DATE,
    upline INT DEFAULT 10000,

    proffesion VARCHAR(100),
    gender ENUM('male', 'female', 'other'),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);