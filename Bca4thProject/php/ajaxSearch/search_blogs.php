<?php
include 'connection.php';

// Get the search query from the request
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($searchQuery)) {
  // Sanitize and prepare the SQL query to search blogs by title or description
  $searchQuery = $conn->real_escape_string($searchQuery);
  $sql = "SELECT * FROM blog_information 
          WHERE title LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%' 
          ORDER BY upload_date DESC";
  $result = $conn->query($sql);

  $data = [];
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
  }

  // Return the results as JSON
  header('Content-Type: application/json');
  echo json_encode($data);
} else {
  // Return an empty response if no query is provided
  echo json_encode([]);
}

$conn->close();
?>
