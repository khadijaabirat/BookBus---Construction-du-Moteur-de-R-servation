# BookBus — Bus Reservation Engine

BookBus is a Laravel-based bus reservation platform designed for a single transportation company.
The system provides an intelligent booking engine with segmented pricing, seat reservation, trip management, and administrative operations.

The project focuses on scalable backend architecture, relational database modeling, and real-world transportation business logic.

---

## Application Preview

Screenshots and UI previews available below.

---

## Product Overview

The application was designed to simulate a real-world reservation system for a transportation company managing:

* Bus fleets
* Drivers and employees
* Routes and schedules
* Segment-based pricing
* Passenger reservations
* Administrative operations

Unlike traditional booking systems, the platform uses an independent pricing model where each route segment has its own dedicated fare.

Example:

* Casablanca → Marrakech = 120 MAD
* Casablanca → Settat = 50 MAD
* Settat → Marrakech = 70 MAD

Prices are independently defined and not automatically cumulative.

---

## Core Features

### Reservation Engine

* Search available trips
* Segment-based booking system
* Seat selection system
* Passenger reservation management
* Real-time seat availability

### Smart Pricing System

* Independent segment pricing
* Dynamic fare calculation
* Premium bus pricing management
* Promotional discount support

### Fleet & Driver Management

* Bus assignment management
* Driver assignment system
* Bus maintenance status tracking
* Driver availability validation

### Administrative Dashboard

* Trip creation and cancellation
* Route and schedule management
* Fleet monitoring
* Reservation tracking
* Business statistics overview

### Customer Features

* Ticket booking
* Booking cancellation
* Loyalty points integration
* Promo code support
* Email confirmation system

---

## Tech Stack

### Backend

* Laravel
* PHP
* Eloquent ORM
* MVC Architecture

### Database

* MySQL

### Frontend

* Blade Templates
* HTML5
* CSS3
* JavaScript

### Tools

* Git & GitHub
* Composer

---

## Architecture Overview

The application follows Laravel’s MVC architecture:

### Models

Handle:

* Database relationships
* Business logic
* Fare calculations
* Reservation validation

### Controllers

Handle:

* HTTP requests
* Booking workflows
* Admin operations
* Search logic

### Views

Handle:

* User interface rendering
* Reservation forms
* Dashboard visualization

---

## Database Design

Main entities implemented:

* Users
* Employees
* Cities
* Stations
* Buses
* Routes
* Stops
* Segments
* Fares
* Schedules
* Trips
* Bookings
* Passengers
* Assignments

---

## Key Technical Highlights

* Complex relational database modeling
* Segment-based pricing architecture
* Eloquent ORM relationships
* Seat availability management
* Route validation system
* Business rule implementation
* Admin management workflows
* Real-world transportation logic

---

## Business Rules Implemented

* A bus cannot be assigned to multiple trips simultaneously
* Drivers cannot exceed daily driving limits
* Premium buses apply additional pricing
* Invalid travel segments are automatically rejected
* Seat capacity validation before booking

---

## Challenges Faced

* Designing the segmented pricing system
* Managing complex Eloquent relationships
* Handling reservation validation logic
* Synchronizing bus capacity and bookings
* Implementing scalable route architecture

---

## Performance & Best Practices

* Clean Laravel MVC structure
* Reusable Eloquent relationships
* Validation and secure request handling
* Maintainable business logic
* Responsive user interface
* Organized codebase structure

---

## Future Improvements

* Online payment integration
* QR ticket generation
* Real-time bus tracking
* Multi-company support
* Mobile application
* Notification system
* API integration

---

## Project Structure

```bash
app/
├── Models/
├── Http/Controllers/
├── Services/
├── Policies/

database/
├── migrations/
├── seeders/

resources/
├── views/
```

---


## Application Preview
<img width="1763" height="2894" alt="Capture d’écran_26-5-2026_134929_127 0 0 1" src="https://github.com/user-attachments/assets/e08ca159-7b7f-4e3d-b0e8-ebc024095cf5" />
<img width="1763" height="2894" alt="Capture d’écran_26-5-2026_134929_127 0 0 1" src="https://github.com/user-attachments/assets/5897f02d-a10e-4a1c-866b-0d10baef1b3e" />
 <img width="1763" height="938" alt="Capture d’écran_23-5-2026_174836_127 0 0 1" src="https://github.com/user-attachments/assets/192e871e-9296-41d6-ae09-ec2f91868888" />
