<?php
session_start();
require_once __DIR__ . '/config/db_connect.php';

$results = [];
if (isset($_GET['mobile_no'])) {
    $mobile_no = mysqli_real_escape_string($conn, $_GET['mobile_no']);
    
    $sql = "SELECT * FROM property_records WHERE mobile_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $mobile_no);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Property Records</title>
    <link href="./src/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                        url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .container-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }

        .search-form {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .search-form:hover {
            transform: translateY(-5px);
        }

        .search-input {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .page-title {
            color: #1e40af;
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="p-6">
    <div class="max-w-4xl mx-auto container-glass">
        <h1 class="page-title">Property Records Search</h1>
        <!-- Add the navigation buttons -->
        <!-- Update the navigation buttons section -->
        <div class="flex justify-between mb-6">
            <a href="add_property.php" class="bg-gradient-to-r from-emerald-400 to-emerald-600 text-white px-6 py-2 rounded flex items-center justify-center transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                <i class="fas fa-plus-circle mr-2"></i>
                <span class="font-semibold">Add New Property</span>
            </a>
            <a href="index.html" class="bg-gradient-to-r from-sky-400 to-blue-600 text-white px-6 py-2 rounded flex items-center justify-center transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                <i class="fas fa-home mr-2"></i>
                <span class="font-semibold">Back to Dashboard</span>
            </a>
        </div>

        <form method="GET" class="bg-white p-6 rounded-lg shadow-md mb-6">
            <div class="flex gap-4">
                <input type="text" name="mobile_no" placeholder="Enter Mobile Number" 
                       class="flex-1 p-2 border rounded" required>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Search Records
                </button>
            </div>
        </form>

        <style>
            .search-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 1rem;
            }
            .search-table th {
                background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
                color: white;
                padding: 1rem;
                text-align: left;
            }
            .search-table td {
                padding: 1rem;
                border-bottom: 1px solid #e5e7eb;
            }
            .search-table tr:hover {
                background-color: #f8fafc;
            }
            .search-table tr:nth-child(even) {
                background-color: #f9fafb;
            }
        </style>

        <!-- Replace the existing results display with this table structure -->
        <?php if (!empty($results)): ?>
            <div class="bg-white rounded-lg shadow-md p-6 overflow-x-auto">
                <h2 class="text-xl font-bold mb-4">Search Results</h2>
                <table class="search-table">
                    <thead>
                        <tr>
                            <th>MC Type</th>
                            <th>Owner Name</th>
                            <th>Father/Husband Name</th>
                            <th>Mobile No.</th>
                            <th>Location</th>
                            <th>Address</th>
                            <th>Floor</th>
                            <th>Part</th>
                            <th>Length</th>
                            <th>Width</th>
                            <th>Use</th>
                            <th>Occupancy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $property): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($property['mc_type']); ?></td>
                                <td><?php echo htmlspecialchars($property['owner_name']); ?></td>
                                <td><?php echo htmlspecialchars($property['father_name']); ?></td>
                                <td><?php echo htmlspecialchars($property['mobile_no']); ?></td>
                                <td><?php echo htmlspecialchars($property['location']); ?></td>
                                <td><?php echo htmlspecialchars($property['address']); ?></td>
                                <td><?php echo htmlspecialchars($property['floor']); ?></td>
                                <td><?php echo htmlspecialchars($property['part']); ?></td>
                                <td><?php echo htmlspecialchars($property['length']); ?></td>
                                <td><?php echo htmlspecialchars($property['width']); ?></td>
                                <td><?php echo htmlspecialchars($property['property_use']); ?></td>
                                <td><?php echo htmlspecialchars($property['occupancy']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
