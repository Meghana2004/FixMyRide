
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Debugging information


$vehicleType = $vehicleBrand = $vehicleModel = $vehicleRegNo = $serviceDate = $serviceType = $otherService = $contactNo = $area = $district = "";
$vehicleTypeErr = $vehicleBrandErr = $vehicleModelErr = $vehicleRegNoErr = $serviceDateErr = $serviceTypeErr = $contactNoErr = $areaErr = $districtErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["vehicleType"])) {
        $vehicleTypeErr = "Vehicle Type is required";
    } else {
        $vehicleType = test_input($_POST["vehicleType"]);
    }

    if (empty($_POST["vehicleBrand"])) {
        $vehicleBrandErr = "Vehicle Brand is required";
    } else {
        $vehicleBrand = test_input($_POST["vehicleBrand"]);
        if ($vehicleBrand == "Other" && empty($_POST["otherBrand"])) {
            $vehicleBrandErr = "Please specify the vehicle brand";
        }
    }

    if (empty($_POST["vehicleModel"])) {
        $vehicleModelErr = "Vehicle Model is required";
    } else {
        $vehicleModel = test_input($_POST["vehicleModel"]);
        if ($vehicleModel == "Other" && empty($_POST["otherModel"])) {
            $vehicleModelErr = "Please specify the vehicle model";
        }
    }

    if (empty($_POST["vehicleRegNo"])) {
        $vehicleRegNoErr = "Vehicle Registration Number is required";
    } elseif (!preg_match("/^[A-Z]{2}[0-9]{2}\s?[A-Z]{1,2}\s?[0-9]{1,4}$/", $_POST["vehicleRegNo"])) {
        $vehicleRegNoErr = "Invalid Vehicle Registration Number";
    } else {
        $vehicleRegNo = test_input($_POST["vehicleRegNo"]);
    }

    if (empty($_POST["serviceDate"])) {
        $serviceDateErr = "Service Date is required";
    } else {
        $serviceDate = test_input($_POST["serviceDate"]);
        if (strtotime($serviceDate) < strtotime('today')) {
            $serviceDateErr = "Service Date cannot be in the past";
        }
    }

    if (empty($_POST["serviceType"])) {
        $serviceTypeErr = "Service Type is required";
    } else {
        $serviceType = test_input($_POST["serviceType"]);
        if ($serviceType == "Other" && empty($_POST["otherService"])) {
            $serviceTypeErr = "Please specify the service type";
        } else {
            $otherService = test_input($_POST["otherService"]);
        }
    }

    if (empty($_POST["contactNo"])) {
        $contactNoErr = "Contact Number is required";
    } elseif (!preg_match("/^[0-9]{10}$/", $_POST["contactNo"])) {
        $contactNoErr = "Contact Number must be 10 digits long";
    } else {
        $contactNo = test_input($_POST["contactNo"]);
    }

    if (empty($_POST["area"])) {
        $areaErr = "Area/Locality is required";
    } else {
        $area = test_input($_POST["area"]);
    }

    if (empty($_POST["district"])) {
        $districtErr = "District is required";
    } else {
        $district = test_input($_POST["district"]);
    }

    if (empty($vehicleTypeErr) && empty($vehicleBrandErr) && empty($vehicleModelErr) && empty($vehicleRegNoErr) && empty($serviceDateErr) && empty($serviceTypeErr) && empty($contactNoErr) && empty($areaErr) && empty($districtErr)) {
        // Generate a unique service number
        $serviceNumber = "SRV" . time() . rand(100, 999);
        // Database insertion
        $servername = "localhost";
        $username = "root";
        $password = ""; // Update this with your actual password
        $dbname = "fixmyride";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Debugging information
        echo "Attempting to insert with user ID: " . htmlspecialchars($userId) . "<br>";

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users_service (user_id, service_number, vehicle_type, vehicle_brand, vehicle_model, vehicle_reg_no, service_date, service_type, other_service, contact_no, area, district) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $bind = $stmt->bind_param("ssssssssssss", $userId, $serviceNumber, $vehicleType, $vehicleBrand, $vehicleModel, $vehicleRegNo, $serviceDate, $serviceType, $otherService, $contactNo, $area, $district);
        if ($bind === false) {
            die('Bind failed: ' . htmlspecialchars($stmt->error));
        }

        $exec = $stmt->execute();
        if ($exec === false) {
            die('Execute failed: ' . htmlspecialchars($stmt->error));
        }

        $stmt->close();
        $conn->close();

        echo "<script>alert('Form submitted successfully! Your service number is $serviceNumber');</script>";
        echo "<script>window.location = 'success.php?serviceNumber=$serviceNumber';</script>";
        exit();
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            margin-top: 150px;
        }
        .container {
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 40%;
            border-radius: 10px;
        }
        .container h1 {
            text-align: center;
            color: #e8491d;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="checkbox"] {
            width: auto;
        }
        .form-group .terms {
            display: flex;
            align-items: center;
        }
        .form-group .terms input {
            margin-right: 5px;
        }
        .form-group .terms label {
            margin: 0;
        }
        .form-group .terms a {
            margin-left: 5px;
            color: #009688;
            text-decoration: none;
        }
        .form-group .terms a:hover {
            text-decoration: underline;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #e8491d;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
        }
        .form-group button:hover {
            background-color: #FF7518;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
        .fixmyride {
            position: absolute;
            left: 20px;
            top: 20px;
            font-size: 35px;
            font-weight: bold;
        }
    </style>
    <script>
        const vehicleModels = {
            "Toyota": ["Corolla", "Camry", "Rav4", "Highlander"],
            "Honda": ["Accord", "Civic", "CR-V", "Pilot"],
            "Ford": ["Fusion", "Escape", "Explorer", "F-150"],
            "Chevrolet": ["Malibu", "Equinox", "Silverado", "Tahoe"],
            "Nissan": ["Altima", "Rogue", "Sentra", "Pathfinder"],
            "Hyundai": ["Elantra", "Sonata", "Tucson", "Santa Fe"],
            "Jeep": ["Wrangler", "Grand Cherokee", "Cherokee", "Compass"],
            "BMW": ["3 Series", "5 Series", "X3", "X5"],
            "Mercedes-Benz": ["C-Class", "E-Class", "GLC", "GLE"],
            "Audi": ["A4", "A6", "Q5", "Q7"]
        };

        function populateModels() {
            const brandSelect = document.getElementById("vehicleBrand");
            const modelSelect = document.getElementById("vehicleModel");
            const selectedBrand = brandSelect.value;

            modelSelect.innerHTML = "";

            if (selectedBrand) {
                const models = vehicleModels[selectedBrand] || [];
                models.forEach(model => {
                    const option = document.createElement("option");
                    option.value = model;
                    option.text = model;
                    modelSelect.add(option);
                });
                const otherOption = document.createElement("option");
                otherOption.value = "Other";
                otherOption.text = "Other";
                modelSelect.add(otherOption);
            }
        }

        function toggleOtherFields() {
            const brandSelect = document.getElementById("vehicleBrand");
            const modelSelect = document.getElementById("vehicleModel");
            const otherBrandField = document.getElementById("otherBrandField");
            const otherModelField = document.getElementById("otherModelField");
            const otherServiceField = document.getElementById("otherService");

            otherBrandField.style.display = brandSelect.value === "Other" ? "block" : "none";
            otherModelField.style.display = modelSelect.value === "Other" ? "block" : "none";
            otherServiceField.style.display = document.getElementById("serviceType").value === "Other" ? "block" : "none";
        }

        window.onload = function() {
            populateModels();
            toggleOtherFields();
        };
    </script>
</head>
<body>
    <div class="fixmyride">FixMyRide</div>
    <div class="container">
        <h1>Service Form</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="vehicleType">Vehicle Type</label>
                <select id="vehicleType" name="vehicleType" required>
                    <option value="">Select</option>
                    <option value="Four Wheeler" <?php if ($vehicleType == "Four Wheeler") echo "selected"; ?>>Four Wheeler</option>
                </select>
                <span class="error"><?php echo $vehicleTypeErr; ?></span>
            </div>
        
            <div class="form-group">
                <label for="vehicleBrand">Vehicle Brand</label>
                <select id="vehicleBrand" name="vehicleBrand" onchange="populateModels(); toggleOtherFields();" required>
                    <option value="">Select</option>
                    <option value="Toyota" <?php if ($vehicleBrand == "Toyota") echo "selected"; ?>>Toyota</option>
                    <option value="Honda" <?php if ($vehicleBrand == "Honda") echo "selected"; ?>>Honda</option>
                    <option value="Ford" <?php if ($vehicleBrand == "Ford") echo "selected"; ?>>Ford</option>
                    <option value="Chevrolet" <?php if ($vehicleBrand == "Chevrolet") echo "selected"; ?>>Chevrolet</option>
                    <option value="Nissan" <?php if ($vehicleBrand == "Nissan") echo "selected"; ?>>Nissan</option>
                    <option value="Hyundai" <?php if ($vehicleBrand == "Hyundai") echo "selected"; ?>>Hyundai</option>
                    <option value="Jeep" <?php if ($vehicleBrand == "Jeep") echo "selected"; ?>>Jeep</option>
                    <option value="BMW" <?php if ($vehicleBrand == "BMW") echo "selected"; ?>>BMW</option>
                    <option value="Mercedes-Benz" <?php if ($vehicleBrand == "Mercedes-Benz") echo "selected"; ?>>Mercedes-Benz</option>
                    <option value="Audi" <?php if ($vehicleBrand == "Audi") echo "selected"; ?>>Audi</option>
                    <option value="Other" <?php if ($vehicleBrand == "Other") echo "selected"; ?>>Other</option>
                </select>
                <span class="error"><?php echo $vehicleBrandErr; ?></span>
                <div id="otherBrandField" style="display: none;">
                    <input type="text" id="otherBrand" name="otherBrand" placeholder="Specify other brand" value="<?php echo isset($_POST['otherBrand']) ? htmlspecialchars($_POST['otherBrand']) : ''; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="vehicleModel">Vehicle Model</label>
                <select id="vehicleModel" name="vehicleModel" onchange="toggleOtherFields();" required>
                    <option value="">Select</option>
                </select>
                <span class="error"><?php echo $vehicleModelErr; ?></span>
<div id="otherModelField" style="display: none;">
<input type="text" id="otherModel" name="otherModel" placeholder="Specify other model" value="<?php echo isset($_POST['otherModel']) ? htmlspecialchars($_POST['otherModel']) : ''; ?>">
</div>
</div>
<div class="form-group">
<label for="vehicleRegNo">Vehicle Registration Number</label>
<input type="text" id="vehicleRegNo" name="vehicleRegNo" value="<?php echo $vehicleRegNo; ?>" required>
<span class="error"><?php echo $vehicleRegNoErr; ?></span>
</div>
<div class="form-group">
<label for="serviceDate">Service Date</label>
<input type="date" id="serviceDate" name="serviceDate" value="<?php echo $serviceDate; ?>" required>
<span class="error"><?php echo $serviceDateErr; ?></span>
</div>
<div class="form-group">
<label for="serviceType">Service Type</label>
<select id="serviceType" name="serviceType" onchange="toggleOtherFields();" required>
<option value="">Select</option>
<option value="Oil Change" <?php if ($serviceType == "Oil Change") echo "selected"; ?>>Oil Change</option>
<option value="Brake Inspection" <?php if ($serviceType == "Brake Inspection") echo "selected"; ?>>Brake Inspection</option>
<option value="Tire Rotation" <?php if ($serviceType == "Tire Rotation") echo "selected"; ?>>Tire Rotation</option>
<option value="Battery Check" <?php if ($serviceType == "Battery Check") echo "selected"; ?>>Battery Check</option>
<option value="Other" <?php if ($serviceType == "Other") echo "selected"; ?>>Other</option>
</select>
<span class="error"><?php echo $serviceTypeErr; ?></span>
<div id="otherService" style="display: none;">
<input type="text" name="otherService" placeholder="Specify other service" value="<?php echo isset($_POST['otherService']) ? htmlspecialchars($_POST['otherService']) : ''; ?>">
</div>
</div>
<div class="form-group">
<label for="contactNo">Contact Number</label>
<input type="text" id="contactNo" name="contactNo" value="<?php echo $contactNo; ?>" required>
<span class="error"><?php echo $contactNoErr; ?></span>
</div>
<div class="form-group">
                <label for="area">Area/Locality:</label>
                <input type="text" id="area" name="area" value="<?php echo htmlspecialchars($area); ?>">
                <span class="error"><?php echo $areaErr;?></span>
            </div>

            <div class="form-group">
                <label for="district">District:</label>
                <input type="text" id="district" name="district" value="<?php echo htmlspecialchars($district); ?>">
                <span class="error"><?php echo $districtErr;?></span>
            </div>
<div class="form-group terms">
<input type="checkbox" id="terms" name="terms" required>
<label for="terms">I agree to the <a href="#">terms and conditions</a></label>
</div>
<div class="form-group">
<button type="submit">Submit</button>
</div>
</form>
</div>

</body>
</html>