<img width="1763" height="1214" alt="Capture d’écran_23-5-2026_175342_127 0 0 1" src="https://github.com/user-attachments/assets/160b8859-7708-4f0c-93d8-a200ce0ae341" />
<img width="1763" height="1000" alt="Capture d’écran_23-5-2026_20628_127 0 0 1" src="https://github.com/user-attachments/assets/462f643a-7d1d-4cd2-8cc4-c81e6c25f31c" />
<img width="1763" height="1075" alt="Capture d’écran_23-5-2026_2088_127 0 0 1" src="https://github.com/user-attachments/assets/49fbe611-e88e-4e1c-a41b-b1b596a59568" />
<img width="1763" height="971" alt="Capture d’écran_23-5-2026_212839_127 0 0 1" src="https://github.com/user-attachments/assets/050cd44d-d3fb-40e4-94f5-94e5e70b06dd" />
<img width="1763" height="1033" alt="Capture d’écran_23-5-2026_212926_127 0 0 1" src="https://github.com/user-attachments/assets/6238a378-b88a-4ddd-8b35-d886aa531fd4" />
<img width="1763" height="971" alt="Capture d’écran_23-5-2026_212954_127 0 0 1" src="https://github.com/user-attachments/assets/9414984d-043a-4f85-8efd-852937ebbb6d" />
<img width="1763" height="1385" alt="Capture d’écran_23-5-2026_213222_127 0 0 1" src="https://github.com/user-attachments/assets/b9759168-fb08-4f88-ac05-afb7891ccfd3" />
<img width="1763" height="971" alt="Capture d’écran_23-5-2026_213950_127 0 0 1" src="https://github.com/user-attachments/assets/1f8c4dfc-438e-447a-966c-eba22697d073" />
<img width="1763" height="981" alt="Capture d’écran_23-5-2026_215848_127 0 0 1" src="https://github.com/user-attachments/assets/852d9e63-eb9e-4039-81af-6f665567dd8c" />
<img width="1763" height="971" alt="Capture d’écran_23-5-2026_21599_127 0 0 1" src="https://github.com/user-attachments/assets/9017f0c0-bee5-46bb-a164-9c4ea2eeccec" />
<img width="1763" height="1811" alt="Capture d’écran_23-5-2026_22033_127 0 0 1" src="https://github.com/user-attachments/assets/f399d2ae-f19d-4996-b4b3-4dba79afe24a" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_155248_127 0 0 1" src="https://github.com/user-attachments/assets/b6297493-5fde-43f5-b809-3143aa580455" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_155310_127 0 0 1" src="https://github.com/user-attachments/assets/fc8d03ed-c580-4eac-b5fa-e356deece7b5" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_155559_127 0 0 1" src="https://github.com/user-attachments/assets/8cd29c92-7196-47d0-8dfc-18e44266339e" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_155613_127 0 0 1" src="https://github.com/user-attachments/assets/a266af07-042e-4593-85c4-f020634f1526" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_155720_127 0 0 1" src="https://github.com/user-attachments/assets/e3abb7bd-0d20-4b53-9ea9-1628f25d768d" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_155744_127 0 0 1" src="https://github.com/user-attachments/assets/dbab9954-4943-4a69-b8e7-5741a4a992fa" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_16127_127 0 0 1" src="https://github.com/user-attachments/assets/99fa5ade-2d67-44ca-9570-6ea6b2348fa7" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_16334_127 0 0 1" src="https://github.com/user-attachments/assets/73459d42-d518-4e17-8a3a-b70194534970" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_16422_127 0 0 1" src="https://github.com/user-attachments/assets/4baeaef4-0ac3-43b0-a9bb-372b83148891" />
<img width="1763" height="971" alt="Capture d’écran_24-5-2026_222829_127 0 0 1" src="https://github.com/user-attachments/assets/f8599827-d6b9-4003-b4d9-e2516e36d62f" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_223149_127 0 0 1" src="https://github.com/user-attachments/assets/45bdec2b-c7fa-4293-96a3-f96acaa26565" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_22320_127 0 0 1" src="https://github.com/user-attachments/assets/3f027782-c282-44fe-b2f5-22cf3483c3e4" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_223251_127 0 0 1" src="https://github.com/user-attachments/assets/783f2594-a917-42ff-af66-74add18f7bd5" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_22335_127 0 0 1" src="https://github.com/user-attachments/assets/abeed53d-01e5-4075-af62-b79624606294" />
<img width="1763" height="844" alt="Capture d’écran_24-5-2026_223349_127 0 0 1" src="https://github.com/user-attachments/assets/a1d69370-fb4a-4d79-8269-39447bdba275" />



**Khadija Abirat**
Full-Stack Developer
Passionate about scalable web applications, backend architecture, and real-world business systems.
