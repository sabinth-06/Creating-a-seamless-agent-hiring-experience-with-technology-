<?php
session_start();

// Simplified session check
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'add_property.php';
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property - LandTax Portal</title>
    <link href="./src/output.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f6f8fc 0%, #e9f0f7 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .form-container {
            max-width: 900px !important;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem !important;
            border-radius: 1.5rem !important;
        }

        .form-input {
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            background: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            padding: 0.75rem 1rem !important;
            border-radius: 0.75rem !important;
        }
        
        .form-input:focus {
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: translateY(-1px);
            background: white;
        }

        .action-button {
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .back-button {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
        }
        
        .submit-button {
            background: linear-gradient(135deg, #34d399 0%, #059669 100%);
            padding: 1rem 3rem !important;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.3);
        }

        .form-section {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
            background: rgba(255, 255, 255, 0.5);
            padding: 1.5rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-header {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: block;
        }

        select, input, textarea {
            font-size: 0.95rem !important;
            color: #1f2937;
        }

        .grid {
            gap: 1.5rem !important;
        }

        h2, h3 {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="form-container mx-auto bg-white rounded-xl shadow-lg p-6">
        <?php
        // Removed duplicate session_start() from here
        if (isset($_SESSION['success_message'])) {
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">' . 
                    $_SESSION['success_message'] . 
                 '</div>';
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">' . 
                    $_SESSION['error_message'] . 
                 '</div>';
            unset($_SESSION['error_message']);
        }
        ?>
        <div class="flex justify-between items-center mb-4 form-section">
            <h2 class="text-5xl font-bold text-gray-800" style="flex: 1;">Add New Property</h2>
            <a href="search_property.php" class="action-button back-button text-white flex items-center justify-center" style="min-width: 140px;">
                <span>Back to Search</span>
            </a>
        </div>

        <form action="save_property.php" method="POST" class="space-y-6">
            <div class="grid grid-cols-2 gap-6 form-section" style="animation-delay: 0.2s">
                <div>
                    <label class="block mb-2 text-gray-700">Select MC</label>
                    <select name="mc_type" class="w-full p-2 rounded form-input" required>
                        <option value="">Select MC Type</option>
                        <option value="MC Thang">MC Thang</option>
                        <option value="MC Thang">MC Modi</option>
                        <option value="MC Thang">MC Ram bilas mehto</option>
                        <option value="MC Thang">MC Thakkar</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-gray-700">Owner Name</label>
                    <input type="text" name="owner_name" class="w-full p-2 rounded form-input" required>
                </div>

                <div>
                    <label class="block mb-2 text-gray-700">Father/Husband Name</label>
                    <input type="text" name="father_name" class="w-full p-2 rounded form-input" required>
                </div>

                <div>
                    <label class="block mb-2 text-gray-700">Mobile No.</label>
                    <input type="tel" name="mobile_no" pattern="[0-9]{10}" class="w-full p-2 rounded form-input" required>
                </div>

                <div>
                    <label class="block mb-2 text-gray-700">Location</label>
                    <input type="text" name="location" class="w-full p-2 rounded form-input" required>
                </div>

                <div>
                    <label class="block mb-2 text-gray-700">Address</label>
                    <textarea name="address" class="w-full p-2 rounded form-input" required></textarea>
                </div>
            </div>

            <div class="border-t pt-6 form-section" style="animation-delay: 0.4s">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Floor Wise Measurement</h3>
                <div class="grid grid-cols-6 gap-4">
                    <div>
                        <label class="block mb-2 text-gray-700">Floor</label>
                        <select name="floor" class="w-full p-2 rounded form-input" required>
                            <option value="Floor-1">Floor-1</option>
                            <option value="Floor-2">Floor-2</option>
                            <option value="Floor-3">Floor-3</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-gray-700">Part</label>
                        <input type="text" name="part" class="w-full p-2 rounded form-input" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-gray-700">Length (Mtrs)</label>
                        <input type="number" step="0.01" name="length" class="w-full p-2 rounded form-input" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-gray-700">Width (Mtrs)</label>
                        <input type="number" step="0.01" name="width" class="w-full p-2 rounded form-input" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-gray-700">Use</label>
                        <select name="property_use" class="w-full p-2 rounded form-input" required>
                            <option value="Residential">Residential</option>
                            <option value="Commercial">Commercial</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-gray-700">Occupancy</label>
                        <select name="occupancy" class="w-full p-2 rounded form-input" required>
                            <option value="By Owner">By Owner</option>
                            <option value="Rented">Rented</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="text-center pt-6 form-section" style="animation-delay: 0.6s">
                <button type="submit" class="bg-green-500 text-white px-8 py-3 rounded-lg hover:bg-green-600 submit-button">
                    Save Property
                </button>
            </div>
        </form>
    </div>
</body>
</html>

<style>
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>

<?php if (isset($_SESSION['alert'])): ?>
    <div style="<?php echo $_SESSION['alert']['style']; ?>" id="alertMessage">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>
            <span style="font-weight: 500;"><?php echo $_SESSION['alert']['message']; ?></span>
            <button onclick="this.parentElement.parentElement.style.display='none'" 
                    style="margin-left: 1rem; opacity: 0.8; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('alertMessage').style.display = 'none';
        }, 5000);
    </script>
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>