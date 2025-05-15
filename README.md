# Courier Delivery Management System

> This project was developed as the final group project for the CSC264 & ISP250 subject while studying at UiTM Tapah.

This system is designed to streamline parcel delivery requests, session generation, and management for delivery staff and administrators within a college setting. Whether you’re an admin registering new staff, managing requests, or a rider handling delivery sessions, this project offers a complete backend and frontend framework to assist your workflow.

## Project Overview

This PHP-based web application handles delivery requests, organizes them into manageable sessions, and tracks delivery statuses. Users can request deliveries, admin staff can generate sessions and assign staff, and riders can view and update delivery sessions. The system features role-based navigation, multi-step forms, and estimated delivery time calculations for efficient handling.

## Project Requirements

- **Operating Environment**: PHP 5.x or PHP 7.x with MySQL database support.
- **Web Server**: Apache (XAMPP) or compatible with PHP & MySQL.
- **Frontend Requirements**: Modern web browser supporting HTML5, CSS3, JavaScript.
- **Node.js & NPM**: For managing JavaScript dependencies like `transition-style` and `@swup/overlay-theme` via `package.json` and `package-lock.json`.
- **Database**: MySQL with configured connection (`Connections/dbConDelivery.php`).

## Dependencies

- PHP (7.x recommended) with MySQL extension.
- MySQL database - tables for requests, sessions, staff.
- JavaScript libraries:
  - jQuery (AJAX and UI interactions)
  - Chart.js (for dashboard pie charts)
  - Transition-style (CSS transitions)
  - Bootstrap CSS & JS (responsive UI)
- Node packages (for frontend enhancements):
  - `@swup/overlay-theme` — page transition overlays
  - `transition-style` — transition effects

## Getting Started

Follow these steps to get the app running:

### 1. Database Setup

- Create a MySQL database.
- Import or create tables:
  ```sql
  CREATE TABLE `request` (
  `reqID` int NOT NULL AUTO_INCREMENT COMMENT 'request ID, auto generated',
  `trackNO` varchar(256) NOT NULL COMMENT 'Tracking number of parcel',
  `recevNM` varchar(256) NOT NULL COMMENT 'Receiver''s Name',
  `recevRN` int NOT NULL COMMENT 'Receiver''s Room Number',
  `recevCol` varchar(256) NOT NULL COMMENT 'Receiver''s College',
  `reqPN` varchar(265) NOT NULL COMMENT 'Receiver''s Phone Number',
  `isDelivered` tinyint(1) NOT NULL,
  `sesID` int DEFAULT NULL COMMENT 'Foreign Key of Session ID',
  PRIMARY KEY (`reqID`),
  UNIQUE KEY `trackNO` (`trackNO`),
  KEY `sesID` (`sesID`),
  CONSTRAINT `request_ibfk_1` FOREIGN KEY (`sesID`) REFERENCES `session` (`sesID`)
  ) ENGINE=InnoDB AUTO_INCREMENT=238 DEFAULT CHARSET=latin1

  CREATE TABLE `session` (
  `sesID` int NOT NULL AUTO_INCREMENT COMMENT 'session ID, auto generated',
  `staffID` int DEFAULT NULL COMMENT 'Foreign key of Staff ID',
  `isComplete` tinyint(1) NOT NULL,
  PRIMARY KEY (`sesID`),
  KEY `staffID` (`staffID`),
  CONSTRAINT `session_ibfk_1` FOREIGN KEY (`staffID`) REFERENCES `staff` (`staffID`)
  ) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1

  CREATE TABLE `staff` (
  `staffID` int NOT NULL AUTO_INCREMENT COMMENT 'staff ID, auto generated',
  `staffNM` varchar(256) NOT NULL,
  `username` varchar(256) NOT NULL COMMENT 'Staff''s Username',
  `password` varchar(256) NOT NULL COMMENT 'Staff''s Password',
  `staffPN` varchar(256) NOT NULL COMMENT 'Staff''s Phone Number',
  `role` varchar(265) NOT NULL COMMENT 'Staff''s Role',
  PRIMARY KEY (`staffID`),
  UNIQUE KEY `username` (`username`)
  ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1
  ```

### 2. Configure Database Connection

- Update `Connections/dbConDelivery.php` with correct database credentials.

### 3. Web Server Deployment

- Deploy PHP files onto your web server root or virtual host (XAMPP is recomended).
- Ensure document root contains assets directory with CSS, JS, and images.

### 4. Node Dependencies

- Run `npm install` to install JavaScript dependencies for frontend transitions.

## How to run the application

The application is web-based. Access the main page through your browser at your server's address, e.g., `http://localhost/index.php`.

### Application Workflow:

- **Main Page (`index.php`)**: Users enter their parcel tracking number or request delivery.
- **Request Form (`request.php`)**: Multi-step form for users to submit delivery requests.
- **Admin Login (`login.php`)**: Admin or rider login pages to access dashboards.
- **Admin Dashboard (`adminDashboard.php`)**: Shows stats, charts, and recent requests.
- **Rider Dashboard (`riderDashboard.php`)**: Similar interface focused on rider needs.
- **Request List (`requestlist.php`)**: Full list of delivery requests with search and edit/delete.
- **Session Management**:
  - Generate sessions based on unassigned requests (`generateSession.php`).
  - Select sessions (`selSes.php`) allows admin or riders to manage sessions.
  - Update delivery and session statuses with AJAX support and forms.
