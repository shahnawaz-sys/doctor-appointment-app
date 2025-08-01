<?php

include('conn file\conn.php');


if (isset($_POST['saveappointment'])) {

    $patientname = $_POST['patientname'];
    $patientcnic = $_POST['patientcnic'];
    $number = $_POST['number'];
    $reason = $_POST['reason'];
    $date = $_POST['date'];

    $getapp = "INSERT into patients (name ,cnic ,number ,reason ) value('$patientname','$patientcnic','$number','$reason')";
    $appoitqry = mysqli_query($conn, $getapp);
    if ($appoitqry) {
        echo "<script> alert('appointed') </script>";
    } else {
        echo "<script> alert('please try again') </script>";
    }
}
// -------------------------------



// -------------Fetch data of patient data------------------
$selectpatientdata = "SELECT * FROM patients where status='Pending'";
$runpatientdata = mysqli_query($conn, $selectpatientdata);
// ------------------------------




// ------------delete and comform data------------------
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $deleteQuery = "DELETE FROM patients WHERE id = '$id'";
    $runDelete = mysqli_query($conn, $deleteQuery);
}

if (isset($_POST['confirm'])) {
    $id = $_POST['id'];

    // Update status to confirmed (example)
    $update = "UPDATE patients SET status='checked' WHERE id=$id";
    $run = mysqli_query($conn, $update);
    if ($run) {
        header("Location: index.php");
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show py-1 px-2 small" role="alert">
                <strong>Error:</strong> Update failed: ' . mysqli_error($conn) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
}


// ------------------------------




// -----------Query for dashboard---------------
$countqry = "SELECT COUNT(*) AS total FROM patients WHERE status = 'Confirmed'";
$runqry = mysqli_query($conn, $countqry);
$fetch = mysqli_fetch_assoc($runqry);
$total = $fetch['total'];

// ---------query for pending-----
// Pending
$pendingQuery = "SELECT COUNT(*) AS total FROM patients WHERE status = 'Pending'";
$pendingResult = mysqli_query($conn, $pendingQuery);
$pending = mysqli_fetch_assoc($pendingResult)['total'];
// ------------------------

// ---------------query for total appontment--
// Total Appointments
$totalQuery = "SELECT COUNT(*) AS total FROM patients ";
$totalResult = mysqli_query($conn, $totalQuery);
$totalAppointments = mysqli_fetch_assoc($totalResult)['total'];

// --------------------------------------------

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="css-file\style.css">

</head>

<body>

    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-stethoscope"></i>
                <h2>MediCare</h2>
            </div>

            <div class="profile">
                <img src="images\doctor.jpg"
                    alt="Profile Picture">
                <h3 style="font-size: 20px;">Dr. Ahmad Khan</h3>
                <p>MBBS, MD, FACP</p>
            </div>

            <div class="nav-buttons">
                <button class="btn btn-primary active" onclick="showView('dashboard')">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </button>
                <button class="btn btn-secondary" onclick="showView('appointments')">
                    <i class="fas fa-calendar"></i> All Appointments
                </button>
                <button class="btn btn-secondary" onclick="showView('add-appointment')">
                    <i class="fas fa-plus"></i> Add Appointment
                </button>
                <button class="btn btn-secondary" onclick="showView('patients')">
                    <i class="fas fa-users"></i> Patients
                </button>
            </div>
        </div>

        <div class="header">
            <h1><i class="fas fa-heartbeat"></i> Doctor Appointment System</h1>
            <p style="color: rgba(255,255,255,0.7); margin-top: 10px;">Manage your medical appointments with ease</p>
        </div>

        <div class="main-content">
            <div id="dashboard" class="content-card">
                <h2><i class="fas fa-chart-line"></i> Dashboard Overview</h2>

                <div class="dashboard-stats">
                    <div class="stat-card blue">
                        <i class="fas fa-calendar-check"></i>
                        <div class="stat-number" id="total-appointments"><?php echo $totalAppointments ?></div>
                        <div>Total Appointments</div>
                    </div>
                    <div class="stat-card green">
                        <i class="fas fa-check-circle"></i>
                        <div class="stat-number" id="confirmed-appointments"><?php echo $total ?></div>
                        <div>Checked</div>
                    </div>
                    <div class="stat-card pink">
                        <i class="fas fa-clock"></i>
                        <div class="stat-number" id="pending-appointments"><?php echo $pending ?></div>
                        <div>Pending</div>
                    </div>
                </div>

                <div class="upcoming-appointments">
                    <h3><i class="fas fa-calendar-day"></i> Today's Appointments</h3>
                    <div id="today-appointments">
                        <p style="text-align: center; color: rgba(255,255,255,0.7); padding: 20px;">No appointments
                            scheduled for today.</p>
                    </div>
                </div>
            </div>

            <div id="appointments" class="content-card hidden">
                <h2><i class="fas fa-list"></i> All Appointments</h2>

                <div class="search-box">
                    <input type="text" class="form-control" placeholder="Search appointments..." id="search-input">
                    <i class="fas fa-search"></i>
                </div>

                <table class="appointments-table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Date & Time</th>
                            <th>Contact</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="appointments-tbody">

                        <?php while ($fetch = mysqli_fetch_assoc($runpatientdata)) { ?>
                            <tr>
                                <td><?php echo $fetch['name']; ?></td>
                                <td>
                                    <i class="fas fa-calendar"></i> <?php echo $fetch['date']; ?><br>
                                    <i class="fas fa-clock"></i> <?php echo $fetch['time']; ?>
                                </td>
                                <td>
                                    <i class="fas fa-id-card"></i> <?php echo $fetch['cnic']; ?><br>
                                    <i class="fas fa-phone"></i> <?php echo $fetch['number']; ?>
                                </td>
                                <td><?php echo $fetch['reason']; ?></td>
                                <td><span class="status-badge" style="background-color: #856404;"><?php echo $fetch['status']; ?></span></td>





                                <td class="action-buttons">
                                    <!-- Confirm (tick) button -->


                                    <!-- Close (X) button -->

                                    <form method="POST" onsubmit="return confirm('Are you sure you want to confirm this appointment?');">
                                        <input type="hidden" name="id" value="<?php echo $fetch['id']; ?>">
                                        <button type="submit" class="btn btn-success btn-sm" title="Confirm" name="confirm">
                                            <i class="fas fa-check" style="margin:0 auto;"></i>
                                        </button>
                                    </form>

                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this patient?');">
                                        <input type="hidden" name="id" value="<?php echo $fetch['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" name="delete" title="Delete">
                                            <i class="fas fa-trash" style="margin:0 auto;"></i>
                                        </button>
                                    </form>

                                </td>

                            </tr>
                        <?php }



                        ?>
                    </tbody>
                </table>
            </div>

            <div id="add-appointment" class="content-card hidden">
                <h2><i class="fas fa-plus-circle"></i> Add New Appointment</h2>

                <form id="appointment-form" action="?" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="patient-name">Patient Name *</label>
                            <input type="text" class="form-control" id="patient-name" name="patientname" required>
                        </div>
                        <div class="form-group">
                            <label for="patient-email">CNIC NO *</label>
                            <input type="number" class="form-control" id="patient-email" name="patientcnic" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="patient-phone">Phone Number</label>
                            <input type="tel" class="form-control" id="patient-phone" name="number" required>
                        </div>
                        <div class="form-group">
                            <label for="appointment-reason">Reason for Visit *</label>
                            <input type="text" class="form-control" id="appointment-reason" name="reason" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="appointment-date">Date *</label>
                            <input type="date" class="form-control" id="appointment-date" name="date" required>
                        </div>
                        <div class="form-group">
                            <label for="appointment-time">Time *</label>
                            <input type="time" class="form-control" id="appointment-time" name="time" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" name="saveappointment">
                        <i class="fas fa-save"></i>Save Appointment
                    </button>
                </form>
            </div>

            <div id="patients" class="content-card hidden">
                <h2><i class="fas fa-users"></i> Patient List</h2>
                <div id="patients-list">
                    <table class="appointments-table">
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>Date & Time</th>
                                <th>Contact</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="appointments-tbody">

                            <?php

                            // -------------Fetch data of patient data------------------
                            $patientdata = "SELECT * FROM patients where status='checked'";
                            $rundata = mysqli_query($conn, $patientdata);
                            // ------------------------------


                            while ($fetch = mysqli_fetch_assoc($rundata)) { ?>
                                <tr>
                                    <td><?php echo $fetch['name']; ?></td>
                                    <td>
                                        <i class="fas fa-calendar"></i> <?php echo $fetch['date']; ?><br>
                                        <i class="fas fa-clock"></i> <?php echo $fetch['time']; ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-id-card"></i> <?php echo $fetch['cnic']; ?><br>
                                        <i class="fas fa-phone"></i> <?php echo $fetch['number']; ?>
                                    </td>
                                    <td><?php echo $fetch['reason']; ?></td>
                                    <td><span class="status-badge status-confirmed"><?php echo $fetch['status']; ?></span></td>
                                    
                                    <td class="action-buttons">

                                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this patient?');">
                                            <input type="hidden" name="id" value="<?php echo $fetch['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" name="delete" title="Delete">
                                                <i class="fas fa-trash" style="margin:0 auto;"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="js file\functions.js"></script>
</body>

</html>