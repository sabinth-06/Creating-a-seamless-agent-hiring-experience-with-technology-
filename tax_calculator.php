<?php
session_start();
require_once __DIR__ . '/config/db_connect.php';

// Tax rate constants (you can modify these based on your requirements)
define('RESIDENTIAL_RATE', 0.015); // 1.5% for residential
define('COMMERCIAL_RATE', 0.025);  // 2.5% for commercial
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Tax Calculator</title>
    <link href="./src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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

        .calculator-form {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .result-card {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }

        .result-card.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="p-6">
    <div class="max-w-4xl mx-auto container-glass">
        <h1 class="text-3xl font-bold text-center text-blue-900 mb-8">Property Tax Calculator</h1>

        <div class="flex justify-between mb-6">
            <a href="index.html" class="bg-gradient-to-r from-sky-400 to-blue-600 text-blue-200 px-6 py-2 rounded flex items-center justify-center transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                <i class="fas fa-home mr-2"></i>
                <span class="font-semibold ">Back to Dashboard</span>
            </a>
        </div>

        <div class="calculator-form p-6 mt-3">
            <form id="taxCalculator" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Property Type</label>
                        <select id="propertyType" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400">
                            <option value="residential">Residential</option>
                            <option value="commercial">Commercial</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Land Area (sq. meters)</label>
                        <input type="number" id="landArea" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Base Rate (per sq. meter)</label>
                        <input type="number" id="baseRate" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Property Age (years)</label>
                        <input type="number" id="propertyAge" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400" required>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 text-white px-8 py-4 rounded-lg hover:bg-blue-600 transition-all transform hover:scale-105">
                        Calculate Tax
                    </button>
                </div>
            </form>
        </div>

        <div id="resultCard" class="result-card hidden">
            <h2 class="text-2xl font-bold mb-4">Tax Calculation Results</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-200">Property Value:</p>
                    <p id="propertyValue" class="text-2xl font-bold"></p>
                </div>
                <div>
                    <p class="text-gray-200">Annual Tax Amount:</p>
                    <p id="taxAmount" class="text-2xl font-bold"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('taxCalculator').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const propertyType = document.getElementById('propertyType').value;
            const landArea = parseFloat(document.getElementById('landArea').value);
            const baseRate = parseFloat(document.getElementById('baseRate').value);
            const propertyAge = parseFloat(document.getElementById('propertyAge').value);

            // Calculate property value
            let propertyValue = landArea * baseRate;

            // Apply depreciation based on property age (1% per year, max 20%)
            const depreciation = Math.min(propertyAge * 0.01, 0.20);
            propertyValue = propertyValue * (1 - depreciation);

            // Calculate tax based on property type
            const taxRate = propertyType === 'residential' ? <?php echo RESIDENTIAL_RATE; ?> : <?php echo COMMERCIAL_RATE; ?>;
            const taxAmount = propertyValue * taxRate;

            // Display results
            document.getElementById('propertyValue').textContent = '₹' + propertyValue.toLocaleString();
            document.getElementById('taxAmount').textContent = '₹' + taxAmount.toLocaleString();

            const resultCard = document.getElementById('resultCard');
            resultCard.classList.remove('hidden');
            setTimeout(() => resultCard.classList.add('show'), 100);
        });
    </script>
</body>
</html>