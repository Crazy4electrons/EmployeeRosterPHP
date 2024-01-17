<?php
/**
 * data_operations.php
 * Handles storing, manipulating, and retrieving data from database tables.
 */

require_once('db_connection.php');

/**
 * Inserts data into the specified table.
 * 
 * @param string $table   The name of the table to insert data into.
 * @param array $data     An associative array of data to insert.
 * @return string         Success or error message.
 */
function insertData($table, $data) {
    try {
        $pdo = connectToDatabase('localhost', 'mydatabase', 'your_username', 'your_password');
        
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $stmt = $pdo->prepare("INSERT INTO $table ($columns) VALUES ($values)");

        $index = 1;
        foreach ($data as $value) {
            $stmt->bindValue($index++, $value);
        }

        $stmt->execute();
        
        return "Data inserted successfully!";
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

// Additional functions for updating, deleting, and retrieving data can be added here.
?>