- **Delivery Status (`status.php`)**: Tracks delivery progress and estimated delivery times.
- **Staff Registration (`signup.php`)**: Admins register new staff users.

## Code Examples Highlights

### 1. Adding a New Delivery Request

The multi-step form collects user info, delivery location, and parcel tracking number before inserting into the database.

```php
$insertSQL = sprintf(
    "INSERT INTO request (trackNO, recevNM, recevRN, recevCol, reqPN) VALUES (%s, %s, %s, %s, %s)",
    GetSQLValueString($_POST['tracking'], "text"),
    GetSQLValueString($_POST['name'], "text"),
    GetSQLValueString($_POST['roomnum'], "int"),
    GetSQLValueString($_POST['college'], "text"),
    GetSQLValueString($_POST['phone'], "text")
);

mysql_select_db($database_dbConDelivery, $dbConDelivery);
mysql_query($insertSQL, $dbConDelivery) or die(mysql_error());
```

### 2. Generating Sessions from Pending Requests

The system groups unassigned requests by their college (`recevCol`), batches them into sets of 5, and assigns them a new session ID.

```php
# Group requests by college
$sql = "SELECT * FROM request WHERE sesID IS NULL";
$result = mysql_query($sql);

$listsByRecevCol = [];

while ($row = mysql_fetch_assoc($result)) {
    $recevCol = $row['recevCol'];
    if (!isset($listsByRecevCol[$recevCol])) {
        $listsByRecevCol[$recevCol] = [];
    }
    $listsByRecevCol[$recevCol][] = $row;
}

foreach ($listsByRecevCol as $recevCol => &$list) {
    while (count($list) >= 5) {
        $tempList = array_slice($list, 0, 5);
        $list = array_slice($list, 5);

        mysql_query("INSERT INTO session (staffID) VALUES (NULL)");
        $sesID = mysql_insert_id();

        foreach ($tempList as $item) {
            $reqID = $item['reqID'];
            mysql_query("UPDATE request SET sesID = $sesID WHERE reqID = $reqID");
        }
    }
}
```

### 3. Updating Delivery Status via AJAX

Checkboxes in the request list send updates asynchronously to mark parcels as delivered.

```javascript
$('input[type="checkbox"]').on('change', function () {
    var reqID = $(this).closest('tr').find('td:first').text();
    var isDelivered = this.checked ? 1 : 0;

    $.ajax({
        type: 'POST',
        url: 'updateIsDelivered.php',
        data: { 'reqID': reqID, 'isDelivered': isDelivered },
        success: function (response) {
            console.log(response);
        },
        error: function (error) {
            console.error('Error updating database:', error);
        }
    });
});
```

### 4. Calculating Estimated Delivery Time

Delivery times depend on the order of requests and their location. The logic sums base times and increments for transitions between locations.

```php
// Define initial and increment times
$initialTimes = ['Alpha' => 7, 'Beta' => 5, 'Gamma' => 12];
$timeIncrement = ['sameCol' => 4, 'diffCol' => 6, 'alphaBetaGamma' => 15];

$totalTime = 0;
$prevCol = null;

while ($row = mysql_fetch_assoc($result)) {
    $currentCol = $row['recevCol'];

    if ($prevCol === null) {
        $totalTime += $initialTimes[$currentCol];
    } elseif ($prevCol === $currentCol) {
        $totalTime += $timeIncrement['sameCol'];
    } elseif (
        ($prevCol === 'Alpha' || $prevCol === 'Beta') && $currentCol === 'Gamma'
        || ($prevCol === 'Gamma' && ($currentCol === 'Alpha' || $currentCol === 'Beta'))
    ) {
        $totalTime += $timeIncrement['alphaBetaGamma'];
    } else {
        $totalTime += $timeIncrement['diffCol'];
    }

    $prevCol = $currentCol;
}

echo "$totalTime minutes";
```

## Additional Features

- **Role-Based Navigation**: Sidebar menus change dynamically based on whether user is admin or rider.
- **Real-time Search**: Filter requests by tracking number.
- **Interactive Dashboards**: Visual charts for request distribution and statuses.
- **Session Selection and Completion**: Riders select and complete sessions to track progress.
- **AJAX-Enhanced UI**: Update delivery statuses and session completions seamlessly.
- **Google Maps Embed**: Show parcel delivery location on a map in status pages.

## Conclusion

This Delivery CMS project provides a comprehensive solution to efficiently handle parcel deliveries within a university campus or similar environments. It combines structured session management, real-time updates, and intuitive interfaces for admin and delivery riders. Whether you want to customize the delivery logic, extend the dashboard analytics, or integrate more authentication methods, this codebase offers a solid foundation.

Feel free to explore the source code, contribute enhancements, or raise issues to improve this project. Your feedback and collaboration are most welcome!
