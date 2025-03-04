-- Active: 1730390521538@@127.0.0.1@3306@libraryDB
CREATE TABLE Categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);


INSERT INTO Categories (name) VALUES
('Fiction'),
('Science'),
('History'),
('Technology'),
('Biography');


CREATE TABLE Books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    publisher VARCHAR(255),
    isbn VARCHAR(20) UNIQUE NOT NULL,
    category_id INT,
    quantity INT DEFAULT 1 CHECK (quantity >= 0),
    available_copies INT CHECK (available_copies >= 0),
    published_year INT,
    added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES Categories(category_id) ON DELETE SET NULL
);


INSERT INTO Books (title, author, publisher, isbn, category_id, quantity, available_copies, published_year)
VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', 'Scribner', '9780743273565', 1, 5, 5, 1925),
('To Kill a Mockingbird', 'Harper Lee', 'J.B. Lippincott & Co.', '9780061120084', 1, 6, 6, 1960),
('1984', 'George Orwell', 'Secker & Warburg', '9780451524935', 1, 8, 8, 1949),
('The Catcher in the Rye', 'J.D. Salinger', 'Little, Brown and Company', '9780316769488', 1, 7, 7, 1951),
('Moby-Dick', 'Herman Melville', 'Harper & Brothers', '9781503280786', 1, 4, 4, 1851),
('A Brief History of Time', 'Stephen Hawking', 'Bantam Books', '9780553380163', 2, 5, 5, 1988),
('The Selfish Gene', 'Richard Dawkins', 'Oxford University Press', '9780198788607', 2, 4, 4, 1976),
('Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 'Harper', '9780062316097', 3, 6, 6, 2011),
('Guns, Germs, and Steel', 'Jared Diamond', 'W.W. Norton & Company', '9780393354324', 3, 5, 5, 1997),
('The Art of War', 'Sun Tzu', 'Shambhala', '9781590302255', 3, 10, 10, -500),
('Clean Code', 'Robert C. Martin', 'Prentice Hall', '9780132350884', 4, 10, 10, 2008),
('Design Patterns', 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides', 'Addison-Wesley', '9780201633610', 4, 7, 7, 1994),
('Introduction to Algorithms', 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest, Clifford Stein', 'MIT Press', '9780262033848', 4, 5, 5, 2009),
('Steve Jobs', 'Walter Isaacson', 'Simon & Schuster', '9781451648539', 5, 3, 3, 2011),
('The Diary of a Young Girl', 'Anne Frank', 'Doubleday', '9780553296983', 5, 6, 6, 1947);


CREATE TABLE Members (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(15) UNIQUE,
    address TEXT,
    password VARCHAR(255) NOT NULL,
    membership_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Active', 'Inactive') DEFAULT 'Active'
);


INSERT INTO Members (name, email, phone, address, password, membership_date, status)
VALUES
('Brandon Morones', 'brandonm@montclair.edu', '5551112233', '123 Elm St, Cityville', SHA2('password123', 256), NOW(), 'Active'),
('Nayam Ahmed', 'nayama@montclair.edu', '5554446677', '456 Oak St, Townsville', SHA2('securepass456', 256), NOW(), 'Active'),
('Kelly Du', 'kellyd@montclair.edu', '5558889999', '789 Pine St, Villagetown', SHA2('mypassword789', 256), NOW(), 'Active'),
('Julia Eltarazy', 'juliae@montclair.edu', '5559876543', '789 Cedar Ave, Riverdale', SHA2('emmaStrongPass', 256), NOW(), 'Active'),
('Radlyn Hernandez', 'radlynh@montclair.edu', '5556543210', '159 Maple Rd, Lakeside', SHA2('jamesSecret', 256), NOW(), 'Inactive');


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
    status ENUM('Issued', 'Returned', 'Overdue') DEFAULT 'Issued',
    FOREIGN KEY (book_id) REFERENCES Books(book_id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES Members(member_id) ON DELETE CASCADE,
    FOREIGN KEY (librarian_id) REFERENCES Librarians(librarian_id) ON DELETE SET NULL
);
CREATE TABLE BorrowedBooks (
    borrowed_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    return_date DATE,
    status ENUM('Borrowed', 'Returned') DEFAULT 'Borrowed',
    FOREIGN KEY (member_id) REFERENCES Members(member_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES Books(book_id) ON DELETE CASCADE
);



INSERT INTO Transactions (book_id, member_id, librarian_id, issue_date, due_date, return_date, status)
VALUES
(1, 1, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), NULL, 'Issued'),
(2, 2, 2, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), NULL, 'Issued'),
(3, 1, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), CURDATE(), 'Returned'),
(4, 3, 2, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), NULL, 'Overdue');


CREATE TABLE Fines (
    fine_id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    amount DECIMAL(5,2) CHECK (amount >= 0),
    status ENUM('Paid', 'Unpaid') DEFAULT 'Unpaid',
    FOREIGN KEY (transaction_id) REFERENCES Transactions(transaction_id) ON DELETE CASCADE
);


INSERT INTO Fines (transaction_id, amount, status)
VALUES
(4, 5.00, 'Unpaid'),
(3, 0.00, 'Paid');