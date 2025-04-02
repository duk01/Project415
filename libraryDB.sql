-- Active: 1730390521538@@127.0.0.1@3306@libraryDB

-- Create Members table
CREATE TABLE Members (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(15) UNIQUE,
    address TEXT,
    password VARCHAR(255) NOT NULL,
    membership_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Create Books table
CREATE TABLE Books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    google_id VARCHAR(255) UNIQUE,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    publisher VARCHAR(255),
    published_date VARCHAR(20),
    page_count INT,
    categories TEXT,
    description TEXT,
    thumbnail_url TEXT,
    availability ENUM('available', 'unavailable') DEFAULT 'available'
) ENGINE=InnoDB;

-- Create BorrowedBooks table with foreign keys to Members and Books
CREATE TABLE BorrowedBooks (
    borrow_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES Members(member_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES Books(book_id) ON DELETE CASCADE
) ENGINE=InnoDB;


INSERT INTO Members (name, email, phone, address, password, membership_date)
VALUES
('Brandon Morones', 'brandonm@montclair.edu', '5551112233', '123 Elm St, Cityville', SHA2('password123', 256), NOW()),
('Nayam Ahmed', 'nayama@montclair.edu', '5554446677', '456 Oak St, Townsville', SHA2('securepass456', 256), NOW()),
('Kelly Du', 'kellyd@montclair.edu', '5558889999', '789 Pine St, Villagetown', SHA2('mypassword789', 256), NOW()),
('Julia Eltarazy', 'juliae@montclair.edu', '5559876543', '789 Cedar Ave, Riverdale', SHA2('emmaStrongPass', 256), NOW()),
('Radlyn Hernandez', 'radlynh@montclair.edu', '5556543210', '159 Maple Rd, Lakeside', SHA2('jamesSecret', 256), NOW());


CREATE TABLE Librarians (
    librarian_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,  -- Added password field
    joined_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO Librarians (name, email, password, joined_date)
VALUES
('John Doe', 'johndoe@montclair.edu', SHA2('JohnSecurePass', 256), NOW()),
('Alice Smith', 'alicesmith@montclair.edu', SHA2('AliceStrongPass', 256), NOW());


CREATE TABLE Transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    member_id INT NOT NULL,
    librarian_id INT,
    issue_date DATE DEFAULT (CURDATE()),  -- FIX for MySQL in Docker
    due_date DATE NOT NULL,
    return_date DATE NULL,
    borrowStatus ENUM('Issued', 'Returned', 'Overdue') DEFAULT 'Issued',
    FOREIGN KEY (book_id) REFERENCES Books(book_id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES Members(member_id) ON DELETE CASCADE,
    FOREIGN KEY (librarian_id) REFERENCES Librarians(librarian_id) ON DELETE SET NULL
);

INSERT INTO Transactions (book_id, member_id, librarian_id, issue_date, due_date, return_date, borrowStatus)
VALUES
(1, 1, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), NULL, 'Issued'),
(2, 2, 2, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), NULL, 'Issued'),
(3, 1, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), CURDATE(), 'Returned'),
(4, 3, 2, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), NULL, 'Overdue');


CREATE TABLE Fines (
    fine_id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    amount DECIMAL(5,2) CHECK (amount >= 0),
    fineStatus ENUM('Paid', 'Unpaid') DEFAULT 'Unpaid',
    FOREIGN KEY (transaction_id) REFERENCES Transactions(transaction_id) ON DELETE CASCADE
);


INSERT INTO Fines (transaction_id, amount, fineStatus)
VALUES
(4, 5.00, 'Unpaid'),
(3, 0.00, 'Paid');
