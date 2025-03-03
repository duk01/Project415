# Library Management System Testing

## 1. Unit Testing

### Test Case 1: Database Connection
- **Test Case ID**: UT-001  
- **Test Description**: Ensure the system can establish a connection to the database.    
- **Test Steps**:  
  1. Ensure database connection parameters are correct.  
  2. Click on the "View Books" button on the homepage.  
  3. Refresh the page.  
- **Expected Result**: The page loads without errors, and books are displayed.  
- **Actual Result**: The page loaded without any errors, and the books with their correct description and search bar are displayed.  
- **Pass/Fail**: Pass  

## 2. Integration Testing

### Test Case 2: Search Functionality
- **Test Case ID**: IT-001  
- **Test Description**: Verify that users can search for books using the search bar.  
- **Test Steps**:  
  1. Navigate to the book catalog page by clicking on the "View Books" button on homepage.  
  2. Enter a book title, author, or publisher in the search bar. (The Great Gatsby)  
  3. Click the "Search" button.  
- **Expected Result**: The search results display books matching the search query.  
- **Actual Result**: The correct book appear in the results.  
- **Pass/Fail**: Pass  

## 3. Functional Testing

### Test Case 3: View Available Books
- **Test Case ID**: FT-001  
- **Test Description**: Ensure that users can see a list of available books.  
- **Test Steps**:  
  1. Navigate to the book catalog page by clicking on the "View Books" button on homepage.  
  2. Check if books are displayed with their title, author, publisher,year, and available copies.  
- **Expected Result**: The books are displayed with their title, author, publisher, year, and available copies.  
- **Actual Result**: The books are correctly displayed with their title, author, publisher, year, and available copies.  
- **Pass/Fail**: Pass  

## 4. Performance Testing

### Test Case 4: Page Load Speed
- **Test Case ID**: PT-001  
- **Test Description**: Check how quickly the book catalog page loads.  
- **Test Steps**:  
  1. Reload the book catalog page multiple times.  
  2. Measure the time the page takes to reload.  
- **Expected Result**: The page should load quickly.  
- **Actual Result**: The page correctly loaded quicky without any errors.  
- **Pass/Fail**: Pass  

## 5. Stress Testing

### Test Case 5: High User Load
- **Test Case ID**: ST-001  
- **Test Description**: Evaluate system performance under high concurrent users.  
- **Test Steps**:  
  1. Use a load-testing tool to simulate multiple users accessing the system.(Used JMeter)  
  2. Monitor response times and server stability.  
- **Expected Result**: The system remains stable under high load.  
- **Actual Result**: The system remained stable.  
- **Pass/Fail**: Pass  

## 6. Security Testing

### Test Case 6: SQL Injection Prevention
- **Test Case ID**: SEC-001  
- **Test Description**: Ensure the system is protected against SQL injection.  
- **Test Steps**:  
  1. Entering SQL commands in the search bar such as `' OR 1=1 --` or `'; DROP TABLE users; --`.  
  2. Check if unexpected behavior occurs.  
- **Expected Result**: The system should not execute any unauthorized or unexpected behavior.  
- **Actual Result**: The system did not execute any unexpected behavior.
- **Pass/Fail**: Pass  